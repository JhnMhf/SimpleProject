<?php

namespace UR\AmburgerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Query\ResultSetMapping;

use UR\DB\OldDBBundle\Entity\Person;
use UR\DB\NewDBBundle\Utils\MigrateData;


class MigrateController extends Controller
{

    private $LOGGER;

    private function getLogger()
    {
        if(is_null($this->LOGGER)){
            $this->LOGGER = $this->get('monolog.logger.migrateOld');
        }
        
        return $this->LOGGER;
    }

    public function personAction($ID)
    {

        $this->getLogger()->info("Migrate Request for Person with ID ". $ID);
        $person = $this->migratePerson($ID);

        if(is_null($person)){
            return new Response("Invalid ID");
        }

        return new Response(
            'Migrated Database entry: '.$person->getId()
        );
    }

    private function getIDForOID($OID, $oldDBManager){

        $IDData = $oldDBManager->getRepository('OldBundle:Ids')->findOneByOid($OID);

        return $IDData->getId();
    }

    private function migratePerson($ID, $OID = null){
                // get old data
        $oldDBManager = $this->get('doctrine')->getManager('old');

        $person = $oldDBManager->getRepository('OldBundle:Person')->findOneById($ID);

        if(!$person){
            return null;
        }

        if(is_null($OID)){
            $IDData = $oldDBManager->getRepository('OldBundle:Ids')->findOneById($ID);

            $OID = $IDData->getOid();
        }


        $existingPerson = $this->get("migrate_data.service")->getNewPersonForOid($OID);

        if(!is_null($existingPerson)){
            //person already migrated, so just return it
            return $existingPerson;
        }

        $newPerson = $this->get("migrate_data.service")->migratePerson($OID, $person->getVornamen(), $person->getRussVornamen(), $person->getName(), $person->getRufnamen(),$person->getGeburtsname(), $person->getGeschlecht(), $person->getBerufsklasse(), $person->getKommentar());

        $this->migrateDataFromIndexID($newPerson, $ID, $oldDBManager);

        $this->migrateBirthController($newPerson, $ID, $oldDBManager);

        $this->migrateBaptismController($newPerson, $ID, $oldDBManager);

        $this->migrateDeathController($newPerson, $ID, $oldDBManager);

        $this->migrateReligionController($newPerson, $ID, $oldDBManager);

        $this->migrateOriginalNation($newPerson, $person);

        $this->migrateSource($newPerson, $ID, $oldDBManager);

        $this->migrateWorks($newPerson, $ID, $oldDBManager);

        $this->migrateHonour($newPerson, $ID, $oldDBManager);

        $this->migrateProperty($newPerson, $ID, $oldDBManager);

        $this->migrateRank($newPerson, $ID, $oldDBManager);

        $this->migrateEducation($newPerson, $ID, $oldDBManager);

        $this->migrateStatus($newPerson, $ID, $oldDBManager);

        $this->migrateRoadOfLife($newPerson, $ID, $oldDBManager);


        //save updated newPerson to database

        $this->get("migrate_data.service")->savePerson($newPerson);


        //migrate relations (including data about relatives)
        $this->migrateMother($newPerson, $ID, $oldDBManager);

        $this->migrateFather($newPerson, $ID, $oldDBManager);

        $this->migrateSibling($newPerson, $ID, $oldDBManager);

        $this->migrateGrandmothers($newPerson, $ID, $oldDBManager);

        $this->migrateGrandfathers($newPerson, $ID, $oldDBManager);

        $this->migrateMarriagePartner($newPerson, $ID, $oldDBManager);

        return $newPerson;
    }

    private function migrateDeathController($newPerson, $oldPersonID, $oldDBManager){

        $tod = $oldDBManager->getRepository('OldBundle:Tod')->findOneById($oldPersonID);

        //if necessary get more informations from other tables
        if(!is_null($tod)){
            $newTodId = $this->get("migrate_data.service")->migrateDeath($tod->getTodesort(), $tod->getGestorben(), $tod->getTodesland(), $tod->getTodesursache(), $tod->getTodesterritorium(), $tod->getFriedhof(), $tod->getBegräbnisort(), $tod->getBegraben(), $tod->getKommentar());
        
            $newPerson->setDeathid($newTodId);
        }
    }

    private function migrateBirthController($newPerson, $oldPersonID, $oldDBManager){

        $birth = $oldDBManager->getRepository('OldBundle:Herkunft')->findOneById($oldPersonID);

        //if necessary get more informations from other tables


        if(!is_null($birth)){
            $newBirthId = $this->get("migrate_data.service")->migrateBirth($birth->getHerkunftsland(),$birth->getHerkunftsterritorium(),$birth->getHerkunftsort(),$birth->getGeburtsland(),$birth->getGeburtsort(),$birth->getGeboren(),$birth->getGeburtsterritorium(),$birth->getKommentar());
            

            $newPerson->setBirthid($newBirthId);
        }
    }


    private function migrateBaptismController($newPerson, $oldPersonID, $oldDBManager){

        $birth = $oldDBManager->getRepository('OldBundle:Herkunft')->findOneById($oldPersonID);

        //if necessary get more informations from other tables
        if(!is_null($birth) && (!is_null($birth->getGetauft()) || !is_null($birth->getTaufort()))){
            $newBaptismId = $this->get("migrate_data.service")->migrateBaptism($birth->getGetauft(),$birth->getTaufort());

            $newPerson->setBaptismid($newBaptismId);
        }
    }

    private function migrateReligionController($newPerson, $oldPersonID, $oldDBManager){
        //find by id because there can be multiple religion for one person
        //$religions = $oldDBManager->getRepository('OldBundle:Religion')->findById($oldPersonID);
        $religions = $this->getReligionDataWithNativeQuery($oldPersonID, $oldDBManager);

        if(count($religions) > 0){
            $religionIDString = "";

            for($i = 0; $i < count($religions); $i++){
                $oldReligion = $religions[$i];
                $newReligionID = $this->get("migrate_data.service")->migrateReligion($oldReligion["konfession"], $oldReligion["order"], $oldReligion["konversion"], $oldReligion["belegt"], $oldReligion["von-ab"], $oldReligion["kommentar"]);

                if($i != 0){
                    $religionIDString .= ",";
                }

                $religionIDString .= $newReligionID;
            }

            $newPerson->setReligionid($religionIDString);
        }
    }

    /*
        Documentation:
        http://forum.symfony-project.org/forum/23/topic/37872.html

        https://stackoverflow.com/questions/3325012/execute-raw-sql-using-doctrine-2

        http://doctrine-orm.readthedocs.org/projects/doctrine-orm/en/latest/reference/native-sql.html

        http://stackoverflow.com/questions/11823980/can-i-use-prepare-statement-in-doctrine-orm

        http://www.doctrine-project.org/api/dbal/2.3/class-Doctrine.DBAL.Statement.html
    */

    private function getReligionDataWithNativeQuery($oldPersonID, $oldDBManager){
        $sql = "SELECT ID, `order`, `von-ab`, konfession, konversion, belegt, kommentar FROM `religion` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();


        /*$resultMap = new ResultSetMapping;


        $resultMap->addEntityResult('OldBundle:Religion', '');
        $resultMap->addEntityResult('OldBundle:Religion', 'r');
        $resultMap->addFieldResult('r', 'ID', 'id');
        $resultMap->addFieldResult('r', 'order', 'order');
        $resultMap->addFieldResult('r', 'von-ab', 'vonAb');
        $resultMap->addFieldResult('r', 'konfession', 'konfession');
        $resultMap->addFieldResult('r', 'konversion', 'konversion');
        $resultMap->addFieldResult('r', 'belegt', 'belegt');
        $resultMap->addFieldResult('r', 'kommentar', 'kommentar');

        $query = $oldDBManager->createNativeQuery('SELECT ID, `order`, `von-ab`, konfession, konversion, belegt, kommentar FROM `religion` WHERE ID=:personID', $resultMap);
        $query->setParameter('personID', $oldPersonID);

        return $query->getArrayResult();*/
    }

    private function migrateOriginalNation($newPerson, $person){

        if(!is_null($person->getUrspNation())){
            $nationId = $this->get("migrate_data.service")->migrateNation($person->getUrspNation(), "");

            $newPerson->setOriginalNationid($nationId);
        }
    }

    private function migrateSource($newPerson, $oldPersonID, $oldDBManager){

        $sources = $this->getSourcesWithNativeQuery($oldPersonID, $oldDBManager);

        if(count($sources) > 0){
            $sourceIDString = "";

            for($i = 0; $i < count($sources); $i++){
                $quelle = $sources[$i];
                $sourceID = $this->get("migrate_data.service")->migrateSource($quelle["order"], $quelle["bezeichnung"], $quelle["fundstelle"], $quelle["bemerkung"],$quelle["kommentar"]);

                if($i != 0){
                    $sourceIDString .= ",";
                }

                $sourceIDString .= $sourceID;
            }

            $newPerson->setSourceid($sourceIDString);
        }
    }

    private function getSourcesWithNativeQuery($oldPersonID, $oldDBManager){
        $sql = "SELECT ID, `order`, bezeichnung, fundstelle, bemerkung, kommentar FROM `quelle` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function migrateWorks($newPerson, $oldPersonID, $oldDBManager){

        $works = $this->getWorksWithNativeQuery($oldPersonID, $oldDBManager);

        if(count($works) > 0){
            $worksIDString = "";

            for($i = 0; $i < count($works); $i++){
                $work = $works[$i];
                $workID = $this->get("migrate_data.service")->migrateWork($work['werke'],$work['order'],$work['land'],$work['ort'],$work['von-ab'],$work['bis'],$work['territorium'],$work['belegt'],$work['kommentar']);

                if($i != 0){
                    $worksIDString .= ",";
                }

                $worksIDString .= $workID;
            }

            $newPerson->setWorksid($worksIDString);
        }
    }

    private function getWorksWithNativeQuery($oldPersonID, $oldDBManager){
        $sql = "SELECT `ID`, `order`, `land`, `werke`, `ort`, `von-ab`, `bis`, `belegt`, `kommentar`, `territorium` FROM `werke` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function migrateDataFromIndexID($newPerson, $oldPersonID, $oldDBManager){

        $idData = $oldDBManager->getRepository('OldBundle:Ids')->findOneById($oldPersonID);

        $newPerson->setComplete($idData->getVollständig());
        $newPerson->setControl($idData->getKontrolle());
    }

    private function migrateHonour($newPerson, $oldPersonID, $oldDBManager){

        $honours = $this->getHonourWithNativeQuery($oldPersonID, $oldDBManager);

        if(count($honours) > 0){
            $honoursIDString = "";

            for($i = 0; $i < count($honours); $i++){
                $honour = $honours[$i];
                $honourID = $this->get("migrate_data.service")->migrateHonour($honour["order"],$honour["ehren"],$honour["land"],$honour["territorium"],$honour["ort"],$honour["von-ab"],$honour["bis"],$honour["belegt"],$honour["kommentar"]);

                if($i != 0){
                    $honoursIDString .= ",";
                }

                $honoursIDString .= $honourID;
            }

            $newPerson->setHonourid($honoursIDString);
        }
    }

    private function getHonourWithNativeQuery($oldPersonID, $oldDBManager){
        $sql = "SELECT ID, `order`, ort, territorium, land, ehren, `von-ab`, bis, belegt, kommentar FROM `ehren` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function migrateProperty($newPerson, $oldPersonID, $oldDBManager){

        $properties = $this->getPropertyWithNativeQuery($oldPersonID, $oldDBManager);

        if(count($properties) > 0){
            $propertiesIDString = "";

            for($i = 0; $i < count($properties); $i++){
                $property = $properties[$i];
                $propertyID = $this->get("migrate_data.service")->migrateProperty($property["order"],$property["besitz"],$property["land"],$property["territorium"],$property["ort"],$property["von-ab"],$property["bis"],$property["belegt"],$property["kommentar"]);

                if($i != 0){
                    $propertiesIDString .= ",";
                }

                $propertiesIDString .= $propertyID;
            }

            $newPerson->setPropertyid($propertiesIDString);
        }
    }

    private function getPropertyWithNativeQuery($oldPersonID, $oldDBManager){
        $sql = "SELECT ID, `order`, land, ort, territorium, besitz, `von-ab`, bis, belegt, kommentar FROM `besitz` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function migrateRank($newPerson, $oldPersonID, $oldDBManager){

        $ranks = $this->getRankWithNativeQuery($oldPersonID, $oldDBManager);

        if(count($ranks) > 0){
            $ranksIDString = "";

            for($i = 0; $i < count($ranks); $i++){
                $rank = $ranks[$i];
                $rankID = $this->get("migrate_data.service")->migrateRank($rank["order"],$rank["rang"],$rank["rangklasse"],$rank["land"],$rank["territorium"],$rank["ort"],$rank["von-ab"],$rank["bis"],$rank["belegt"],$rank["kommentar"]);

                if($i != 0){
                    $ranksIDString .= ",";
                }

                $ranksIDString .= $rankID;
            }

            $newPerson->setRankid($ranksIDString);
        }
    }

    private function getRankWithNativeQuery($oldPersonID, $oldDBManager){
        $sql = "SELECT ID, `order`, ort, territorium, land, `von-ab`, bis, rang, rangklasse, belegt, kommentar FROM `rang` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }


    private function migrateEducation($newPerson, $oldPersonID, $oldDBManager){

        $educations = $this->getEducationWithNativeQuery($oldPersonID, $oldDBManager);

        if(count($educations) > 0){
            $educationIDString = "";

            for($i = 0; $i < count($educations); $i++){
                $education = $educations[$i];
                $educationID = $this->get("migrate_data.service")->migrateEducation($education["order"],$education["ausbildung"],$education["land"],$education["territorium"],$education["ort"],$education["von-ab"],$education["bis"],$education["belegt"],$education["bildungsabschluss"],$education["bildungsabschlussdatum"],$education["bildungsabschlussort"],$education["kommentar"]);

                if($i != 0){
                    $educationIDString .= ",";
                }

                $educationIDString .= $educationID;
            }

            $newPerson->setEducationid($educationIDString);
        }
    }

    private function getEducationWithNativeQuery($oldPersonID, $oldDBManager){
        $sql = "SELECT ID, `order`, ort, land, territorium, ausbildung, bildungsabschluss, bildungsabschlussdatum, bildungsabschlussort, `von-ab`, bis, belegt, kommentar FROM `ausbildung` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function migrateStatus($newPerson, $oldPersonID, $oldDBManager){

        $stati = $this->getStatusWithNativeQuery($oldPersonID, $oldDBManager);

        if(count($stati) > 0){
            $statiIDString = "";

            for($i = 0; $i < count($stati); $i++){
                $status = $stati[$i];
                $statusID = $this->get("migrate_data.service")->migrateStatus($status["order"],$status["stand"],$status["land"],$status["territorium"],$status["ort"],$status["von-ab"],$status["bis"],$status["belegt"],$status["kommentar"]);

                if($i != 0){
                    $statiIDString .= ",";
                }

                $statiIDString .= $statusID;
            }

            $newPerson->setStatusid($statiIDString);
        }
    }

    private function getStatusWithNativeQuery($oldPersonID, $oldDBManager){
        $sql = "SELECT ID, `order`, ort, territorium, land, `von-ab`, bis, stand, belegt, kommentar FROM `stand`  WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function migrateRoadOfLife($newPerson, $oldPersonID, $oldDBManager){

        $roadOfLive = $this->getRoadOfLifeWithNativeQuery($oldPersonID, $oldDBManager);

        if(count($roadOfLive) > 0){
            $roadOfLiveIDString = "";

            for($i = 0; $i < count($roadOfLive); $i++){
                $step = $roadOfLive[$i];
                $stepID = $this->get("migrate_data.service")->migrateRoadOfLife($step["order"],$step["stammland"],$step["stammterritorium"],$step["beruf"],$step["land"], $step["territorium"],$step["ort"],$step["von-ab"],$step["bis"],$step["belegt"],$step["kommentar"]);

                if($i != 0){
                    $roadOfLiveIDString .= ",";
                }

                $roadOfLiveIDString .= $stepID;
            }

            $newPerson->setRoadOfLiveid($roadOfLiveIDString);
        }
    }

    private function getRoadOfLifeWithNativeQuery($oldPersonID, $oldDBManager){
        $sql = "SELECT ID, `order`, ort, territorium, land, stammterritorium, stammland, `von-ab`, bis, beruf, belegt, kommentar FROM `lebensweg` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }


    private function migrateGrandmothers($newPerson, $oldPersonID, $oldDBManager){

        //non paternal
        $grandmothers = $oldDBManager->getRepository('OldBundle:GroßmutterMuetterlicherseits')->findById($oldPersonID);

        for($i = 0; $i < count($grandmothers); $i++){
            $oldGrandmother = $grandmothers[$i];

            //$firstName, $patronym, $lastName, $gender, $nation, $comment
            $grandmother = $this->get("migrate_data.service")->migrateRelative($oldGrandmother->getVornamen(), null, $oldGrandmother->getName(), "weiblich", $oldGrandmother->getNation());

            $this->get("migrate_data.service")->migrateIsGrandparent($newPerson, $grandmother, false);
        }

        //paternal
        $grandmothers = $oldDBManager->getRepository('OldBundle:GroßmutterVaeterlicherseits')->findById($oldPersonID);

        for($i = 0; $i < count($grandmothers); $i++){
            $oldGrandmother = $grandmothers[$i];

            //$firstName, $patronym, $lastName, $gender, $nation, $comment
            $grandmother = $this->get("migrate_data.service")->migrateRelative($oldGrandmother->getVornamen(), null, $oldGrandmother->getName(), "weiblich");

            //insert additional data
            if(!is_null($oldGrandmother->getGeburtsland())){
                $birthID = $this->get("migrate_data.service")->migrateBirth(null,null,null,$oldGrandmother->getGeburtsland());

                $grandmother->setBirthid($birthID);
            }

            if(!is_null($oldGrandmother->getBeruf())){
                $jobID = $this->get("migrate_data.service")->migrateJob($oldGrandmother->getBeruf());

                $grandmother->setJobid($jobID);
            }


            $this->get("migrate_data.service")->migrateIsGrandparent($newPerson, $grandmother, true);
        }
        
    }

    private function migrateGrandfathers($newPerson, $oldPersonID, $oldDBManager){

        //non paternal
        $grandfathers = $this->getGrandfatherMaternalWithNativeQuery($oldPersonID, $oldDBManager);

        for($i = 0; $i < count($grandfathers); $i++){
            $oldGrandfather = $grandfathers[$i];

            //check if reference to person
            if(!is_null($oldGrandfather["mütterl_großvater_id-nr"])){
                $grandfathersOID = $oldGrandfather["mütterl_großvater_id-nr"];

                $grandfatherMainID = $this->getIDForOID($grandfathersOID, $oldDBManager);

                $newGrandfather = $this->migratePerson($grandfatherMainID, $grandfathersOID);


                $this->get("migrate_data.service")->migrateIsGrandparent($newPerson, $newGrandfather, false, $oldGrandfather["kommentar"]);
            }else{
                $grandfather = $this->get("migrate_data.service")->migrateRelative($oldGrandfather["vornamen"], null, $oldGrandfather["name"], "männlich", $oldGrandfather["nation"], $oldGrandfather["kommentar"]);

                //insert additional data
                if(!is_null($oldGrandfather["beruf"])){
                    $jobID = $this->get("migrate_data.service")->migrateJob($oldGrandfather["beruf"]);

                    $grandfather->setJobid($jobID);
                }

                if(!is_null($oldGrandfather["wohnort"])){
                    $residenceId = $this->get("migrate_data.service")->migrateResidence(1,null,null,$oldGrandfather["wohnort"]);

                    $grandfather->setResidenceId($residenceId);
                }

                if(!is_null($oldGrandfather["gestorben"])){
                    $deathId = $this->get("migrate_data.service")->migrateDeath(null,$oldGrandfather["gestorben"]);

                    $grandfather->setDeathid($deathId);
                }

                $this->get("migrate_data.service")->migrateIsGrandparent($newPerson, $grandfather, false);
            }


        }

        //paternal
        $grandfathers = $this->getGrandfatherPaternalWithNativeQuery($oldPersonID, $oldDBManager);

        for($i = 0; $i < count($grandfathers); $i++){
            $oldGrandfather = $grandfathers[$i];

            //check if reference to person
            if(!is_null($oldGrandfather["vät_großvater_id-nr"])){
                $grandfathersOID = $oldGrandfather["vät_großvater_id-nr"];

                $grandfatherMainID = $this->getIDForOID($grandfathersOID, $oldDBManager);

                $newGrandfather = $this->migratePerson($grandfatherMainID, $grandfathersOID);

                $this->get("migrate_data.service")->migrateIsGrandparent($newPerson, $newGrandfather, true, $oldGrandfather["kommentar"]);
            }else{
                $grandfather = $this->get("migrate_data.service")->migrateRelative($oldGrandfather["vornamen"], null, $oldGrandfather["name"], "männlich", $oldGrandfather["nation"], $oldGrandfather["kommentar"]);

                //insert additional data
                if(!is_null($oldGrandfather["beruf"])){
                    $jobID = $this->get("migrate_data.service")->migrateJob($oldGrandfather["beruf"]);

                    $grandfather->setJobid($jobID);
                }

                if(!is_null($oldGrandfather["geburtsort"]) || 
                    !is_null($oldGrandfather["geburtsland"]) || 
                    !is_null($oldGrandfather["geburtsterritorium"]) || 
                    !is_null($oldGrandfather["geboren"])){
                    $birthID = $this->get("migrate_data.service")->migrateBirth(null,null,null,$oldGrandfather["geburtsland"], $oldGrandfather["geburtsort"],$oldGrandfather["geboren"],$oldGrandfather["geburtsterritorium"]);

                    $grandfather->setBirthid($birthID);
                }

                if(!is_null($oldGrandfather["wohnort"]) || 
                    !is_null($oldGrandfather["wohnterritorium"])){
                    $residenceId = $this->get("migrate_data.service")->migrateResidence(1,null,$oldGrandfather["wohnterritorium"],$oldGrandfather["wohnort"]);

                    $grandfather->setResidenceId($residenceId);
                }

                if(!is_null($oldGrandfather["gestorben"])){
                    $deathId = $this->get("migrate_data.service")->migrateDeath(null,$oldGrandfather["gestorben"]);

                    $grandfather->setDeathid($deathId);
                }

                if(!is_null($oldGrandfather["rang"])){
                    $rankId = $this->get("migrate_data.service")->migrateRank(1, $oldGrandfather["rang"]);
                    $grandfather->setRankid($rankId);
                }

                if(!is_null($oldGrandfather["stand"])){
                    $statusId = $this->get("migrate_data.service")->migrateStatus(1, $oldGrandfather["stand"]);
                    $grandfather->setStatusid($statusId);
                }

                $this->get("migrate_data.service")->migrateIsGrandparent($newPerson, $grandfather, true);
            }

        }
        
    }

    private function getGrandfatherMaternalWithNativeQuery($oldPersonID, $oldDBManager){
        $sql = "SELECT ID, `order`, order2, vornamen, name, gestorben, wohnort, nation, beruf, `mütterl_großvater_id-nr`, kommentar FROM `großvater_muetterlicherseits` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getGrandfatherPaternalWithNativeQuery($oldPersonID, $oldDBManager){
        $sql = "SELECT ID, `order`, order2, vornamen, name, geboren, geburtsort, geburtsland, geburtsterritorium, gestorben, wohnort, wohnterritorium, nation, beruf, rang, stand, `vät_großvater_id-nr`, kommentar FROM `großvater_vaeterlicherseits` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    public function migrateMother($newPerson, $oldPersonID, $oldDBManager){
       
        $mothers = $this->getMotherWithNativeQuery($oldPersonID, $oldDBManager);

        for($i = 0; $i < count($mothers); $i++){
            $oldMother = $mothers[$i];

            //check if reference to person
            if(!is_null($oldMother["mutter_id-nr"])){

                //problem since some mutter_id-nrs are referencing sons others are referencing entries for the mother in the person table
                if($this->checkIfMotherReferenceContainsChildPerson($oldPersonID, $oldMother["mutter_id-nr"], $oldDBManager)){
                    $this->getLogger()->info("Child reference found...");
                    // child reference found what to do now?

                    //for now just insert the data... perhaps in future create one relative and reference to it from all childs?
                    $this->createMother($newPerson, $oldMother);
                }else{
                    $this->getLogger()->info("Mother reference found...");
                    //reference to person entry for mother
                    $mothersOID = $oldMother["mutter_id-nr"];

                    $mothersMainID = $this->getIDForOID($mothersOID, $oldDBManager);

                    $newMother = $this->migratePerson($mothersMainID, $mothersOID);

                    $this->get("migrate_data.service")->migrateIsParent($newPerson, $newMother, $oldMother["kommentar"]);
                }

            }else{
                $this->createMother($newPerson, $oldMother);
            }
        }

    }

    private function createMother($newPerson, $oldMother){
        //$firstName, $patronym, $lastName, $gender, $nation, $comment
                $mother = $this->get("migrate_data.service")->migrateRelative($oldMother["vornamen"], $oldMother["russ_vornamen"], $oldMother["name"], "weiblich", $oldMother["nation"], $oldMother["kommentar"]);

                $mother->setForeName($oldMother["rufnamen"]);

                //additional data

                //birth
                if(!is_null($oldMother["herkunftsort"]) || 
                    !is_null($oldMother["herkunftsland"]) || 
                    !is_null($oldMother["herkunftsterritorium"]) || 
                    !is_null($oldMother["geburtsort"]) || 
                    !is_null($oldMother["geboren"])){
                    //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
                    $birthID = $this->get("migrate_data.service")->migrateBirth($oldMother["herkunftsland"],$oldMother["herkunftsterritorium"],$oldMother["herkunftsort"],null, $oldMother["geburtsort"],$oldMother["geboren"]);

                    $mother->setBirthid($birthID);
                }

                //baptism
                if(!is_null($oldMother["getauft"])){
                    $baptismId = $this->get("migrate_data.service")->migrateBaptism($oldMother["getauft"]);

                    $mother->setBaptismid($baptismId);
                }

                //death
                if(!is_null($oldMother["gestorben"]) || 
                    !is_null($oldMother["todesort"]) || 
                    !is_null($oldMother["todesterritorium"]) || 
                    !is_null($oldMother["begraben"]) || 
                    !is_null($oldMother["friedhof"])){
                    //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
                    $deathId = $this->get("migrate_data.service")->migrateDeath($oldMother["todesort"],$oldMother["gestorben"], null, null, $oldMother["todesterritorium"], $oldMother["friedhof"], null, $oldMother["begraben"]);

                    $mother->setDeathid($deathId);
                }

                //residence
                if(!is_null($oldMother["wohnort"])){
                    $residenceId = $this->get("migrate_data.service")->migrateResidence(1,null,null,$oldMother["wohnort"]);

                    $mother->setResidenceId($residenceId);
                }

                //religion
                if(!is_null($oldMother["konfession"])){
                    $religionId = $this->get("migrate_data.service")->migrateReligion($oldMother["konfession"], 1);

                    $mother->setReligionid($religionId);
                }
               
                //status
                if(!is_null($oldMother["stand"])){
                    $statusId = $this->get("migrate_data.service")->migrateStatus(1, $oldMother["stand"]);
                    $mother->setStatusid($statusId);
                }

                //rank
                if(!is_null($oldMother["rang"])){
                    $rankId = $this->get("migrate_data.service")->migrateRank(1, $oldMother["rang"]);
                    $mother->setRankid($rankId);
                }


                //property
                if(!is_null($oldMother["besitz"])){
                    $propertyId = $this->get("migrate_data.service")->migrateProperty(1, $oldMother["besitz"]);

                    $mother->setPropertyid($propertyId);
                }


                //job
                if(!is_null($oldMother["beruf"])){
                    $jobID = $this->get("migrate_data.service")->migrateJob($oldMother["beruf"]);

                    $mother->setJobid($jobID);
                }

                //born_in_marriage
                if(!is_null($oldMother["ehelich"])){
                    $mother->setBornInMarriage($oldMother["ehelich"]);
                }

                $this->get("migrate_data.service")->migrateIsParent($newPerson, $mother);
    }

    private function getMotherWithNativeQuery($oldPersonID, $oldDBManager){
        $sql = "SELECT ID, `order`, vornamen, russ_vornamen, name, rufnamen, geboren, geburtsort, getauft, gestorben, todesort, todesterritorium, begraben, friedhof, herkunftsort, herkunftsland, herkunftsterritorium, wohnort, nation, konfession, ehelich, stand, rang, besitz, beruf, `mutter_id-nr`, kommentar FROM `mutter` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function checkIfMotherReferenceContainsChildPerson($childID, $motherReferenceOid, $oldDBManager){
        
        /*
            Für problematische Mutter-Kind Einträge in der mutter_id-nr:
            Schritt 1: ID für OID in mutter_id-nr auslesen.
            Schritt 2: Prüfen, ob ein weiterer Eintrag in Mutter für diese ID vorhanden ist.
            Schritt 3: Prüfen, ob die OID dieses Eintrags auf die ID des ersten Eintrags verweist.
            Wenn ja ==> Sonderfall gefunden
            (Wenn nein, eventuell das Ganze so weit weiter machen, bis mutter_id-nr leer ist/kein weiterer Verweis vorhanden ist?)
            (Dies könnte nötig sein, um einen Kreisverweis von mehr als 2 Kindern abzufangen)
        */

        $this->getLogger()->info("Checking against ID ".$childID." for OID ".$motherReferenceOid);

        $referenceMotherID = $this->getIDForOID($motherReferenceOid, $oldDBManager);

        $mother = $this->getMotherWithNativeQuery($referenceMotherID, $oldDBManager);

        if(count($mother) > 0){
            $this->getLogger()->debug("There is a mother for : ".$referenceMotherID);
            if(!is_null($mother[0]["mutter_id-nr"]) && $mother[0]["mutter_id-nr"] != ""){
                $this->getLogger()->info("New mother Oid found: ".$mother[0]["mutter_id-nr"]);
                $nextReferenceOid = $mother[0]["mutter_id-nr"];

                $nextReferenceID = $this->getIDForOID($nextReferenceOid, $oldDBManager);

                if($childID == $nextReferenceID){
                    return true;
                }

                //check next reference?
                return $this->checkIfMotherReferenceContainsChildPerson($childID, $nextReferenceOid, $oldDBManager);
            }
        }

        return false;
    }

    private function migrateFather($newPerson, $oldPersonID, $oldDBManager){
        //non paternal
        $fathers = $this->getFatherWithNativeQuery($oldPersonID, $oldDBManager);

        for($i = 0; $i < count($fathers); $i++){
            $oldFather = $fathers[$i];

            //check if reference to person
            if(!is_null($oldFather["vater_id-nr"])){
                //check it?
                $fathersOID = $oldFather["vater_id-nr"];

                $fathersMainID = $this->getIDForOID($fathersOID, $oldDBManager);

                $newFather = $this->migratePerson($fathersMainID, $fathersOID);

                $this->get("migrate_data.service")->migrateIsParent($newPerson, $newFather, $oldFather["kommentar"]);
            }else{
                //$firstName, $patronym, $lastName, $gender, $nation, $comment
                $father = $this->get("migrate_data.service")->migrateRelative($oldFather["vornamen"], $oldFather["russ_vornamen"], $oldFather["name"], "männlich", $oldFather["nation"], $oldFather["kommentar"]);

                $father->setForeName($oldFather["rufnamen"]);

                //additional data

                //birth
                if(!is_null($oldFather["herkunftsort"]) || 
                    !is_null($oldFather["herkunftsland"]) || 
                    !is_null($oldFather["herkunftsterritorium"]) || 
                    !is_null($oldFather["geburtsort"]) || 
                    !is_null($oldFather["geburtsterritorium"]) || 
                    !is_null($oldFather["geburtsland"]) || 
                    !is_null($oldFather["geboren"])){
                    //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
                    $birthID = $this->get("migrate_data.service")->migrateBirth($oldFather["herkunftsland"],$oldFather["herkunftsterritorium"],$oldFather["herkunftsort"],$oldFather["geburtsland"], $oldFather["geburtsort"],$oldFather["geboren"], $oldFather["geburtsterritorium"]);

                    $father->setBirthid($birthID);
                }

                //baptism
                if(!is_null($oldFather["getauft"]) ||
                    !is_null($oldFather["taufort"])){
                    $baptismId = $this->get("migrate_data.service")->migrateBaptism($oldFather["getauft"], $oldFather["taufort"]);

                    $father->setBaptismid($baptismId);
                }

                //death
                if(!is_null($oldFather["gestorben"]) || 
                    !is_null($oldFather["todesort"]) || 
                    !is_null($oldFather["todesterritorium"]) || 
                    !is_null($oldFather["begraben"]) || 
                    !is_null($oldFather["begräbnisort"])){
                    //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
                    $deathId = $this->get("migrate_data.service")->migrateDeath($oldFather["todesort"],$oldFather["gestorben"], null, null, $oldFather["todesterritorium"], null, $oldFather["begräbnisort"], $oldFather["begraben"]);

                    $father->setDeathid($deathId);
                }

                //residence
                if(!is_null($oldFather["wohnort"]) ||
                    !is_null($oldFather["wohnterritorium"]) ||
                    !is_null($oldFather["wohnland"])){
                    $residenceId = $this->get("migrate_data.service")->migrateResidence(1,$oldFather["wohnland"],$oldFather["wohnterritorium"],$oldFather["wohnort"]);

                    $father->setResidenceId($residenceId);
                }

                //religion
                if(!is_null($oldFather["konfession"])){
                    $religionId = $this->get("migrate_data.service")->migrateReligion($oldFather["konfession"], 1);

                    $father->setReligionid($religionId);
                }
               
                //status
                if(!is_null($oldFather["stand"])){
                    $statusId = $this->get("migrate_data.service")->migrateStatus(1, $oldFather["stand"]);
                    $father->setStatusid($statusId);
                }

                //rank
                if(!is_null($oldFather["rang"])){
                    $rankId = $this->get("migrate_data.service")->migrateRank(1, $oldFather["rang"]);
                    $father->setRankid($rankId);
                }


                //property
                if(!is_null($oldFather["besitz"])){
                    $propertyId = $this->get("migrate_data.service")->migrateProperty(1, $oldFather["besitz"]);

                    $father->setPropertyid($propertyId);
                }


                //job
                if(!is_null($oldFather["beruf"])){
                    $jobID = $this->get("migrate_data.service")->migrateJob($oldFather["beruf"]);

                    $father->setJobid($jobID);
                }

                //education
                if(!is_null($oldFather["ausbildung"]) ||
                    !is_null($oldFather["bildungsabschluss"])){
                    //$educationOrder, $label, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $graduationLabel=null, $graduationDate=null, $graduationLocation=null, $comment=null
                    $educationID = $this->get("migrate_data.service")->migrateEducation(1, $oldFather["ausbildung"], null, null, null, null, null, null, $oldFather["bildungsabschluss"]);

                    $father->setEducationid($educationID);
                }

                //honour
                if(!is_null($oldFather["ehren"])){
                    $honourID = $this->get("migrate_data.service")->migrateHonour(1, $oldFather["ehren"]);

                    $father->setHonourid($honourID);
                }

                //born_in_marriage
                if(!is_null($oldFather["ehelich"])){
                    $father->setBornInMarriage($oldFather["ehelich"]);
                }

                $this->get("migrate_data.service")->migrateIsParent($newPerson, $father);
            }
        }

    }
    private function getFatherWithNativeQuery($oldPersonID, $oldDBManager){
        $sql = "SELECT `ID`,`order`,`vater_id-nr`,`vornamen`,`name`,`russ_vornamen`,`rufnamen`,
        `geboren`,`geburtsterritorium`,`geburtsort`,`geburtsland`,`herkunftsort`,`herkunftsterritorium`,`herkunftsland`,
        `gestorben`,`todesort`,`todesterritorium`,`begraben`,`begräbnisort`,`getauft`,`taufort`,`wohnort`,`wohnterritorium`,`wohnland`,
        `nation`,`beruf`,`stand`,`bildungsabschluss`,`rang`,`besitz`,`ehren`,`ehelich`,`konfession`,`hochzeitstag`,`ausbildung`,`kommentar` 
        FROM `vater` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function migrateSibling($newPerson, $oldPersonID, $oldDBManager){
        //non paternal
        $siblings = $this->getSiblingWithNativeQuery($oldPersonID, $oldDBManager);

        for($i = 0; $i < count($siblings); $i++){
            $oldSibling = $siblings[$i];

            //check if reference to person
            if(!is_null($oldSibling["geschwister_id-nr"])){
                //check it?
                $siblingsOID = $oldSibling["geschwister_id-nr"];

                $siblingsMainId = $this->getIDForOID($siblingsOID, $oldDBManager);

                $newSibling = $this->migratePerson($siblingsMainId, $siblingsOID);

                $this->get("migrate_data.service")->migrateIsSibling($newPerson, $newSibling, $oldSibling["kommentar"]);
            }else{
                $this->createSibling($newPerson, $oldSibling, $oldPersonID, $oldDBManager);                
            }
        }

    }

    private function createSibling($newPerson, $oldSibling, $oldPersonID, $oldDBManager){
        //$firstName, $patronym, $lastName, $gender, $nation, $comment
        $sibling = $this->get("migrate_data.service")->migrateRelative($oldSibling["vornamen"], $oldSibling["russ_vornamen"], $oldSibling["name"], $oldSibling["geschlecht"], null, $oldSibling["kommentar"]);

        $sibling->setForeName($oldSibling["rufnamen"]);

        //additional data
        $siblingEducation = $this->getSiblingsEducationWithNativeQuery($oldPersonID, $oldSibling["order"], $oldDBManager);

        $siblingHonour = $this->getSiblingsHonourWithNativeQuery($oldPersonID, $oldSibling["order"], $oldDBManager);

        $siblingOrigin = $this->getSiblingsOriginWithNativeQuery($oldPersonID, $oldSibling["order"], $oldDBManager);

        $siblingRoadOfLive = $this->getSiblingsRoadOfLiveWithNativeQuery($oldPersonID, $oldSibling["order"], $oldDBManager);

        $siblingRank = $this->getSiblingsRankWithNativeQuery($oldPersonID, $oldSibling["order"], $oldDBManager);

        $siblingStatus = $this->getSiblingsStatusWithNativeQuery($oldPersonID, $oldSibling["order"], $oldDBManager);

        $siblingDeath = $this->getSiblingsDeathWithNativeQuery($oldPersonID, $oldSibling["order"], $oldDBManager);




        if(count($siblingEducation) > 0){
            $educationIDString = "";
            //education
            for($i = 0; $i < count($siblingEducation); $i++){
                $education = $siblingEducation[$i];
                $educationID = $this->get("migrate_data.service")->migrateEducation($education["order2"],$education["ausbildung"],$education["land"],null,$education["ort"],$education["von-ab"],$education["bis"],$education["belegt"],$education["bildungsabschluss"]);

                if($i != 0){
                    $educationIDString .= ",";
                }

                $educationIDString .= $educationID;
            }

            $sibling->setEducationid($educationIDString);
        }


        if(count($siblingHonour) > 0){
            $honoursIDString = "";
            //honour
            for($i = 0; $i < count($siblingHonour); $i++){
                $honour = $siblingHonour[$i];
                $honourID = $this->get("migrate_data.service")->migrateHonour($honour["order2"],$honour["ehren"],$honour["land"]);

                if($i != 0){
                    $honoursIDString .= ",";
                }

                $honoursIDString .= $honourID;
            }

            $sibling->setHonourid($honoursIDString);
        }


        if(count($siblingOrigin) > 0){
            $birthIdString = "";
            $baptismIdString = "";

            //origin
            for($i = 0; $i < count($siblingOrigin); $i++){
                if($siblingOrigin[$i]['geboren'] != null
                    || $siblingOrigin[$i]['geburtsort'] != null
                    || $siblingOrigin[$i]['geburtsland'] != null
                    || $siblingOrigin[$i]['kommentar'] != null){
                    //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
                    $birthID = $this->get("migrate_data.service")->migrateBirth(null,null,null,$siblingOrigin[$i]["geburtsland"], $siblingOrigin[$i]["geburtsort"],$siblingOrigin[$i]["geboren"], null, $siblingOrigin[$i]['kommentar']);

                    if($birthIdString != ""){
                        $birthIdString .= "," . $birthID;
                    }else{
                        $birthIdString = $birthID;
                    }
                }


                if($siblingOrigin[$i]['getauft'] != null
                    || $siblingOrigin[$i]['taufort'] != null){
                    $baptismID = $this->get("migrate_data.service")->migrateBaptism($siblingOrigin[$i]["getauft"], $siblingOrigin[$i]["taufort"]);

                    if($baptismIdString != ""){
                        $baptismIdString .= "," . $baptismID;
                    }else{
                        $baptismIdString = $baptismID;
                    }
                }

            }

            if($birthIdString != ""){
                $sibling->setBirthid($birthIdString);
            }

            if($baptismIdString != ""){
                $sibling->setBaptismid($baptismIdString);
            }
        }


        if(count($siblingRoadOfLive) > 0){
            $roadOfLiveIDString = "";
            //roadOfLife
            for($i = 0; $i < count($siblingRoadOfLive); $i++){
                $step = $siblingRoadOfLive[$i];
                $stepID = $this->get("migrate_data.service")->migrateRoadOfLife($step["order2"],$step["stammland"],null,$step["beruf"],null, $step["territorium"],$step["ort"],$step["von-ab"],$step["bis"],$step["belegt"],$step["kommentar"]);

                if($i != 0){
                    $roadOfLiveIDString .= ",";
                }

                $roadOfLiveIDString .= $stepID;
            }

            $sibling->setRoadOfLiveid($roadOfLiveIDString);
        }


        if(count($siblingRank) > 0){
            $rankIDString = "";
            //rank
            for($i = 0; $i < count($siblingRank); $i++){
                $rank = $siblingRank[$i];
                $rankID = $this->get("migrate_data.service")->migrateRank($rank["order2"],$rank["rang"],null,$rank["land"],null,null,null,null,null,$rank["kommentar"]);

                if($i != 0){
                    $rankIDString .= ",";
                }

                $rankIDString .= $rankID;
            }

            $sibling->setRankid($rankIDString);
        }


        if(count($siblingStatus) > 0){
            $statiIDString = "";
            //status
            for($i = 0; $i < count($siblingStatus); $i++){
                $status = $siblingStatus[$i];
                $statusID = $this->get("migrate_data.service")->migrateStatus($status["order2"],$status["stand"],$status["land"],null,null,$status["von-ab"]);

                if($i != 0){
                    $statiIDString .= ",";
                }

                $statiIDString .= $statusID;
            }

            $sibling->setStatusid($statiIDString);
        }


        if(count($siblingDeath) > 0){
            $deathIDString = "";
            //death
            for($i = 0; $i < count($siblingDeath); $i++){
                //death
                if(!is_null($siblingDeath[$i]["begräbnisort"]) || 
                    !is_null($siblingDeath[$i]["gestorben"]) || 
                    !is_null($siblingDeath[$i]["todesort"]) || 
                    !is_null($siblingDeath[$i]["friedhof"]) ||
                    !is_null($siblingDeath[$i]["todesursache"]) ||
                    !is_null($siblingDeath[$i]["kommentar"])){
                    //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
                    $deathId = $this->get("migrate_data.service")->migrateDeath($siblingDeath[$i]["todesort"],$siblingDeath[$i]["gestorben"], null, $siblingDeath[$i]["todesursache"], null, $siblingDeath[$i]["friedhof"], $siblingDeath[$i]["begräbnisort"], null,$siblingDeath[$i]["kommentar"]);

                    if($deathIDString != ""){
                        $deathIDString .= "," . $deathId;
                    }else{
                        $deathIDString = $deathId;
                    }
                }
            }

            $sibling->setDeathid($deathIDString);
        }


        $this->get("migrate_data.service")->migrateIsSibling($newPerson, $sibling);
    }

    private function getSiblingWithNativeQuery($oldPersonID, $oldDBManager){
        $sql = "SELECT ID, `order`, vornamen, russ_vornamen, name, rufnamen, geschlecht, `geschwister_id-nr`, kommentar 
                    FROM `geschwister` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }


    private function getSiblingsEducationWithNativeQuery($oldPersonID, $siblingNr, $oldDBManager){
        $sql = "SELECT ID, `order`, order2, land, ort, `von-ab`, bis, ausbildung, bildungsabschluss, belegt 
        FROM `ausbildung_des_geschwisters` WHERE ID=:personID AND `order`=:siblingNr";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('siblingNr', $siblingNr);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getSiblingsHonourWithNativeQuery($oldPersonID, $siblingNr, $oldDBManager){
        $sql = "SELECT ID, `order`, order2, land, ehren
                FROM `ehren_des_geschwisters` WHERE ID=:personID AND `order`=:siblingNr";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('siblingNr', $siblingNr);
        $stmt->execute();


        return $stmt->fetchAll();
    }


    private function getSiblingsOriginWithNativeQuery($oldPersonID, $siblingNr, $oldDBManager){
        $sql = "SELECT ID, `order`, order2, geboren, geburtsort, geburtsland, getauft, taufort, kommentar
                FROM `herkunft_des_geschwisters` WHERE ID=:personID AND `order`=:siblingNr";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('siblingNr', $siblingNr);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getSiblingsRoadOfLiveWithNativeQuery($oldPersonID, $siblingNr, $oldDBManager){
        $sql = "SELECT ID, `order`, order2, ort, territorium, stammland, beruf, `von-ab`, bis, belegt, kommentar
                FROM `lebensweg_des_geschwisters` WHERE ID=:personID AND `order`=:siblingNr";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('siblingNr', $siblingNr);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getSiblingsRankWithNativeQuery($oldPersonID, $siblingNr, $oldDBManager){
        $sql = "SELECT ID, `order`, order2, land, rang, kommentar
                FROM `rang_des_geschwisters` WHERE ID=:personID AND `order`=:siblingNr";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('siblingNr', $siblingNr);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getSiblingsStatusWithNativeQuery($oldPersonID, $siblingNr, $oldDBManager){
        $sql = "SELECT ID, `order`, order2, land, stand, `von-ab`
                FROM `stand_des_geschwisters` WHERE ID=:personID AND `order`=:siblingNr";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('siblingNr', $siblingNr);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getSiblingsDeathWithNativeQuery($oldPersonID, $siblingNr, $oldDBManager){
        $sql = "SELECT `ID`,`order`,`order2`,`begräbnisort`,`gestorben`,`todesort`,`friedhof`,`kommentar`,`todesursache` 
                FROM `tod_des_geschwisters` WHERE ID=:personID AND `order`=:siblingNr";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('siblingNr', $siblingNr);
        $stmt->execute();


        return $stmt->fetchAll();
    }


    private function migrateMarriagePartner($newPerson, $oldPersonID, $oldDBManager){
        $this->getLogger()->info("Migrating Marriage Partners!");
        //non paternal
        $marriagePartners = $this->getMarriagePartnerWithNativeQuery($oldPersonID, $oldDBManager);

        $this->getLogger()->info("Found " . count($marriagePartners) . " partners.");

        for($i = 0; $i < count($marriagePartners); $i++){
            $oldMarriagePartner = $marriagePartners[$i];

            //check if reference to person
            if(!is_null($oldMarriagePartner["ehepartner_id-nr"])){
                $this->getLogger()->info("Reference to person found!");
                //check it?
                $marriagePartnersOID = $oldMarriagePartner["ehepartner_id-nr"];

                $marriagePartnersMainID = $this->getIDForOID($marriagePartnersOID, $oldDBManager);

                $newMarriagePartner = $this->migratePerson($marriagePartnersMainID, $marriagePartnersOID);

                //ehe
                $this->migrateWedding($newPerson, $newMarriagePartner, $oldMarriagePartner);
            }else{
                $this->createMarriagePartner($newPerson, $oldMarriagePartner, $oldPersonID, $oldDBManager);                
            }
        }
    }

 private function createMarriagePartner($newPerson, $oldMarriagePartner, $oldPersonID, $oldDBManager){

        $gender = $this->getOppositeGender($newPerson);

        //$firstName, $patronym, $lastName, $gender, $nation, $comment
        $marriagePartner = $this->get("migrate_data.service")->migratePartner($oldMarriagePartner["vornamen"], $oldMarriagePartner["russ_vornamen"], $oldMarriagePartner["name"], $gender, $oldMarriagePartner["nation"], $oldMarriagePartner["kommentar"]);

        $marriagePartner->setForeName($oldMarriagePartner["rufnamen"]);

        //additional data
        //birth
        if(!is_null($oldMarriagePartner["herkunftsort"]) || 
            !is_null($oldMarriagePartner["herkunftsland"]) || 
            !is_null($oldMarriagePartner["herkunftsterritorium"]) || 
            !is_null($oldMarriagePartner["geburtsort"]) || 
            !is_null($oldMarriagePartner["geburtsterritorium"]) || 
            !is_null($oldMarriagePartner["geburtsland"]) || 
            !is_null($oldMarriagePartner["geboren"])){
            //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
            $birthID = $this->get("migrate_data.service")->migrateBirth($oldMarriagePartner["herkunftsland"],$oldMarriagePartner["herkunftsterritorium"],$oldMarriagePartner["herkunftsort"],$oldMarriagePartner["geburtsland"], $oldMarriagePartner["geburtsort"],$oldMarriagePartner["geboren"], $oldMarriagePartner["geburtsterritorium"]);

            $marriagePartner->setBirthid($birthID);
        }

        //baptism
        if(!is_null($oldMarriagePartner["getauft"]) ||
            !is_null($oldMarriagePartner["taufort"])){
            $baptismId = $this->get("migrate_data.service")->migrateBaptism($oldMarriagePartner["getauft"], $oldMarriagePartner["taufort"]);

            $marriagePartner->setBaptismid($baptismId);
        }

        //death
        if(!is_null($oldMarriagePartner["gestorben"]) || 
            !is_null($oldMarriagePartner["todesort"]) || 
            !is_null($oldMarriagePartner["todesterritorium"]) || 
            !is_null($oldMarriagePartner["todesursache"]) ||
            !is_null($oldMarriagePartner["todesland"]) ||
            !is_null($oldMarriagePartner["begraben"]) ||
            !is_null($oldMarriagePartner["friedhof"]) ||
            !is_null($oldMarriagePartner["begräbnisort"])){
            //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
            $deathId = $this->get("migrate_data.service")->migrateDeath($oldMarriagePartner["todesort"],$oldMarriagePartner["gestorben"], $oldMarriagePartner["todesland"], $oldMarriagePartner["todesursache"], $oldMarriagePartner["todesterritorium"], $oldMarriagePartner["friedhof"], $oldMarriagePartner["begräbnisort"], $oldMarriagePartner["begraben"]);

            $marriagePartner->setDeathid($deathId);
        }


        //religion
        if(!is_null($oldMarriagePartner["konfession"])){
            $religionId = $this->get("migrate_data.service")->migrateReligion($oldMarriagePartner["konfession"], 1);

            $marriagePartner->setReligionid($religionId);
        }
       
        //status
        if(!is_null($oldMarriagePartner["stand"])){
            $statusId = $this->get("migrate_data.service")->migrateStatus(1, $oldMarriagePartner["stand"]);
            $marriagePartner->setStatusid($statusId);
        }

        //rank
        if(!is_null($oldMarriagePartner["rang"])){
            $rankId = $this->get("migrate_data.service")->migrateRank(1, $oldMarriagePartner["rang"]);
            $marriagePartner->setRankid($rankId);
        }


        //property
        if(!is_null($oldMarriagePartner["besitz"])){
            $propertyId = $this->get("migrate_data.service")->migrateProperty(1, $oldMarriagePartner["besitz"]);

            $marriagePartner->setPropertyid($propertyId);
        }


        //job
        if(!is_null($oldMarriagePartner["beruf"])){
            $jobID = $this->get("migrate_data.service")->migrateJob($oldMarriagePartner["beruf"]);

            $marriagePartner->setJobid($jobID);
        }

        //education
        if(!is_null($oldMarriagePartner["bildungsabschluss"])){
            //$educationOrder, $label, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $graduationLabel=null, $graduationDate=null, $graduationLocation=null, $comment=null
            $educationID = $this->get("migrate_data.service")->migrateEducation(1, null, null, null, null, null, null, null, $oldMarriagePartner["bildungsabschluss"]);

            $marriagePartner->setEducationid($educationID);
        }

        //honour
        if(!is_null($oldMarriagePartner["ehren"])){
            $honourID = $this->get("migrate_data.service")->migrateHonour(1, $oldMarriagePartner["ehren"]);

            $marriagePartner->setHonourid($honourID);
        }

        $this->migrateWedding($newPerson, $marriagePartner, $oldMarriagePartner);
    }

    private function getOppositeGender($newPerson){
        $this->getLogger()->debug("Finding Gender.");
        $genderOfPerson = $newPerson->getGender();

        $oppositeGender = $this->get("migrate_data.service")->getOppositeGender($genderOfPerson);

        $this->getLogger()->debug("Gender of Person: " . $genderOfPerson . " OppositeGender: " . $oppositeGender);


        return $oppositeGender;
    }


    private function migrateWedding($newPerson, $marriagePartner, $oldMarriagePartner){


        $existingWedding = $this->get("migrate_data.service")->checkIfWeddingAlreadyExists($oldMarriagePartner['order'], $newPerson, $marriagePartner);

        if($existingWedding == null){
            //$weddingOrder, $husband, $wife, $weddingDateid, $weddingLocationid, $weddingTerritoryid, $bannsDateid, $breakupReason, $breakupDateid, $marriageComment, $beforeAfter, $comment
            $this->get("migrate_data.service")->migrateWedding($oldMarriagePartner['order'], $newPerson, $marriagePartner, $oldMarriagePartner['hochzeitstag'], $oldMarriagePartner['hochzeitsort'], $oldMarriagePartner['hochzeitsterritorium'], $oldMarriagePartner['aufgebot'], $oldMarriagePartner['auflösung'], $oldMarriagePartner['gelöst'], $oldMarriagePartner['verheiratet'], $oldMarriagePartner['vorher-nachher'], null);
        }else{
            $this->getLogger()->info("Wedding already exists!");
        }

    }

    private function getMarriagePartnerWithNativeQuery($oldPersonID, $oldDBManager){
        $sql = "SELECT ID, vornamen, russ_vornamen, name, rufnamen, `order`, nation, herkunftsort, herkunftsland, 
                herkunftsterritorium, geboren, geburtsort, geburtsland, geburtsterritorium, 
                getauft, taufort, gestorben, todesort, friedhof, begraben, 
                begräbnisort, todesterritorium, todesland, todesursache, konfession, aufgebot, 
                verheiratet, `ehepartner_id-nr`, hochzeitstag, hochzeitsort, hochzeitsterritorium, 
                auflösung, gelöst, `partnerpartner_id-nr`, beruf, stand, rang, ehren, besitz, 
                bildungsabschluss, `vorher-nachher`, kommentar
                FROM `ehepartner` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }





    private function migrateChild($newPerson, $oldPersonID, $oldDBManager){
        //non paternal
        $children = $this->getChildWithNativeQuery($oldPersonID, $oldDBManager);

        for($i = 0; $i < count($children); $i++){
            $oldChild = $children[$i];

            //check if reference to person
            if(!is_null($oldChild["kind_id-nr"])){
                //check it?
                $childsOID = $oldChild["kind_id-nr"];

                $childsMainId = $this->getIDForOID($childsOID, $oldDBManager);

                $newChild = $this->migratePerson($childsMainId, $childsOID);

                $this->get("migrate_data.service")->migrateIsParent($newChild, $newPerson, $oldChild["kommentar"]);
            }else{
                $this->createChild($newPerson, $oldChild, $oldPersonID, $oldDBManager);                
            }
        }
    }

    private function createChild($newPerson, $oldChild, $oldPersonID, $oldDBManager){
        //$firstName, $patronym, $lastName, $gender, $nation, $comment
        $child = $this->get("migrate_data.service")->migrateRelative($oldChild["vornamen"], $oldChild["russ_vornamen"], $oldChild["name"], $oldChild["geschlecht"], null, $oldChild["kommentar"]);

        $child->setForeName($oldChild["rufnamen"]);

        //additional data
        //@ToDo...

        $this->get("migrate_data.service")->migrateIsParent($child, $newPerson);
    }

    private function getChildWithNativeQuery($oldPersonID, $oldDBManager){
        $sql = "SELECT ID, `order`, order2, vornamen, russ_vornamen, name, rufnamen, geboren, geburtsort, geschlecht, `kind_id-nr`, kommentar
                    FROM `kind` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }


}
