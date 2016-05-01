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
        
        //print_r("Normalisiert: ".$this->get("normalization.service")->writeOutAbbreviations("Du bist mein Mamaschaf. Mein bayer. Mamaschaf! Und du besitzt eine Batt.. Und hast einen adl. Adj.!")."\n");

        if(is_null($person)){
            return new Response("Invalid ID");
        }

        /*
        return new Response(
            'Migrated Database entry: '.$person->getId()
        );*/
        
        return $this->forward('NewBundle:Default:json', array(
            'type' => 'id',
            'ID'  => $person->getId()
        ));
    }

    private function getIDForOID($OID, $oldDBManager){

        $IDData = $oldDBManager->getRepository('OldBundle:Ids')->findOneByOid($OID);

        return $IDData->getId();
    }

    private function migratePerson($ID, $OID = null){
        $this->getLogger()->info("Migrating Person with ID: ". $ID);
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

        // migrate GrandChild after marriagepartners of child
        
        $this->get("migrate_data.service")->flush();

        return $newPerson;
    }

    private function migrateDeathController($newPerson, $oldPersonID, $oldDBManager){

        $tod = $oldDBManager->getRepository('OldBundle:Tod')->findOneById($oldPersonID);

        //if necessary get more informations from other tables
        if(!is_null($tod)){
            $this->get("migrate_data.service")->migrateDeath($newPerson,$tod->getTodesort(), $tod->getGestorben(), $tod->getTodesland(), $tod->getTodesursache(), $tod->getTodesterritorium(), $tod->getFriedhof(), $tod->getBegräbnisort(), $tod->getBegraben(), $tod->getKommentar());
        }
    }

    private function migrateBirthController($newPerson, $oldPersonID, $oldDBManager){

        $birth = $oldDBManager->getRepository('OldBundle:Herkunft')->findOneById($oldPersonID);

        //if necessary get more informations from other tables
        if(!is_null($birth)){
            if($birth->getHerkunftsland() != null ||
                $birth->getHerkunftsterritorium() != null ||
                $birth->getHerkunftsort() != null ||
                $birth->getGeburtsland() != null ||
                $birth->getGeburtsort() != null ||
                $birth->getGeboren() != null ||
                $birth->getGeburtsterritorium() != null ||
                $birth->getKommentar() != null){
                
                $this->get("migrate_data.service")->migrateBirth($newPerson,$birth->getHerkunftsland(),$birth->getHerkunftsterritorium(),$birth->getHerkunftsort(),$birth->getGeburtsland(),$birth->getGeburtsort(),$birth->getGeboren(),$birth->getGeburtsterritorium(),$birth->getKommentar());
            }
          }
    }


    private function migrateBaptismController($newPerson, $oldPersonID, $oldDBManager){

        $birth = $oldDBManager->getRepository('OldBundle:Herkunft')->findOneById($oldPersonID);

        //if necessary get more informations from other tables
        if(!is_null($birth) && (!is_null($birth->getGetauft()) || !is_null($birth->getTaufort()))){
            $this->get("migrate_data.service")->migrateBaptism($newPerson,$birth->getGetauft(),$birth->getTaufort());
        }
    }

    private function migrateReligionController($newPerson, $oldPersonID, $oldDBManager){
        //find by id because there can be multiple religion for one person
        //$religions = $oldDBManager->getRepository('OldBundle:Religion')->findById($oldPersonID);
        $religions = $this->getReligionDataWithNativeQuery($oldPersonID, $oldDBManager);

        if(count($religions) > 0){
            for($i = 0; $i < count($religions); $i++){
                $oldReligion = $religions[$i];
                $this->get("migrate_data.service")->migrateReligion($newPerson,$oldReligion["konfession"], $oldReligion["order"], $oldReligion["konversion"], $oldReligion["belegt"], $oldReligion["von-ab"], $oldReligion["kommentar"]);
            }
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
    }

    private function migrateOriginalNation($newPerson, $person){

        if(!is_null($person->getUrspNation())){
            $nationId = $this->get("migrate_data.service")->migrateNation($person->getUrspNation(), "");

            $newPerson->setOriginalNation($nationId);
        }
    }

    private function migrateSource($newPerson, $oldPersonID, $oldDBManager){

        $sources = $this->getSourcesWithNativeQuery($oldPersonID, $oldDBManager);

        if(count($sources) > 0){
            for($i = 0; $i < count($sources); $i++){
                $quelle = $sources[$i];
                $this->get("migrate_data.service")->migrateSource($newPerson,$quelle["order"], $quelle["bezeichnung"], $quelle["fundstelle"], $quelle["bemerkung"],$quelle["kommentar"]);
            }
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
            for($i = 0; $i < count($works); $i++){
                $work = $works[$i];
                $this->get("migrate_data.service")->migrateWork($newPerson,$work['werke'],$work['order'],$work['land'],$work['ort'],$work['von-ab'],$work['bis'],$work['territorium'],$work['belegt'],$work['kommentar']);
            }
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
            for($i = 0; $i < count($honours); $i++){
                $honour = $honours[$i];
                $this->get("migrate_data.service")->migrateHonour($newPerson,$honour["order"],$honour["ehren"],$honour["land"],$honour["territorium"],$honour["ort"],$honour["von-ab"],$honour["bis"],$honour["belegt"],$honour["kommentar"]);
            }
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
            for($i = 0; $i < count($properties); $i++){
                $property = $properties[$i];
                $propertyID = $this->get("migrate_data.service")->migrateProperty($newPerson,$property["order"],$property["besitz"],$property["land"],$property["territorium"],$property["ort"],$property["von-ab"],$property["bis"],$property["belegt"],$property["kommentar"]);
            }
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
            for($i = 0; $i < count($ranks); $i++){
                $rank = $ranks[$i];
                $this->get("migrate_data.service")->migrateRank($newPerson,$rank["order"],$rank["rang"],$rank["rangklasse"],$rank["land"],$rank["territorium"],$rank["ort"],$rank["von-ab"],$rank["bis"],$rank["belegt"],$rank["kommentar"]);
            }
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
            for($i = 0; $i < count($educations); $i++){
                $education = $educations[$i];
                $this->get("migrate_data.service")->migrateEducation($newPerson,$education["order"],$education["ausbildung"],$education["land"],$education["territorium"],$education["ort"],$education["von-ab"],$education["bis"],$education["belegt"],$education["bildungsabschluss"],$education["bildungsabschlussdatum"],$education["bildungsabschlussort"],$education["kommentar"]);
            }
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
            for($i = 0; $i < count($stati); $i++){
                $status = $stati[$i];
                $this->get("migrate_data.service")->migrateStatus($newPerson,$status["order"],$status["stand"],$status["land"],$status["territorium"],$status["ort"],$status["von-ab"],$status["bis"],$status["belegt"],$status["kommentar"]);
            }

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
            for($i = 0; $i < count($roadOfLive); $i++){
                $step = $roadOfLive[$i];
                $this->get("migrate_data.service")->migrateRoadOfLife($newPerson,$step["order"],$step["stammland"],$step["stammterritorium"],$step["beruf"],$step["land"], $step["territorium"],$step["ort"],$step["von-ab"],$step["bis"],$step["belegt"],$step["kommentar"]);
            }
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
                $this->get("migrate_data.service")->migrateBirth($grandmother,null,null,null,$oldGrandmother->getGeburtsland());
            }

            if(!is_null($oldGrandmother->getBeruf())){
                $jobID = $this->get("migrate_data.service")->migrateJob($oldGrandmother->getBeruf());

                $grandmother->setJob($jobID);
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

                    $grandfather->setJob($jobID);
                }

                if(!is_null($oldGrandfather["wohnort"])){
                    $this->get("migrate_data.service")->migrateResidence($grandfather,1,null,null,$oldGrandfather["wohnort"]);
                }

                if(!is_null($oldGrandfather["gestorben"])){
                    $this->get("migrate_data.service")->migrateDeath($grandfather,null,$oldGrandfather["gestorben"]);
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

                    $grandfather->setJob($jobID);
                }

                if(!is_null($oldGrandfather["geburtsort"]) || 
                    !is_null($oldGrandfather["geburtsland"]) || 
                    !is_null($oldGrandfather["geburtsterritorium"]) || 
                    !is_null($oldGrandfather["geboren"])){
                    $this->get("migrate_data.service")->migrateBirth($grandfather,null,null,null,$oldGrandfather["geburtsland"], $oldGrandfather["geburtsort"],$oldGrandfather["geboren"],$oldGrandfather["geburtsterritorium"]);
                }

                if(!is_null($oldGrandfather["wohnort"]) || 
                    !is_null($oldGrandfather["wohnterritorium"])){
                    $this->get("migrate_data.service")->migrateResidence($grandfather,1,null,$oldGrandfather["wohnterritorium"],$oldGrandfather["wohnort"]);
                }

                if(!is_null($oldGrandfather["gestorben"])){
                    $this->get("migrate_data.service")->migrateDeath($grandfather,null,$oldGrandfather["gestorben"]);
                }

                if(!is_null($oldGrandfather["rang"])){
                    $this->get("migrate_data.service")->migrateRank($grandfather,1, $oldGrandfather["rang"]);
                }

                if(!is_null($oldGrandfather["stand"])){
                    $this->get("migrate_data.service")->migrateStatus($grandfather,1, $oldGrandfather["stand"]);
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

            $newMother = null;
            //check if reference to person
            if(!is_null($oldMother["mutter_id-nr"])){


                //problem since some mutter_id-nrs are referencing sons others are referencing entries for the mother in the person table
                if($this->checkIfMotherReferenceContainsChildPerson($oldPersonID, $oldMother["mutter_id-nr"], $oldDBManager)){
                    $this->getLogger()->info("Child reference found...");
                    // child reference found what to do now?

                    //for now just insert the data... perhaps in future create one relative and reference to it from all childs?
                    $newMother = $this->createMother($newPerson, $oldMother);
                }else{
                    $this->getLogger()->info("Mother reference found...");
                    //reference to person entry for mother
                    $mothersOID = $oldMother["mutter_id-nr"];

                    $mothersMainID = $this->getIDForOID($mothersOID, $oldDBManager);

                    $newMother = $this->migratePerson($mothersMainID, $mothersOID);

                    $newMotherObj = $this->createMother($oldMother);
                    
                    $mergedMother = $this->get("person_merging.service")->mergePersons($newMother,$newMotherObj);
                    
                    $this->get("migrate_data.service")->migrateIsParent($newPerson, $mergedMother);
                    
                    $newMother = $mergedMother;
                }

            }else{
                $newMother =$this->createMother($oldMother);
                $this->get("migrate_data.service")->migrateIsParent($newPerson, $newMother);
            }

            //partners of mother
            $this->migratePartnersOfMother($newMother, $oldPersonID, $oldDBManager);
        }

    }

    private function createMother($oldMother){
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
            $this->get("migrate_data.service")->migrateBirth($mother,$oldMother["herkunftsland"],$oldMother["herkunftsterritorium"],$oldMother["herkunftsort"],null, $oldMother["geburtsort"],$oldMother["geboren"]);
        }

        //baptism
        if(!is_null($oldMother["getauft"])){
            $this->get("migrate_data.service")->migrateBaptism($mother,$oldMother["getauft"]);
        }

        //death
        if(!is_null($oldMother["gestorben"]) || 
            !is_null($oldMother["todesort"]) || 
            !is_null($oldMother["todesterritorium"]) || 
            !is_null($oldMother["begraben"]) || 
            !is_null($oldMother["friedhof"])){
            //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
            $this->get("migrate_data.service")->migrateDeath($mother,$oldMother["todesort"],$oldMother["gestorben"], null, null, $oldMother["todesterritorium"], $oldMother["friedhof"], null, $oldMother["begraben"]);
        }

        //residence
        if(!is_null($oldMother["wohnort"])){
            $this->get("migrate_data.service")->migrateResidence($mother,1,null,null,$oldMother["wohnort"]);
        }

        //religion
        if(!is_null($oldMother["konfession"])){
            $this->get("migrate_data.service")->migrateReligion($mother,$oldMother["konfession"], 1);
        }
       
        //status
        if(!is_null($oldMother["stand"])){
            $this->get("migrate_data.service")->migrateStatus($mother,1, $oldMother["stand"]);
        }

        //rank
        if(!is_null($oldMother["rang"])){
            $this->get("migrate_data.service")->migrateRank($mother,1, $oldMother["rang"]);
        }


        //property
        if(!is_null($oldMother["besitz"])){
            $this->get("migrate_data.service")->migrateProperty($mother,1, $oldMother["besitz"]);
        }


        //job
        if(!is_null($oldMother["beruf"])){
            $jobID = $this->get("migrate_data.service")->migrateJob($oldMother["beruf"]);

            $mother->setJob($jobID);
        }

        //born_in_marriage
        if(!is_null($oldMother["ehelich"])){
            $mother->setBornInMarriage($oldMother["ehelich"]);
        }

        return $mother;
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
            $newFather = null;

            //check if reference to person
            if(!is_null($oldFather["vater_id-nr"])){
                //check it?
                $fathersOID = $oldFather["vater_id-nr"];

                $fathersMainID = $this->getIDForOID($fathersOID, $oldDBManager);

                $newFather = $this->migratePerson($fathersMainID, $fathersOID);

                $this->get("migrate_data.service")->migrateIsParent($newPerson, $newFather, $oldFather["kommentar"]);
            }else{
                $newFather = $this->createFather($newPerson, $oldFather);
            }

            $this->migratePartnersOfFather($newFather, $oldPersonID, $oldDBManager);
        }

    }

    private function createFather($newPerson, $oldFather){
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
            $this->get("migrate_data.service")->migrateBirth($father,$oldFather["herkunftsland"],$oldFather["herkunftsterritorium"],$oldFather["herkunftsort"],$oldFather["geburtsland"], $oldFather["geburtsort"],$oldFather["geboren"], $oldFather["geburtsterritorium"]);
        }

        //baptism
        if(!is_null($oldFather["getauft"]) ||
            !is_null($oldFather["taufort"])){
            $this->get("migrate_data.service")->migrateBaptism($father,$oldFather["getauft"], $oldFather["taufort"]);
        }

        //death
        if(!is_null($oldFather["gestorben"]) || 
            !is_null($oldFather["todesort"]) || 
            !is_null($oldFather["todesterritorium"]) || 
            !is_null($oldFather["begraben"]) || 
            !is_null($oldFather["begräbnisort"])){
            //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
            $this->get("migrate_data.service")->migrateDeath($father,$oldFather["todesort"],$oldFather["gestorben"], null, null, $oldFather["todesterritorium"], null, $oldFather["begräbnisort"], $oldFather["begraben"]);
        }

        //residence
        if(!is_null($oldFather["wohnort"]) ||
            !is_null($oldFather["wohnterritorium"]) ||
            !is_null($oldFather["wohnland"])){
            $this->get("migrate_data.service")->migrateResidence($father,1,$oldFather["wohnland"],$oldFather["wohnterritorium"],$oldFather["wohnort"]);
        }

        //religion
        if(!is_null($oldFather["konfession"])){
            $this->get("migrate_data.service")->migrateReligion($father,$oldFather["konfession"], 1);
        }
       
        //status
        if(!is_null($oldFather["stand"])){
            $this->get("migrate_data.service")->migrateStatus($father,1, $oldFather["stand"]);
        }

        //rank
        if(!is_null($oldFather["rang"])){
            $this->get("migrate_data.service")->migrateRank($father,1, $oldFather["rang"]);
        }


        //property
        if(!is_null($oldFather["besitz"])){
            $this->get("migrate_data.service")->migrateProperty($father,1, $oldFather["besitz"]);
        }


        //job
        if(!is_null($oldFather["beruf"])){
            $jobID = $this->get("migrate_data.service")->migrateJob($oldFather["beruf"]);

            $father->setJob($jobID);
        }

        //education
        if(!is_null($oldFather["ausbildung"]) ||
            !is_null($oldFather["bildungsabschluss"])){
            //$educationOrder, $label, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $graduationLabel=null, $graduationDate=null, $graduationLocation=null, $comment=null
            $this->get("migrate_data.service")->migrateEducation($father,1, $oldFather["ausbildung"], null, null, null, null, null, null, $oldFather["bildungsabschluss"]);
        }

        //honour
        if(!is_null($oldFather["ehren"])){
            $this->get("migrate_data.service")->migrateHonour($father,1, $oldFather["ehren"]);
        }

        //born_in_marriage
        if(!is_null($oldFather["ehelich"])){
            $father->setBornInMarriage($oldFather["ehelich"]);
        }

        $this->get("migrate_data.service")->migrateIsParent($newPerson, $father);

        return $father;
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

            $newSibling = null;

            //check if reference to person
            if(!is_null($oldSibling["geschwister_id-nr"])){
                //check it?
                $siblingsOID = $oldSibling["geschwister_id-nr"];

                $siblingsMainId = $this->getIDForOID($siblingsOID, $oldDBManager);

                $newSibling = $this->migratePerson($siblingsMainId, $siblingsOID);

                $this->get("migrate_data.service")->migrateIsSibling($newPerson, $newSibling, $oldSibling["kommentar"]);
            }else{
                $newSibling = $this->createSibling($newPerson, $oldSibling, $oldPersonID, $oldDBManager);                
            }

            $this->migrateMarriagePartnersOfSibling($newSibling, $oldSibling["order"], $oldPersonID, $oldDBManager);

            $this->migrateFatherOfSibling($newSibling,$oldSibling["order"], $oldPersonID, $oldDBManager);
            $this->migrateMotherOfSibling($newSibling,$oldSibling["order"], $oldPersonID, $oldDBManager);
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
            //education
            for($i = 0; $i < count($siblingEducation); $i++){
                $education = $siblingEducation[$i];
                $this->get("migrate_data.service")->migrateEducation($sibling,$education["order2"],$education["ausbildung"],$education["land"],null,$education["ort"],$education["von-ab"],$education["bis"],$education["belegt"],$education["bildungsabschluss"]);
            }
        }


        if(count($siblingHonour) > 0){
            //honour
            for($i = 0; $i < count($siblingHonour); $i++){
                $honour = $siblingHonour[$i];
                $this->get("migrate_data.service")->migrateHonour($sibling,$honour["order2"],$honour["ehren"],$honour["land"]);
            }
        }


        if(count($siblingOrigin) > 0){
            //origin
            for($i = 0; $i < count($siblingOrigin); $i++){
                if($siblingOrigin[$i]['geboren'] != null
                    || $siblingOrigin[$i]['geburtsort'] != null
                    || $siblingOrigin[$i]['geburtsland'] != null
                    || $siblingOrigin[$i]['kommentar'] != null){
                    //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
                    $this->get("migrate_data.service")->migrateBirth($sibling,null,null,null,$siblingOrigin[$i]["geburtsland"], $siblingOrigin[$i]["geburtsort"],$siblingOrigin[$i]["geboren"], null, $siblingOrigin[$i]['kommentar']);
                }

                if($siblingOrigin[$i]['getauft'] != null
                    || $siblingOrigin[$i]['taufort'] != null){
                    $this->get("migrate_data.service")->migrateBaptism($sibling,$siblingOrigin[$i]["getauft"], $siblingOrigin[$i]["taufort"]);
                }
            }
        }


        if(count($siblingRoadOfLive) > 0){
            //roadOfLife
            for($i = 0; $i < count($siblingRoadOfLive); $i++){
                $step = $siblingRoadOfLive[$i];
                $this->get("migrate_data.service")->migrateRoadOfLife($sibling,$step["order2"],$step["stammland"],null,$step["beruf"],null, $step["territorium"],$step["ort"],$step["von-ab"],$step["bis"],$step["belegt"],$step["kommentar"]);
            }
        }


        if(count($siblingRank) > 0){
            //rank
            for($i = 0; $i < count($siblingRank); $i++){
                $rank = $siblingRank[$i];
                $this->get("migrate_data.service")->migrateRank($sibling,$rank["order2"],$rank["rang"],null,$rank["land"],null,null,null,null,null,$rank["kommentar"]);
            }
        }


        if(count($siblingStatus) > 0){
            //status
            for($i = 0; $i < count($siblingStatus); $i++){
                $status = $siblingStatus[$i];
                $$this->get("migrate_data.service")->migrateStatus($sibling,$status["order2"],$status["stand"],$status["land"],null,null,$status["von-ab"]);
            }
        }


        if(count($siblingDeath) > 0){
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
                    $deathId = $this->get("migrate_data.service")->migrateDeath($sibling,$siblingDeath[$i]["todesort"],$siblingDeath[$i]["gestorben"], null, $siblingDeath[$i]["todesursache"], null, $siblingDeath[$i]["friedhof"], $siblingDeath[$i]["begräbnisort"], null,$siblingDeath[$i]["kommentar"]);
                }
            }
        }


        $this->get("migrate_data.service")->migrateIsSibling($newPerson, $sibling);

        return $sibling;
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

            $newMarriagePartner = null;

            //check if reference to person
            if(!is_null($oldMarriagePartner["ehepartner_id-nr"]) ||
                !is_null($oldMarriagePartner["partnerpartner_id-nr"])){
                $this->getLogger()->info("Reference to person found!");


                //check it?
                $marriagePartnersOID = $oldMarriagePartner["ehepartner_id-nr"];

                if(is_null($marriagePartnersOID)){
                    $marriagePartnersOID = $oldMarriagePartner["partnerpartner_id-nr"];
                }

                $marriagePartnersMainID = $this->getIDForOID($marriagePartnersOID, $oldDBManager);

                $newMarriagePartner = $this->migratePerson($marriagePartnersMainID, $marriagePartnersOID);

                //ehe
                $this->migrateWedding($newPerson, $newMarriagePartner, $oldMarriagePartner);
            }else{
                $newMarriagePartner = $this->createMarriagePartner($newPerson, $oldMarriagePartner);                
            }

            //mother in law imports father in law!
            $this->migrateMotherInLaw($newPerson,$newMarriagePartner,$oldMarriagePartner["order"], $oldPersonID, $oldDBManager);

            //children?
            $this->migrateChild($newPerson,$newMarriagePartner,$oldMarriagePartner["order"], $oldPersonID, $oldDBManager);

            //other partners
            $this->migrateOtherPartners($newMarriagePartner,$oldMarriagePartner["order"], $oldPersonID, $oldDBManager);
        }
    }

 private function createMarriagePartner($newPerson, $oldMarriagePartner){

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
            $this->get("migrate_data.service")->migrateBirth($marriagePartner,$oldMarriagePartner["herkunftsland"],$oldMarriagePartner["herkunftsterritorium"],$oldMarriagePartner["herkunftsort"],$oldMarriagePartner["geburtsland"], $oldMarriagePartner["geburtsort"],$oldMarriagePartner["geboren"], $oldMarriagePartner["geburtsterritorium"]);
        }

        //baptism
        if(!is_null($oldMarriagePartner["getauft"]) ||
            !is_null($oldMarriagePartner["taufort"])){
            $this->get("migrate_data.service")->migrateBaptism($marriagePartner,$oldMarriagePartner["getauft"], $oldMarriagePartner["taufort"]);
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
            $this->get("migrate_data.service")->migrateDeath($marriagePartner,$oldMarriagePartner["todesort"],$oldMarriagePartner["gestorben"], $oldMarriagePartner["todesland"], $oldMarriagePartner["todesursache"], $oldMarriagePartner["todesterritorium"], $oldMarriagePartner["friedhof"], $oldMarriagePartner["begräbnisort"], $oldMarriagePartner["begraben"]);
        }


        //religion
        if(!is_null($oldMarriagePartner["konfession"])){
            $this->get("migrate_data.service")->migrateReligion($marriagePartner,$oldMarriagePartner["konfession"], 1);
        }
       
        //status
        if(!is_null($oldMarriagePartner["stand"])){
            $this->get("migrate_data.service")->migrateStatus($marriagePartner,1, $oldMarriagePartner["stand"]);
        }

        //rank
        if(!is_null($oldMarriagePartner["rang"])){
            $this->get("migrate_data.service")->migrateRank($marriagePartner,1, $oldMarriagePartner["rang"]);
        }


        //property
        if(!is_null($oldMarriagePartner["besitz"])){
            $this->get("migrate_data.service")->migrateProperty($marriagePartner,1, $oldMarriagePartner["besitz"]);
        }


        //job
        if(!is_null($oldMarriagePartner["beruf"])){
            $jobID = $this->get("migrate_data.service")->migrateJob($oldMarriagePartner["beruf"]);

            $marriagePartner->setJob($jobID);
        }

        //education
        if(!is_null($oldMarriagePartner["bildungsabschluss"])){
            //$educationOrder, $label, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $graduationLabel=null, $graduationDate=null, $graduationLocation=null, $comment=null
            $this->get("migrate_data.service")->migrateEducation($marriagePartner,1, null, null, null, null, null, null, null, $oldMarriagePartner["bildungsabschluss"]);
        }

        //honour
        if(!is_null($oldMarriagePartner["ehren"])){
            $this->get("migrate_data.service")->migrateHonour($marriagePartner,1, $oldMarriagePartner["ehren"]);
        }

        $this->migrateWedding($newPerson, $marriagePartner, $oldMarriagePartner);

        return $marriagePartner;
    }

    private function getOppositeGender($newPerson){
        $this->getLogger()->debug("Finding Gender.");
        $genderOfPerson = $newPerson->getGender();

        $oppositeGender = $this->get("migrate_data.service")->getOppositeGender($genderOfPerson);

        $this->getLogger()->debug("Gender of Person: " . $genderOfPerson . " OppositeGender: " . $oppositeGender);


        return $oppositeGender;
    }


    private function migrateWedding($newPerson, $marriagePartner, $oldMarriagePartner){
        //$weddingOrder, $husband, $wife, $weddingDateid, $weddingLocationid, $weddingTerritoryid, $bannsDateid, $breakupReason, $breakupDateid, $marriageComment, $beforeAfter, $comment
        $this->get("migrate_data.service")->migrateWedding($oldMarriagePartner['order'], $newPerson, $marriagePartner, $oldMarriagePartner['hochzeitstag'], $oldMarriagePartner['hochzeitsort'], $oldMarriagePartner['hochzeitsterritorium'], $oldMarriagePartner['aufgebot'], $oldMarriagePartner['auflösung'], $oldMarriagePartner['gelöst'], $oldMarriagePartner['verheiratet'], $oldMarriagePartner['vorher-nachher'], null);
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

    private function migrateChild($newPerson,$newMarriagePartner,$marriageOrder, $oldPersonID, $oldDBManager){
        //non paternal
        $children = $this->getChildWithNativeQuery($oldPersonID,$marriageOrder, $oldDBManager);

        for($i = 0; $i < count($children); $i++){
            $oldChild = $children[$i];

            $newChild = null;

            //check if reference to person
            if(!is_null($oldChild["kind_id-nr"])){
                //check it?
                $childsOID = $oldChild["kind_id-nr"];

                $childsMainId = $this->getIDForOID($childsOID, $oldDBManager);

                $newChild = $this->migratePerson($childsMainId, $childsOID);

                $this->get("migrate_data.service")->migrateIsParent($newChild, $newPerson, $oldChild["kommentar"]);
                $this->get("migrate_data.service")->migrateIsParent($newChild, $newMarriagePartner, $oldChild["kommentar"]);
            }else{
                $newChild =$this->createChild($newPerson,$newMarriagePartner, $oldChild, $oldPersonID, $oldDBManager);                
            }

            $this->migrateMarriagePartnersOfChildren($newPerson,$newChild,$marriageOrder,$oldChild['order2'], $oldPersonID, $oldDBManager);

            //grandchild etc.
        }
    }

    private function createChild($newPerson,$newMarriagePartner, $oldChild, $oldPersonID, $oldDBManager){
        //$firstName, $patronym, $lastName, $gender, $nation, $comment
        $child = $this->get("migrate_data.service")->migrateRelative($oldChild["vornamen"], $oldChild["russ_vornamen"], $oldChild["name"], $oldChild["geschlecht"], null, $oldChild["kommentar"]);

        $child->setForeName($oldChild["rufnamen"]);

        //additional data
        $childEducation = $this->getChildsEducationWithNativeQuery($oldPersonID, $oldChild["order"], $oldChild["order2"], $oldDBManager);

        $childProperty = $this->getChildsPropertyWithNativeQuery($oldPersonID, $oldChild["order"], $oldChild["order2"], $oldDBManager);

        $childHonour = $this->getChildsHonourWithNativeQuery($oldPersonID, $oldChild["order"], $oldChild["order2"], $oldDBManager);

        $childOrigin = $this->getChildsOriginWithNativeQuery($oldPersonID, $oldChild["order"], $oldChild["order2"], $oldDBManager);

        $childRoadOfLife = $this->getChildsRoadOfLifeWithNativeQuery($oldPersonID, $oldChild["order"], $oldChild["order2"], $oldDBManager);

        $childRank = $this->getChildsRankWithNativeQuery($oldPersonID, $oldChild["order"], $oldChild["order2"], $oldDBManager);

        $childReligion = $this->getChildsReligionWithNativeQuery($oldPersonID, $oldChild["order"], $oldChild["order2"], $oldDBManager);

        $childStatus = $this->getChildsStatusWithNativeQuery($oldPersonID, $oldChild["order"], $oldChild["order2"], $oldDBManager);

        $childDeath = $this->getChildsDeathWithNativeQuery($oldPersonID, $oldChild["order"], $oldChild["order2"], $oldDBManager);

        //birth
        //geboren, geburtsort, from oldChild
        if(!is_null($oldChild["geboren"]) 
            || !is_null($oldChild["geburtsort"]) 
            || count($childOrigin) > 0){

            if(count($childOrigin) == 0){
                $this->get("migrate_data.service")->migrateBirth($child,null,null,null,null, $oldChild["geburtsort"],$oldChild["geboren"]);
            }else{
                //BELEGT?!?!?!
                //origin
                for($i = 0; $i < count($childOrigin); $i++){
                    $origin = $childOrigin[$i];

                    $geburtsOrt = $oldChild["geburtsort"];
                    $geboren = $oldChild["geboren"];

                    if(!is_null($origin['geburtsort'])
                        && $origin['geburtsort'] != $geburtsOrt){
                         if(!is_null($geburtsOrt)){
                            // add it with oder
                            $geburtsOrt .= " ODER ".$origin['geburtsort'];
                        }else{
                            $geburtsOrt = $origin['geburtsort'];
                        }
                    }

                    if(!is_null($origin['geboren'])
                        && $origin['geboren'] != $geboren){
                        if(!is_null($geboren)){
                            //create date array? add comment?
                            $geboren .= ";".$origin['geboren'];
                        }else{
                            $geboren = $origin['geboren'];
                        }
                    }

                    if(!is_null($geboren)
                        || !is_null($geburtsOrt)
                        || !is_null($origin['geburtsland'])
                        || !is_null($origin['geburtsterritorium'])
                        || !is_null($origin['kommentar'])){
                        //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
                        $this->get("migrate_data.service")->migrateBirth($child,null,null,null,$origin["geburtsland"], $geburtsOrt,$geboren, $origin['geburtsterritorium'], $origin['kommentar']);
                    }


                    if(!is_null($origin['getauft'])
                        || !is_null($origin['taufort'])){
                        $this->get("migrate_data.service")->migrateBaptism($child,$origin["getauft"], $origin["taufort"]);
                    }

                }
            }

        }

        if(count($childEducation) > 0){
            //education
            for($i = 0; $i < count($childEducation); $i++){
                $educationID = $this->get("migrate_data.service")->migrateEducation($child,$education["order3"],$education["ausbildung"],$education["land"],null,$education["ort"],$education["von-ab"],$education["bis"],$education["belegt"],$education["bildungsabschluss"],$education["bildungsabschlussdatum"], $education["bildungsabschlussort"], $education["kommentar"]);

            }
        }


        if(count($childProperty) > 0){
            //property
            for($i = 0; $i < count($childProperty); $i++){
                $property = $childProperty[$i];
                //$propertyOrder, $label, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $comment=null
                $this->get("migrate_data.service")->migrateProperty($child,$property["order3"],$property["besitz"],$property["land"],$property['territorium'],$property["ort"],$property["von-ab"],null,$property["belegt"]);
            }
        }


        if(count($childHonour) > 0){
            //honour
            for($i = 0; $i < count($childHonour); $i++){
                $honour = $childHonour[$i];
                //$honourOrder, $label, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $comment=null
                $this->get("migrate_data.service")->migrateHonour($child,$honour["order3"],$honour["ehren"],$honour["land"],null,$honour["ort"],$honour["von-ab"]);
            }
        }





        if(count($childRoadOfLife) > 0){
            //roadOfLife
            for($i = 0; $i < count($childRoadOfLife); $i++){
                $step = $childRoadOfLife[$i];
                //$roadOfLifeOrder, $originCountry=null, $originTerritory=null, $job=null, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $comment=null
                $this->get("migrate_data.service")->migrateRoadOfLife($child,$step["order3"],$step["stammland"],null,$step["beruf"],$step["land"], $step["territorium"],$step["ort"],$step["von-ab"],$step["bis"],$step["belegt"],$step["kommentar"]);
            }
        }


        if(count($childRank) > 0){
            //rank
            for($i = 0; $i < count($childRank); $i++){
                $rank = $childRank[$i];
                //$rankOrder, $label, $class=null, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $comment=null
                $this->get("migrate_data.service")->migrateRank($child,$rank["order3"],$rank["rang"],$rank["rangklasse"],$rank["land"],null,$rank["ort"],$rank["von-ab"],$rank["bis"],$rank["belegt"],$rank["kommentar"]);
            }
        }

        //religoin
        if(count($childReligion) > 0){
            //rank
            for($i = 0; $i < count($childReligion); $i++){
                $religion = $childReligion[$i];
                //$name, $religionOrder, $change_of_religion=null, $provenDate=null, $fromDate=null, $comment=null
                $this->get("migrate_data.service")->migrateReligion($child,$religion["konfession"], $religion["order3"], null, null, null, $religion["kommentar"]);

            }
        }


        if(count($childStatus) > 0){
            //status
            for($i = 0; $i < count($childStatus); $i++){
                $status = $childStatus[$i];
                //$statusOrder, $label, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $comment=null
                $this->get("migrate_data.service")->migrateStatus($child,$status["order3"],$status["stand"],$status["land"],$status["territorium"],$status["ort"],$status["von-ab"], null, $status["belegt"],$status["kommentar"]);
            }
        }


        if(count($childDeath) > 0){
            //death
            for($i = 0; $i < count($childDeath); $i++){
                $death = $childDeath[$i];
                //death
                //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
                $this->get("migrate_data.service")->migrateDeath($child,$death["todesort"],$death["gestorben"], $death["todesland"], $death["todesursache"], $death["todesterritorium"], $death["friedhof"], $death["begräbnisort"], $death["begraben"],$death["kommentar"]);
            }
        }
        

        $this->get("migrate_data.service")->migrateIsParent($child, $newPerson);
        $this->get("migrate_data.service")->migrateIsParent($child, $newMarriagePartner);

        return $child;
    }

    private function getChildWithNativeQuery($oldPersonID, $marriageOrder, $oldDBManager){
        $sql = "SELECT ID, `order`, order2, vornamen, russ_vornamen, name, rufnamen, geboren, geburtsort, geschlecht, `kind_id-nr`, kommentar
                    FROM `kind` WHERE ID=:personID AND `order`=:marriageOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getChildsEducationWithNativeQuery($oldPersonID, $marriageOrder, $childOrder, $oldDBManager){
        $sql = "SELECT ID, `order`, order2, order3, land, ort, ausbildung, `von-ab`, bis, bildungsabschluss, bildungsabschlussdatum, bildungsabschlussort, belegt, kommentar
            FROM `ausbildung_des_kindes` WHERE ID=:personID AND `order`=:marriageOrder AND `order2`=:childOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getChildsPropertyWithNativeQuery($oldPersonID, $marriageOrder, $childOrder, $oldDBManager){
        $sql = "SELECT ID, `order`, order2, order3, land, ort, territorium, besitz, `von-ab`, belegt
                FROM `besitz_des_kindes` WHERE ID=:personID AND `order`=:marriageOrder AND `order2`=:childOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function getChildsHonourWithNativeQuery($oldPersonID, $marriageOrder, $childOrder, $oldDBManager){
        $sql = "SELECT ID, `order`, order2, order3, ort, land, `von-ab`, ehren
                    FROM `ehren_des_kindes` WHERE ID=:personID AND `order`=:marriageOrder AND `order2`=:childOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getChildsOriginWithNativeQuery($oldPersonID, $marriageOrder, $childOrder, $oldDBManager){
        $sql = "SELECT ID, `order`, order2, order3, geboren, geburtsort, geburtsterritorium, geburtsland, getauft, taufort, belegt, kommentar
                FROM `herkunft_des_kindes` WHERE ID=:personID AND `order`=:marriageOrder AND `order2`=:childOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getChildsRoadOfLifeWithNativeQuery($oldPersonID, $marriageOrder, $childOrder, $oldDBManager){
        $sql = "SELECT ID, `order`, order2, order3, ort, territorium, land, stammland, beruf, `von-ab`, bis, belegt, kommentar
                FROM `lebensweg_des_kindes` WHERE ID=:personID AND `order`=:marriageOrder AND `order2`=:childOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getChildsRankWithNativeQuery($oldPersonID, $marriageOrder, $childOrder, $oldDBManager){
        $sql = "SELECT ID, `order`, order2, order3, ort, land, rang, rangklasse, `von-ab`, bis, belegt, kommentar
                FROM `rang_des_kindes` WHERE ID=:personID AND `order`=:marriageOrder AND `order2`=:childOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getChildsReligionWithNativeQuery($oldPersonID, $marriageOrder, $childOrder, $oldDBManager){
        $sql = "SELECT ID, `order`, order2, order3, konfession, kommentar
                FROM `religion_des_kindes` WHERE ID=:personID AND `order`=:marriageOrder AND `order2`=:childOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getChildsStatusWithNativeQuery($oldPersonID, $marriageOrder, $childOrder, $oldDBManager){
        $sql = "SELECT ID, `order`, order2, order3, ort, territorium, land, stand, `von-ab`, belegt, kommentar
                FROM `stand_des_kindes` WHERE ID=:personID AND `order`=:marriageOrder AND `order2`=:childOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getChildsDeathWithNativeQuery($oldPersonID, $marriageOrder, $childOrder, $oldDBManager){
        $sql = "SELECT `ID`,`order`,`order2`,`order3`,`todesort`,`todesterritorium`,`gestorben`,`begräbnisort`,`todesursache`,`friedhof`,`begraben`,`todesland`,`kommentar` 
                FROM `tod_des_kindes` WHERE ID=:personID AND `order`=:marriageOrder AND `order2`=:childOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->execute();


        return $stmt->fetchAll();
    }


    private function migrateFatherInLaw($newPerson, $newMarriagePartner, $marriageOrder, $newMotherInLaw, $motherInLawOrder, $oldPersonID, $oldDBManager){
        $fathersInLaw = $this->getFatherInLawWithNativeQuery($oldPersonID,$marriageOrder, $motherInLawOrder, $oldDBManager);

        for($i = 0; $i < count($fathersInLaw); $i++){
            $oldFatherInLaw = $fathersInLaw[$i];

            //check if reference to person
            if(!is_null($oldFatherInLaw["schwiegervater_id-nr"])){
                //check it?
                $fatherInLawOID = $oldFatherInLaw["schwiegervater_id-nr"];

                $fatherInLawsMainId = $this->getIDForOID($fatherInLawOID, $oldDBManager);

                $newFatherInLaw = $this->migratePerson($fatherInLawsMainId, $fatherInLawOID);

                $this->migrateWeddingOfParentsInLaw($newFatherInLaw, $newMotherInLaw, $oldFatherInLaw);
                $this->get("migrate_data.service")->migrateIsParentInLaw($newPerson, $newFatherInLaw, $oldFatherInLaw["kommentar"]);
                $this->get("migrate_data.service")->migrateIsParent($newMarriagePartner, $newFatherInLaw, $oldFatherInLaw["kommentar"]);
            }else{
                $this->createFatherInLaw($newPerson,$newMarriagePartner,$newMotherInLaw, $oldFatherInLaw);                
            }
        }
    }

    private function migrateWeddingOfParentsInLaw($newFatherInLaw, $newMotherInLaw, $oldFatherInLaw){
        //$weddingOrder, $husband, $wife, $weddingDateid, $weddingLocationid, $weddingTerritoryid, $bannsDateid, $breakupReason, $breakupDateid, $marriageComment, $beforeAfter, $comment
        $this->get("migrate_data.service")->migrateWedding(1, $newFatherInLaw, $newMotherInLaw, null, $oldFatherInLaw['hochzeitsort']);
    }

    private function createFatherInLaw($newPerson,$newMarriagePartner,$newMotherInLaw, $oldFatherInLaw){
        //$firstName, $patronym, $lastName, $gender, $nation, $comment
        $fatherInLaw = $this->get("migrate_data.service")->migrateRelative($oldFatherInLaw["vornamen"], $oldFatherInLaw["russ_vornamen"], $oldFatherInLaw["name"], "männlich" , $oldFatherInLaw["nation"], $oldFatherInLaw["kommentar"]);


         //birth
        if(!is_null($oldFatherInLaw["herkunftsort"]) || 
            !is_null($oldFatherInLaw["herkunftsterritorium"]) || 
            !is_null($oldFatherInLaw["geboren"])){
            //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
            $this->get("migrate_data.service")->migrateBirth($fatherInLaw,null,$oldFatherInLaw["herkunftsterritorium"],$oldFatherInLaw["herkunftsort"],null, null,$oldFatherInLaw["geboren"]);
        }

        //baptism
        if(!is_null($oldFatherInLaw["getauft"]) ||
            !is_null($oldFatherInLaw["taufort"])){
            $this->get("migrate_data.service")->migrateBaptism($fatherInLaw,$oldFatherInLaw["getauft"], $oldFatherInLaw["taufort"]);
        }

        //death
        if(!is_null($oldFatherInLaw["gestorben"]) || 
            !is_null($oldFatherInLaw["todesort"]) || 
            !is_null($oldFatherInLaw["begraben"]) || 
            !is_null($oldFatherInLaw["begräbnisort"])){
            //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
            $this->get("migrate_data.service")->migrateDeath($fatherInLaw,$oldFatherInLaw["todesort"],$oldFatherInLaw["gestorben"], null, null, null,null, $oldFatherInLaw["begräbnisort"], $oldFatherInLaw["begraben"]);
        }

        //residence
        if(!is_null($oldFatherInLaw["wohnort"]) ||
            !is_null($oldFatherInLaw["wohnterritorium"]) ||
            !is_null($oldFatherInLaw["wohnland"])){
            $this->get("migrate_data.service")->migrateResidence($fatherInLaw,1,$oldFatherInLaw["wohnland"],$oldFatherInLaw["wohnterritorium"],$oldFatherInLaw["wohnort"]);

        }

        //religion
        if(!is_null($oldFatherInLaw["konfession"])){
            $this->get("migrate_data.service")->migrateReligion($fatherInLaw,$oldFatherInLaw["konfession"], 1);
        }
       
        //status
        if(!is_null($oldFatherInLaw["stand"])){
            $this->get("migrate_data.service")->migrateStatus($fatherInLaw,1, $oldFatherInLaw["stand"]);
        }

        //rank
        if(!is_null($oldFatherInLaw["rang"])){
            $this->get("migrate_data.service")->migrateRank($fatherInLaw,1, $oldFatherInLaw["rang"]);
        }


        //property
        if(!is_null($oldFatherInLaw["besitz"])){
            $this->get("migrate_data.service")->migrateProperty($fatherInLaw,1, $oldFatherInLaw["besitz"]);
        }


        //job
        if(!is_null($oldFatherInLaw["beruf"])){
            $jobID = $this->get("migrate_data.service")->migrateJob($oldFatherInLaw["beruf"]);

            $fatherInLaw->setJob($jobID);
        }

        //education
        if(!is_null($oldFatherInLaw["bildungsabschluss"])){
            //$educationOrder, $label, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $graduationLabel=null, $graduationDate=null, $graduationLocation=null, $comment=null
            $this->get("migrate_data.service")->migrateEducation($fatherInLaw,1, null, null, null, null, null, null, null, $oldFatherInLaw["bildungsabschluss"]);
        }

        //honour
        if(!is_null($oldFatherInLaw["ehren"])){
            $this->get("migrate_data.service")->migrateHonour($fatherInLaw,1, $oldFatherInLaw["ehren"]);
        }

        //born_in_marriage
        if(!is_null($oldFatherInLaw["ehelich"])){
            $fatherInLaw->setBornInMarriage($oldFatherInLaw["ehelich"]);
        }

        $this->migrateWeddingOfParentsInLaw($fatherInLaw, $newMotherInLaw, $oldFatherInLaw);

        $this->get("migrate_data.service")->migrateIsParentInLaw($newPerson, $fatherInLaw);
        $this->get("migrate_data.service")->migrateIsParent($newMarriagePartner, $fatherInLaw);
    }

    private function getFatherInLawWithNativeQuery($oldPersonID, $marriageOrder, $motherInLawOrder, $oldDBManager){
        $sql = "SELECT ID, `order`, order2, vornamen, russ_vornamen, name, geboren, gestorben, todesort, 
        begraben, begräbnisort, ehelich, hochzeitsort, wohnort, wohnland, wohnterritorium, nation, 
        getauft, taufort, konfession, herkunftsort, herkunftsterritorium, bildungsabschluss, beruf, 
        rang, ehren, stand, besitz, `schwiegervater_id-nr`, kommentar 
        FROM `schwiegervater` WHERE ID=:personID AND `order`=:marriageOrder AND order2=:motherInLawOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->bindValue('motherInLawOrder', $motherInLawOrder);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function migrateMotherInLaw($newPerson,$newMarriagePartner, $marriageOrder, $oldPersonID, $oldDBManager){
        $mothersInLaw = $this->getMotherInLawWithNativeQuery($oldPersonID,$marriageOrder, $oldDBManager);

        for($i = 0; $i < count($mothersInLaw); $i++){
            $oldMotherInLaw = $mothersInLaw[$i];
            $newMotherInLaw = $this->createMotherInLaw($newPerson,$newMarriagePartner, $oldMotherInLaw);
            $this->migrateFatherInLaw($newPerson,$newMarriagePartner,$marriageOrder,$newMotherInLaw, $oldMotherInLaw['order2'], $oldPersonID, $oldDBManager);
        }
    }

    private function createMotherInLaw($newPerson,$newMarriagePartner, $oldMotherInLaw){
         //$firstName, $patronym, $lastName, $gender, $nation, $comment
        $motherInLaw = $this->get("migrate_data.service")->migrateRelative($oldMotherInLaw["vornamen"], $oldMotherInLaw["russ_vornamen"], $oldMotherInLaw["name"], "weiblich" , $oldMotherInLaw["nation"], $oldMotherInLaw["kommentar"]);


         //birth
        if(!is_null($oldMotherInLaw["herkunftsort"]) || 
            !is_null($oldMotherInLaw["geburtsort"]) || 
            !is_null($oldMotherInLaw["geboren"])){
            //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
            $this->get("migrate_data.service")->migrateBirth($motherInLaw,null,null,$oldMotherInLaw["herkunftsort"],$oldMotherInLaw["geburtsort"], null,$oldMotherInLaw["geboren"]);
        }

        //death
        if(!is_null($oldMotherInLaw["gestorben"]) || 
            !is_null($oldMotherInLaw["todesort"])){
            //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
            $this->get("migrate_data.service")->migrateDeath($motherInLaw,$oldMotherInLaw["todesort"],$oldMotherInLaw["gestorben"]);
        }

        //status
        if(!is_null($oldMotherInLaw["stand"])){
            $this->get("migrate_data.service")->migrateStatus($motherInLaw,1, $oldMotherInLaw["stand"]);
        }


        //job
        if(!is_null($oldMotherInLaw["beruf"])){
            $jobID = $this->get("migrate_data.service")->migrateJob($oldMotherInLaw["beruf"]);

            $motherInLaw->setJob($jobID);
        }

        //born_in_marriage
        if(!is_null($oldMotherInLaw["ehelich"])){
            $motherInLaw->setBornInMarriage($oldMotherInLaw["ehelich"]);
        }

        $this->get("migrate_data.service")->migrateIsParentInLaw($newPerson, $motherInLaw);
        $this->get("migrate_data.service")->migrateIsParent($newMarriagePartner, $motherInLaw);

        return $motherInLaw;
    }

    private function getMotherInLawWithNativeQuery($oldPersonID, $marriageOrder, $oldDBManager){
        $sql = "SELECT ID, `order`, order2, vornamen, russ_vornamen, name, geboren, geburtsort, 
            gestorben, todesort, nation, ehelich, herkunftsort, beruf, stand, kommentar
            FROM `schwiegermutter` WHERE ID=:personID AND `order`=:marriageOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    //other marriagepartners to the marriage partners of the main person
    private function migrateOtherPartners($newMarriagePartner,$marriageOrder, $oldPersonID, $oldDBManager){
        $otherPartners = $this->getOtherPartnersWithNativeQuery($oldPersonID,$marriageOrder, $oldDBManager);

        for($i = 0; $i < count($otherPartners); $i++){
            $oldOtherPartner = $otherPartners[$i];

            //check if reference to person
            if(!is_null($oldOtherPartner["partnerpartner_id-nr"])){
                //check it?
                $partnerPartnerOID = $oldOtherPartner["partnerpartner_id-nr"];

                $partnerPartnerMainId = $this->getIDForOID($partnerPartnerOID, $oldDBManager);

                $newPartnerPartner = $this->migratePerson($partnerPartnerMainId, $partnerPartnerOID);

                $this->migrateWeddingOfOtherPartners($newPartnerPartner, $newMarriagePartner, $oldOtherPartner);
            }else{
                $this->createOtherPartners($newMarriagePartner, $oldOtherPartner);                 
            }
                          
        }
    }

    private function migrateWeddingOfOtherPartners($newPartnerPartner, $newMarriagePartner, $oldOtherPartners){
        //$weddingOrder, $husband, $wife, $weddingDateid, $weddingLocationid, $weddingTerritoryid, $bannsDateid, $breakupReason, $breakupDateid, $marriageComment, $beforeAfter, $comment
        $this->get("migrate_data.service")->migrateWedding($oldOtherPartners['order2'], $newPartnerPartner, $newMarriagePartner, $oldOtherPartners['hochzeitstag'], 
            $oldOtherPartners['hochzeitsort'], null, $oldOtherPartners['aufgebot'], $oldOtherPartners['auflösung'], $oldOtherPartners['gelöst'], 
            $oldOtherPartners['verheiratet'], $oldOtherPartners['vorher-nachher'], null);
    }

    private function createOtherPartners($newMarriagePartner, $oldOtherPartner){
        //$firstName, $patronym, $lastName, $gender, $nation, $comment
        $gender = $this->getOppositeGender($newMarriagePartner);
        $otherPartner = $this->get("migrate_data.service")->migratePartner($oldOtherPartner["vornamen"], $oldOtherPartner["russ_vornamen"], $oldOtherPartner["name"], $gender, null, $oldOtherPartner["kommentar"]);

         //birth
        if(!is_null($oldOtherPartner["herkunftsort"]) || 
            !is_null($oldOtherPartner["herkunftsterritorium"]) || 
            !is_null($oldOtherPartner["geburtsort"]) || 
            !is_null($oldOtherPartner["geboren"])){
            //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
            $this->get("migrate_data.service")->migrateBirth($otherPartner,null,$oldOtherPartner["herkunftsterritorium"],$oldOtherPartner["herkunftsort"],null,$oldOtherPartner["geburtsort"], $oldOtherPartner["geboren"]);
        }

        //death
        if(!is_null($oldOtherPartner["gestorben"]) || 
            !is_null($oldOtherPartner["friedhof"]) || 
            !is_null($oldOtherPartner["todesort"])){
            //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
            $this->get("migrate_data.service")->migrateDeath($otherPartner,$oldOtherPartner["todesort"],$oldOtherPartner["gestorben"], null, null, null, $oldOtherPartner["friedhof"]);
        }

       //religion
        if(!is_null($oldOtherPartner["konfession"])){
            $this->get("migrate_data.service")->migrateReligion($otherPartner,$oldOtherPartner["konfession"], 1);
        }
       
        //status
        if(!is_null($oldOtherPartner["stand"])){
            $this->get("migrate_data.service")->migrateStatus($otherPartner,1, $oldOtherPartner["stand"]);
        }

        //rank
        if(!is_null($oldOtherPartner["rang"])){
            $this->get("migrate_data.service")->migrateRank($otherPartner,1, $oldOtherPartner["rang"]);
        }


        //property
        if(!is_null($oldOtherPartner["besitz"])){
            $this->get("migrate_data.service")->migrateProperty($otherPartner,1, $oldOtherPartner["besitz"]);
        }


        //job
        if(!is_null($oldOtherPartner["beruf"])){
            $jobID = $this->get("migrate_data.service")->migrateJob($oldOtherPartner["beruf"]);

            $otherPartner->setJob($jobID);
        }

        //education
        if(!is_null($oldOtherPartner["bildungsabschluss"])){
            //$educationOrder, $label, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $graduationLabel=null, $graduationDate=null, $graduationLocation=null, $comment=null
            $this->get("migrate_data.service")->migrateEducation($otherPartner,1, null, null, null, null, null, null, null, $oldOtherPartner["bildungsabschluss"]);
        }

        //honour
        if(!is_null($oldOtherPartner["ehren"])){
            $this->get("migrate_data.service")->migrateHonour($otherPartner,1, $oldOtherPartner["ehren"]);
        }

        $this->migrateWeddingOfOtherPartners($otherPartner, $newMarriagePartner, $oldOtherPartner);
    }

    private function getOtherPartnersWithNativeQuery($oldPersonID, $marriageOrder, $oldDBManager){
        $sql = "SELECT ID, vornamen, russ_vornamen, name, `order`, order2, 
        geboren, geburtsort, gestorben, todesort, friedhof, herkunftsort, 
        herkunftsterritorium, konfession, `vorher-nachher`, aufgebot, 
        verheiratet, hochzeitstag, hochzeitsort, auflösung, gelöst, beruf, 
        stand, rang, ehren, besitz, bildungsabschluss, `partnerpartner_id-nr`, kommentar
        FROM `anderer_partner` WHERE ID=:personID AND `order`=:marriageOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    //other marriage partners of the father of the person
    private function migratePartnersOfFather($newFather, $oldPersonID, $oldDBManager){
        $partnersOfFather = $this->getPartnersOfFatherWithNativeQuery($oldPersonID, $oldDBManager);

        for($i = 0; $i < count($partnersOfFather); $i++){
            $oldPartnersOfFather = $partnersOfFather[$i];
            $this->createPartnersOfFather($newFather, $oldPartnersOfFather);                
        }
    }

    private function createPartnersOfFather($newFather, $oldPartnersOfFather){
        $partnerOfFather = $this->get("migrate_data.service")->migratePartner($oldPartnersOfFather["vornamen"], null, $oldPartnersOfFather["name"], "weiblich", null, $oldPartnersOfFather["kommentar"]);

         //birth
        if(!is_null($oldPartnersOfFather["geburtsort"]) || 
            !is_null($oldPartnersOfFather["geboren"])){
            //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
            $this->get("migrate_data.service")->migrateBirth($partnerOfFather,null,null,null,$oldPartnersOfFather["geburtsort"], null,$oldPartnersOfFather["geboren"]);
        }

         //baptism
        if(!is_null($oldPartnersOfFather["getauft"]) ||
            !is_null($oldPartnersOfFather["taufort"])){
            $this->get("migrate_data.service")->migrateBaptism($partnerOfFather,$oldPartnersOfFather["getauft"], $oldPartnersOfFather["taufort"]);
        }


        //death
        if(!is_null($oldPartnersOfFather["gestorben"]) || 
            !is_null($oldPartnersOfFather["todesort"])){
            //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
            $this->get("migrate_data.service")->migrateDeath($partnerOfFather,$oldPartnersOfFather["todesort"],$oldPartnersOfFather["gestorben"]);
        }

        //status
        if(!is_null($oldPartnersOfFather["stand"])){
            $this->get("migrate_data.service")->migrateStatus($partnerOfFather,1, $oldPartnersOfFather["stand"]);
        }

        //job
        if(!is_null($oldPartnersOfFather["beruf"])){
            $jobID = $this->get("migrate_data.service")->migrateJob($oldPartnersOfFather["beruf"]);

            $partnerOfFather->setJob($jobID);
        }

        $this->get("migrate_data.service")->migrateWedding($oldPartnersOfFather['order2'], $newFather, $partnerOfFather, $oldPartnersOfFather['hochzeitstag'], $oldPartnersOfFather['hochzeitsort'], null, null, null, null, $oldPartnersOfFather['verheiratet'], $oldPartnersOfFather['vorher-nachher'], null);
    }

    private function getPartnersOfFatherWithNativeQuery($oldPersonID, $oldDBManager){
        $sql = "SELECT ID, `order`, order2, vornamen, name, geboren, geburtsort, getauft, 
        taufort, gestorben, todesort, verheiratet, hochzeitstag, hochzeitsort, beruf, stand, `vorher-nachher`, kommentar
        FROM `partnerin_des_vaters` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function migratePartnersOfMother($newMother, $oldPersonID, $oldDBManager){
        $partnersOfMother = $this->getPartnersOfMotherWithNativeQuery($oldPersonID, $oldDBManager);

        for($i = 0; $i < count($partnersOfMother); $i++){
            $oldPartnersOfMother = $partnersOfMother[$i];
            $this->createPartnersOfMother($newMother, $oldPartnersOfMother);                
        }
    }

    private function createPartnersOfMother($newMother, $oldPartnersOfMother){
        $partnerOfMother = $this->get("migrate_data.service")->migratePartner($oldPartnersOfMother["vornamen"], null, $oldPartnersOfMother["name"], "männlich", null, $oldPartnersOfMother["kommentar"]);

        //status
        if(!is_null($oldPartnersOfMother["stand"])){
            $this->get("migrate_data.service")->migrateStatus($partnerOfMother,1, $oldPartnersOfMother["stand"]);
        }

        //rank
        if(!is_null($oldPartnersOfMother["rang"])){
            $this->get("migrate_data.service")->migrateRank($partnerOfMother,1, $oldPartnersOfMother["rang"]);
        }

        //job
        if(!is_null($oldPartnersOfMother["beruf"])){
            $jobID = $this->get("migrate_data.service")->migrateJob($oldPartnersOfMother["beruf"]);

            $partnerOfMother->setJob($jobID);
        }

        //belegt

        //$weddingOrder, $husband, $wife, $weddingDateid, $weddingLocationid, $weddingTerritoryid, $bannsDateid, $breakupReason, $breakupDateid, $marriageComment, $beforeAfter, $comment
        $this->get("migrate_data.service")->migrateWedding($oldPartnersOfMother['order2'], $newMother, $partnerOfMother, $oldPartnersOfMother['hochzeitstag'], $oldPartnersOfMother['hochzeitsort'], null, null, $oldPartnersOfMother['auflösung'], $oldPartnersOfMother['gelöst'], $oldPartnersOfMother['verheiratet'], $oldPartnersOfMother['vorher-nachher'], null);
    }

    private function getPartnersOfMotherWithNativeQuery($oldPersonID, $oldDBManager){
        $sql = "SELECT ID, `order`, order2, vornamen, name, verheiratet, hochzeitstag, hochzeitsort, 
        auflösung, gelöst, `vorher-nachher`, rang, beruf, stand, belegt, kommentar
        FROM `partner_der_mutter` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function migrateMarriagePartnersOfSibling($newSibling, $siblingOrder, $oldPersonID, $oldDBManager){
        $marriagePartnersOfSibling = $this->getMarriagePartnersOfSiblingWithNativeQuery($oldPersonID,$siblingOrder, $oldDBManager);

        for($i = 0; $i < count($marriagePartnersOfSibling); $i++){
            $oldMarriagePartnersOfSibling = $marriagePartnersOfSibling[$i];

            $newMarriagePartner = null;

            if(!is_null($oldMarriagePartnersOfSibling["geschwisterpartner_id-nr"])){
                //check it?
                $marriagePartnersOfSiblingOID = $oldMarriagePartnersOfSibling["geschwisterpartner_id-nr"];

                $marriagePartnersOfSiblingMainId = $this->getIDForOID($marriagePartnersOfSiblingOID, $oldDBManager);

                $newMarriagePartner = $this->migratePerson($marriagePartnersOfSiblingMainId, $marriagePartnersOfSiblingOID);

                $this->migrateWeddingOfSibling($newSibling, $newMarriagePartner, $oldMarriagePartnersOfSibling);
            }else{
                $newMarriagePartner = $this->createMarriagePartnersOfSibling($newSibling, $oldMarriagePartnersOfSibling);                 
            }

            $this->migrateChildrenOfSibling($newSibling, $siblingOrder, $newMarriagePartner, $oldMarriagePartnersOfSibling["order2"], $oldPersonID, $oldDBManager);
            //migrate children of marriage partner                 
        }
    }

    private function createMarriagePartnersOfSibling($newSibling, $oldMarriagePartnersOfSibling){
        $gender = $this->getOppositeGender($newSibling);
        $marriagePartnersOfSibling = $this->get("migrate_data.service")->migratePartner($oldMarriagePartnersOfSibling["vornamen"], $oldMarriagePartnersOfSibling["russ_vornamen"], $oldMarriagePartnersOfSibling["name"], $gender, null, $oldMarriagePartnersOfSibling["kommentar"]);

         //birth
        if(!is_null($oldMarriagePartnersOfSibling["herkunftsort"]) || 
            !is_null($oldMarriagePartnersOfSibling["geboren"])){
            //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
            $this->get("migrate_data.service")->migrateBirth($marriagePartnersOfSibling,null,null,$oldMarriagePartnersOfSibling["herkunftsort"],null, null,$oldMarriagePartnersOfSibling["geboren"]);
        }

        //death
        if(!is_null($oldMarriagePartnersOfSibling["gestorben"]) || 
            !is_null($oldMarriagePartnersOfSibling["friedhof"]) || 
            !is_null($oldMarriagePartnersOfSibling["begräbnisort"]) || 
            !is_null($oldMarriagePartnersOfSibling["begraben"])){
            //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
            $this->get("migrate_data.service")->migrateDeath($marriagePartnersOfSibling,null,$oldMarriagePartnersOfSibling["gestorben"], null, null, null, $oldMarriagePartnersOfSibling["friedhof"], $oldMarriagePartnersOfSibling["begräbnisort"], $oldMarriagePartnersOfSibling["begraben"]);
        }

       //religion
        if(!is_null($oldMarriagePartnersOfSibling["konfession"])){
            $this->get("migrate_data.service")->migrateReligion($marriagePartnersOfSibling,$oldMarriagePartnersOfSibling["konfession"], 1);
        }
       
        //status
        if(!is_null($oldMarriagePartnersOfSibling["stand"])){
            $this->get("migrate_data.service")->migrateStatus($marriagePartnersOfSibling,1, $oldMarriagePartnersOfSibling["stand"]);
        }

        //rank
        if(!is_null($oldMarriagePartnersOfSibling["rang"])){
            $this->get("migrate_data.service")->migrateRank($marriagePartnersOfSibling,1, $oldMarriagePartnersOfSibling["rang"]);
        }


        //job
        if(!is_null($oldMarriagePartnersOfSibling["beruf"])){
            $jobID = $this->get("migrate_data.service")->migrateJob($oldMarriagePartnersOfSibling["beruf"]);

            $marriagePartnersOfSibling->setJob($jobID);
        }

        //education
        if(!is_null($oldMarriagePartnersOfSibling["bildungsabschluss"])){
            //$educationOrder, $label, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $graduationLabel=null, $graduationDate=null, $graduationLocation=null, $comment=null
            $this->get("migrate_data.service")->migrateEducation($marriagePartnersOfSibling,1, null, null, null, null, null, null, null, $oldMarriagePartnersOfSibling["bildungsabschluss"]);
        }

        //honour
        if(!is_null($oldMarriagePartnersOfSibling["ehren"])){
            $this->get("migrate_data.service")->migrateHonour($marriagePartnersOfSibling,1, $oldMarriagePartnersOfSibling["ehren"]);
        }


        //verheiratet, 
        //hochzeitstag, hochzeitsort, auflösung,`vorher-nachher`,
        $this->migrateWeddingOfSibling($newSibling, $marriagePartnersOfSibling, $oldMarriagePartnersOfSibling);

        return $marriagePartnersOfSibling;
    }

    private function migrateWeddingOfSibling($newSibling, $marriagePartner, $oldMarriagePartnersOfSibling){
        //$weddingOrder, $husband, $wife, $weddingDateid, $weddingLocationid, $weddingTerritoryid, $bannsDateid, $breakupReason, $breakupDateid, $marriageComment, $beforeAfter, $comment
        $this->get("migrate_data.service")->migrateWedding($oldMarriagePartnersOfSibling['order2'], $newSibling, $marriagePartner, $oldMarriagePartnersOfSibling['hochzeitstag'], $oldMarriagePartnersOfSibling['hochzeitsort'], null, null, $oldMarriagePartnersOfSibling['auflösung'], null, $oldMarriagePartnersOfSibling['verheiratet'], $oldMarriagePartnersOfSibling['vorher-nachher'], null);
    }

    private function getMarriagePartnersOfSiblingWithNativeQuery($oldPersonID,$siblingOrder, $oldDBManager){
        $sql = "SELECT ID, `order`, order2, vornamen, russ_vornamen, name, geboren, herkunftsort, 
        gestorben, begraben, begräbnisort, friedhof, `geschwisterpartner_id-nr`, verheiratet, 
        hochzeitstag, hochzeitsort, auflösung, konfession, bildungsabschluss, stand, beruf, 
        ehren, rang, `vorher-nachher`, kommentar
            FROM `ehepartner_des_geschwisters` WHERE ID=:personID AND `order`=:siblingOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('siblingOrder', $siblingOrder);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function migrateChildrenOfSibling($newSibling, $siblingOrder, $newMarriagePartner, $marriageOrder, $oldPersonID, $oldDBManager){
        $childrenOfSibling = $this->getChildrenOfSiblingWithNativeQuery($oldPersonID, $siblingOrder, $marriageOrder, $oldDBManager);

        for($i = 0; $i < count($childrenOfSibling); $i++){
            $oldChildrenOfSibling = $childrenOfSibling[$i];
            $this->createChildrenOfSibling($oldChildrenOfSibling, $newSibling, $newMarriagePartner);                
        }
    }

    private function createChildrenOfSibling($oldChildOfSibling,$newSibling, $newMarriagePartner){
        $childOfSibling = $this->get("migrate_data.service")->migrateRelative($oldChildOfSibling["vornamen"],null, $oldChildOfSibling["name"], $oldChildOfSibling["geschlecht"], null, $oldChildOfSibling["kommentar"]);

         //birth
        if(!is_null($oldChildOfSibling["geburtsort"]) || 
            !is_null($oldChildOfSibling["geboren"])){
            //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
            $this->get("migrate_data.service")->migrateBirth($childOfSibling,null,null,null, null, $oldChildOfSibling["geburtsort"],$oldChildOfSibling["geboren"]);
        }

        //baptism
        if(!is_null($oldChildOfSibling["getauft"]) ||
            !is_null($oldChildOfSibling["taufort"])){
            $$this->get("migrate_data.service")->migrateBaptism($childOfSibling,$oldChildOfSibling["getauft"], $oldChildOfSibling["taufort"]);
        }

        //death
        if(!is_null($oldChildOfSibling["gestorben"])){
            //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
            $this->get("migrate_data.service")->migrateDeath($childOfSibling,null,$oldChildOfSibling["gestorben"]);
        }

        //job
        if(!is_null($oldChildOfSibling["beruf"])){
            $jobID = $this->get("migrate_data.service")->migrateJob($oldChildOfSibling["beruf"]);

            $childOfSibling->setJob($jobID);
        }

        $this->get("migrate_data.service")->migrateIsParent($childOfSibling, $newSibling);
        $this->get("migrate_data.service")->migrateIsParent($childOfSibling, $newMarriagePartner);
    }

    private function getChildrenOfSiblingWithNativeQuery($oldPersonID, $siblingOrder, $marriageOrder, $oldDBManager){
        $sql = "SELECT  ID, `order`, order2, order3, vornamen, name, geschlecht, geboren, 
        geburtsort, getauft, taufort, gestorben, beruf, kommentar
            FROM `geschwisterkind` WHERE ID=:personID AND `order`=:siblingOrder AND order2=:marriageOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('siblingOrder', $siblingOrder);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function migrateMarriagePartnersOfChildren($newPerson,$newChild,$parentMarriageOrder, $childOrder, $oldPersonID, $oldDBManager){
        $marriagePartnersOfChildren = $this->getMarriagePartnersOfChildrenWithNativeQuery($oldPersonID,$parentMarriageOrder,$childOrder, $oldDBManager);

        for($i = 0; $i < count($marriagePartnersOfChildren); $i++){
            $oldMarriagePartner = $marriagePartnersOfChildren[$i];

            $newMarriagePartner = null;

            //check if reference to person
            if(!is_null($oldMarriagePartner["kindespartner_id-nr"])){
                // think about special case 88558<->87465
                //check it?
                $marriagePartnerOID = $oldMarriagePartner["kindespartner_id-nr"];

                $marriagePartnerMainId = $this->getIDForOID($marriagePartnerOID, $oldDBManager);

                $newMarriagePartner = $this->migratePerson($marriagePartnerMainId, $marriagePartnerOID);

                $this->migrateWeddingOfChildren($newChild, $newMarriagePartner, $oldMarriagePartner);
            }else{
               $newMarriagePartner = $this->createMarriagePartnersOfChildren($newChild, $oldMarriagePartner);                
            }

            //other partners
            $this->migrateOtherPartnersOfChildren($newMarriagePartner,$parentMarriageOrder, $childOrder,$oldMarriagePartner['order3'], $oldPersonID, $oldDBManager);

            $this->migrateFatherInLawOfChildren($newChild,$newMarriagePartner,$parentMarriageOrder, $childOrder,$oldMarriagePartner['order3'], $oldPersonID, $oldDBManager);
            $this->migrateMotherInLawOfChildren($newChild,$newMarriagePartner,$parentMarriageOrder, $childOrder,$oldMarriagePartner['order3'], $oldPersonID, $oldDBManager);
        
            $this->migrateGrandchild($newPerson,$newChild,$newMarriagePartner,$parentMarriageOrder, $childOrder,$oldMarriagePartner['order3'], $oldPersonID, $oldDBManager);
        }
    }

    private function migrateWeddingOfChildren($newChild, $newMarriagePartner, $oldMarriagePartner){
        //$weddingOrder, $husband, $wife, $weddingDateid, $weddingLocationid, $weddingTerritoryid, $bannsDateid, $breakupReason, $breakupDateid, $marriageComment, $beforeAfter, $comment
        $this->get("migrate_data.service")->migrateWedding($oldMarriagePartner['order3'], $newChild, $newMarriagePartner, $oldMarriagePartner['hochzeitstag'], 
            $oldMarriagePartner['hochzeitsort'], null, $oldMarriagePartner['aufgebot'], 
            $oldMarriagePartner['auflösung'], $oldMarriagePartner['gelöst'], 
            $oldMarriagePartner['verheiratet'], null, null);
    }

    private function createMarriagePartnersOfChildren($newChild, $oldMarriagePartnerOfChild){
        $gender = $this->getOppositeGender($newChild);

        //$firstName, $patronym, $lastName, $gender, $nation, $comment
        $marriagePartnerOfChild = $this->get("migrate_data.service")->migratePartner($oldMarriagePartnerOfChild["vornamen"], $oldMarriagePartnerOfChild["russ_vornamen"], $oldMarriagePartnerOfChild["name"], $gender, $oldMarriagePartnerOfChild["nation"], $oldMarriagePartnerOfChild["kommentar"]);

        $marriagePartnerOfChild->setForeName($oldMarriagePartnerOfChild["rufnamen"]);

         //birth
        if(!is_null($oldMarriagePartnerOfChild["herkunftsort"]) || 
            !is_null($oldMarriagePartnerOfChild["herkunftsterritorium"]) || 
            !is_null($oldMarriagePartnerOfChild["geburtsort"]) || 
            !is_null($oldMarriagePartnerOfChild["geburtsterritorium"]) || 
            !is_null($oldMarriagePartnerOfChild["geboren"])){
            //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
            $this->get("migrate_data.service")->migrateBirth($marriagePartnerOfChild,null,$oldMarriagePartnerOfChild["herkunftsterritorium"],$oldMarriagePartnerOfChild["herkunftsort"],null, $oldMarriagePartnerOfChild["geburtsort"], $oldMarriagePartnerOfChild["geboren"], $oldMarriagePartnerOfChild["geburtsterritorium"]);
        }
 
        //death
        if(!is_null($oldMarriagePartnerOfChild["gestorben"]) || 
            !is_null($oldMarriagePartnerOfChild["friedhof"]) || 
            !is_null($oldMarriagePartnerOfChild["begräbnisort"]) || 
            !is_null($oldMarriagePartnerOfChild["todesort"])){
            //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
            $this->get("migrate_data.service")->migrateDeath($marriagePartnerOfChild,$oldMarriagePartnerOfChild["todesort"],$oldMarriagePartnerOfChild["gestorben"], null, null, null, $oldMarriagePartnerOfChild["friedhof"],$oldMarriagePartnerOfChild["begräbnisort"]);
        }

       
        //status
        if(!is_null($oldMarriagePartnerOfChild["stand"])){
            $this->get("migrate_data.service")->migrateStatus($marriagePartnerOfChild,1, $oldMarriagePartnerOfChild["stand"]);
        }

        //rank
        if(!is_null($oldMarriagePartnerOfChild["rang"])){
            $this->get("migrate_data.service")->migrateRank($marriagePartnerOfChild,1, $oldMarriagePartnerOfChild["rang"]);
        }


        //property
        if(!is_null($oldMarriagePartnerOfChild["besitz"])){
            $this->get("migrate_data.service")->migrateProperty($marriagePartnerOfChild,1, $oldMarriagePartnerOfChild["besitz"]);
        }


        //job
        if(!is_null($oldMarriagePartnerOfChild["beruf"])){
            $jobID = $this->get("migrate_data.service")->migrateJob($oldMarriagePartnerOfChild["beruf"]);

            $marriagePartnerOfChild->setJob($jobID);
        }

        //education
        if(!is_null($oldMarriagePartnerOfChild["bildungsabschluss"])){
            //$educationOrder, $label, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $graduationLabel=null, $graduationDate=null, $graduationLocation=null, $comment=null
            $this->get("migrate_data.service")->migrateEducation($marriagePartnerOfChild,1, null, null, null, null, null, null, null, $oldMarriagePartnerOfChild["bildungsabschluss"]);
        }

        $this->migrateWeddingOfChildren($newChild, $marriagePartnerOfChild, $oldMarriagePartnerOfChild);

        return $marriagePartnerOfChild;
    }

    private function getMarriagePartnersOfChildrenWithNativeQuery($oldPersonID,$parentMarriageOrder,$childOrder, $oldDBManager){
        $sql = "SELECT ID, `order`, order2, order3, vornamen, russ_vornamen, name, rufnamen, 
        nation, geboren, geburtsort, geburtsterritorium, herkunftsort, herkunftsterritorium, 
        gestorben, todesort, begräbnisort, friedhof, aufgebot, verheiratet, hochzeitstag, 
        hochzeitsort, auflösung, gelöst, bildungsabschluss, beruf, rang, stand, besitz, 
        `kindespartner_id-nr`, kommentar
        FROM `ehepartner_des_kindes` WHERE ID=:personID AND `order`=:parentMarriageOrder AND order2=:childOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('parentMarriageOrder', $parentMarriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function migrateOtherPartnersOfChildren($newMarriagePartner,$parentMarriageOrder, $childOrder,$childMarriageOrder, $oldPersonID, $oldDBManager){
        $otherPartnersOfChildren = $this->getOtherPartnersOfChildrenWithNativeQuery($oldPersonID,$parentMarriageOrder, $childOrder,$childMarriageOrder, $oldDBManager);

        for($i = 0; $i < count($otherPartnersOfChildren); $i++){
            $oldOtherPartnersOfChildren = $otherPartnersOfChildren[$i];
            $this->createOtherPartnersOfChildren($newMarriagePartner, $oldOtherPartnersOfChildren);                
        }
    }

    private function createOtherPartnersOfChildren($newMarriagePartner, $oldOtherPartnerOfChild){
        $gender = $this->getOppositeGender($newMarriagePartner);
        
        //$firstName, $patronym, $lastName, $gender, $nation, $comment
        $otherPartnerOfChild = $this->get("migrate_data.service")->migratePartner($oldOtherPartnerOfChild["vornamen"], null, $oldOtherPartnerOfChild["name"], $gender, null, $oldOtherPartnerOfChild["kommentar"]);

         //birth
        if(!is_null($oldOtherPartnerOfChild["geburtsort"]) || 
            !is_null($oldOtherPartnerOfChild["geboren"])){
            //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
            $this->get("migrate_data.service")->migrateBirth($otherPartnerOfChild,null,null,null,null, $oldOtherPartnerOfChild["geburtsort"], $oldOtherPartnerOfChild["geboren"]);
        }
 
        //death
        if(!is_null($oldOtherPartnerOfChild["gestorben"]) || 
            !is_null($oldOtherPartnerOfChild["todesort"])){
            //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
            $this->get("migrate_data.service")->migrateDeath($otherPartnerOfChild,$oldOtherPartnerOfChild["todesort"],$oldOtherPartnerOfChild["gestorben"]);
        }

        //rank
        if(!is_null($oldOtherPartnerOfChild["rang"])){
            $this->get("migrate_data.service")->migrateRank($otherPartnerOfChild,1, $oldOtherPartnerOfChild["rang"]);
        }

        $this->migrateWeddingOfOtherPartnersOfChild($newMarriagePartner, $otherPartnerOfChild, $oldOtherPartnerOfChild);
    }

    private function migrateWeddingOfOtherPartnersOfChild($newMarriagePartner, $otherPartnerOfChild, $oldOtherPartnerOfChild){
        //$weddingOrder, $husband, $wife, $weddingDateid, $weddingLocationid, $weddingTerritoryid, $bannsDateid, $breakupReason, $breakupDateid, $marriageComment, $beforeAfter, $comment
        $this->get("migrate_data.service")->migrateWedding($oldOtherPartnerOfChild['order4'], $newMarriagePartner, $otherPartnerOfChild, 
            $oldOtherPartnerOfChild['hochzeitstag'], $oldOtherPartnerOfChild['hochzeitsort'],null, 
            null, $oldOtherPartnerOfChild['auflösung'], null, 
            $oldOtherPartnerOfChild['verheiratet'], $oldOtherPartnerOfChild['vorher-nachher']);
    }

    private function getOtherPartnersOfChildrenWithNativeQuery($oldPersonID,$parentMarriageOrder, $childOrder,$childMarriageOrder, $oldDBManager){
        $sql = "SELECT ID, vornamen, name, `order`, order2, order3, order4, geboren, geburtsort, 
        gestorben, todesort, `vorher-nachher`, hochzeitstag, hochzeitsort, verheiratet, auflösung, 
        rang, kommentar
        FROM `anderer_partner_des_kindes` WHERE ID=:personID AND `order`=:parentMarriageOrder AND order2=:childOrder AND order3=:childMarriageOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('parentMarriageOrder', $parentMarriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->bindValue('childMarriageOrder', $childMarriageOrder);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function migrateFatherInLawOfChildren($newChild,$newMarriagePartner,$parentMarriageOrder, $childOrder,$childMarriageOrder, $oldPersonID, $oldDBManager){
        $fatherInLawOfChildren = $this->getFatherInLawOfChildrenWithNativeQuery($oldPersonID,$parentMarriageOrder, $childOrder,$childMarriageOrder, $oldDBManager);

        for($i = 0; $i < count($fatherInLawOfChildren); $i++){
            $oldFatherInLawOfChildren = $fatherInLawOfChildren[$i];
            $this->createFatherInLawOfChildren($newChild,$newMarriagePartner, $oldFatherInLawOfChildren);                
        }
    }

    private function createFatherInLawOfChildren($newChild,$newMarriagePartner, $oldFatherInLawOfChild){
        $gender = "männlich";
        //$firstName, $patronym, $lastName, $gender, $nation, $comment
        $fatherInLawOfChild = $this->get("migrate_data.service")->migrateRelative($oldFatherInLawOfChild["vornamen"], null, $oldFatherInLawOfChild["name"], $gender);

        //rank
        if(!is_null($oldFatherInLawOfChild["rang"])){
            $this->get("migrate_data.service")->migrateRank($fatherInLawOfChild,1, $oldFatherInLawOfChild["rang"]);
        }

        //job
        if(!is_null($oldFatherInLawOfChild["beruf"])){
            $jobID = $this->get("migrate_data.service")->migrateJob($oldFatherInLawOfChild["beruf"]);

            $fatherInLawOfChild->setJob($jobID);
        }

        //born_in_marriage
        if(!is_null($oldFatherInLawOfChild["ehelich"])){
            $fatherInLawOfChild->setBornInMarriage($oldFatherInLawOfChild["ehelich"]);
        }

        if(!is_null($oldFatherInLawOfChild["wohnort"])){
            $this->get("migrate_data.service")->migrateResidence($fatherInLawOfChild,1,null,null,$oldFatherInLawOfChild["wohnort"]);
        }

        $this->get("migrate_data.service")->migrateIsParent($newMarriagePartner, $fatherInLawOfChild);
        $this->get("migrate_data.service")->migrateIsParentInLaw($newChild, $fatherInLawOfChild);
    }

    private function getFatherInLawOfChildrenWithNativeQuery($oldPersonID,$parentMarriageOrder, $childOrder,$childMarriageOrder, $oldDBManager){
        $sql = "SELECT ID, `order`, order2, order3, order4, vornamen, name, ehelich, beruf, rang, wohnort
        FROM `schwiegervater_des_kindes` WHERE ID=:personID AND `order`=:parentMarriageOrder AND order2=:childOrder AND order3=:childMarriageOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('parentMarriageOrder', $parentMarriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->bindValue('childMarriageOrder', $childMarriageOrder);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function migrateMotherInLawOfChildren($newChild,$newMarriagePartner,$parentMarriageOrder, $childOrder,$childMarriageOrder, $oldPersonID, $oldDBManager){
        $motherInLawOfChildren = $this->getMotherInLawOfChildrenWithNativeQuery($oldPersonID,$parentMarriageOrder, $childOrder,$childMarriageOrder, $oldDBManager);

        for($i = 0; $i < count($motherInLawOfChildren); $i++){
            $oldMotherInLawOfChildren = $motherInLawOfChildren[$i];
            $this->createMotherInLawOfChildren($newChild,$newMarriagePartner, $oldMotherInLawOfChildren);                
        }
    }

    private function createMotherInLawOfChildren($newChild,$newMarriagePartner, $oldMotherInLawOfChild){
        $gender = "weiblich";
        //$firstName, $patronym, $lastName, $gender, $nation, $comment
        $motherInLawOfChild = $this->get("migrate_data.service")->migrateRelative($oldMotherInLawOfChild["vornamen"], null, $oldMotherInLawOfChild["name"], $gender);

        //born_in_marriage
        if(!is_null($oldMotherInLawOfChild["ehelich"])){
            $motherInLawOfChild->setBornInMarriage($oldMotherInLawOfChild["ehelich"]);
        }

        $this->get("migrate_data.service")->migrateIsParent($newMarriagePartner, $motherInLawOfChild);
        $this->get("migrate_data.service")->migrateIsParentInLaw($newChild, $motherInLawOfChild);
    }

    private function getMotherInLawOfChildrenWithNativeQuery($oldPersonID,$parentMarriageOrder, $childOrder,$childMarriageOrder, $oldDBManager){
        $sql = "SELECT ID, `order`, order2, order3, order4, vornamen, name, ehelich
        FROM `schwiegermutter_des_kindes` WHERE ID=:personID AND `order`=:parentMarriageOrder AND order2=:childOrder AND order3=:childMarriageOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('parentMarriageOrder', $parentMarriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->bindValue('childMarriageOrder', $childMarriageOrder);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function migrateMotherOfSibling($newSibling,$siblingOrder, $oldPersonID, $oldDBManager){
        $motherOfSibling = $this->getMotherOfSiblingWithNativeQuery($oldPersonID,$siblingOrder, $oldDBManager);

        for($i = 0; $i < count($motherOfSibling); $i++){
            $oldMotherOfSibling = $motherOfSibling[$i];
            $this->createMotherOfSibling($newSibling, $oldMotherOfSibling);                
        }
    }

    private function createMotherOfSibling($newSibling, $oldMotherOfSibling){
        $gender = "weiblich";
        //$firstName, $patronym, $lastName, $gender, $nation, $comment
        $motherOfSibling = $this->get("migrate_data.service")->migrateRelative($oldMotherOfSibling["vornamen"], null, $oldMotherOfSibling["name"], $gender);

        //birth
        if(!is_null($oldMotherOfSibling["geboren"])){
            //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
            $this->get("migrate_data.service")->migrateBirth($motherOfSibling,null,null,null,null, null, $oldMotherOfSibling["geboren"]);
        }
 
        //death
        if(!is_null($oldMotherOfSibling["gestorben"])){
            //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
            $this->get("migrate_data.service")->migrateDeath($motherOfSibling,null,$oldMotherOfSibling["gestorben"]);
        }

        //born_in_marriage
        if(!is_null($oldMotherOfSibling["ehelich"])){
            $motherOfSibling->setBornInMarriage($oldMotherOfSibling["ehelich"]);
        }

        $this->get("migrate_data.service")->migrateIsParent($newSibling, $motherOfSibling);
    }

    private function getMotherOfSiblingWithNativeQuery($oldPersonID,$siblingOrder, $oldDBManager){
        $sql = "SELECT ID, `order`, order2, vornamen, name, geboren, gestorben, ehelich
        FROM `mutter_des_geschwisters` WHERE ID=:personID AND `order`=:siblingOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('siblingOrder', $siblingOrder);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function migrateFatherOfSibling($newSibling,$siblingOrder, $oldPersonID, $oldDBManager){
        $fatherOfSibling = $this->getFatherOfSiblingWithNativeQuery($oldPersonID,$siblingOrder, $oldDBManager);

        for($i = 0; $i < count($fatherOfSibling); $i++){
            $oldFatherOfSibling = $fatherOfSibling[$i];
            if(!is_null($oldFatherOfSibling["geschwistervater_id-nr"])){
                //check it?
                $fatherOfSiblingOID = $oldFatherOfSibling["geschwistervater_id-nr"];

                $fatherOfSiblingMainId = $this->getIDForOID($fatherOfSiblingOID, $oldDBManager);

                $fatherOfSibling = $this->migratePerson($fatherOfSiblingMainId, $fatherOfSiblingOID);

                $this->get("migrate_data.service")->migrateIsParent($newSibling, $fatherOfSibling);     
            }else{
                //not happening                
            }
        }
    }

    private function getFatherOfSiblingWithNativeQuery($oldPersonID,$siblingOrder, $oldDBManager){
        $sql = "SELECT `order`,`order2`,`geschwistervater_id-nr` FROM `vater des geschwisters`
             WHERE ID=:personID AND `order`=:siblingOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('siblingOrder', $siblingOrder);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function migrateGrandchild($newPerson,$newChild,$newMarriagePartner,$parentMarriageOrder, $childOrder,$childMarriageOrder, $oldPersonID, $oldDBManager){
        //
        $grandchildren = $this->getGrandchildWithNativeQuery($oldPersonID,$parentMarriageOrder, $childOrder,$childMarriageOrder, $oldDBManager);

        for($i = 0; $i < count($grandchildren); $i++){
            $oldGrandchild = $grandchildren[$i];

            //check if reference to person
            if(!is_null($oldGrandchild["enkel_id-nr"])){
                //check it?
                $grandchildsOID = $oldGrandchild["enkel_id-nr"];

                $grandchildsMainId = $this->getIDForOID($grandchildsOID, $oldDBManager);

                $newGrandchild = $this->migratePerson($grandchildsMainId, $grandchildsOID);

                $paternal = true;

                $this->get("migrate_data.service")->migrateIsGrandparent($newGrandchild, $newPerson, $paternal,$oldGrandchild["kommentar"]);

                $this->addSecondGrandparentToChild($newPerson, $newGrandchild, $oldGrandchild);

                $this->get("migrate_data.service")->migrateIsParent($newGrandchild, $newChild,$oldGrandchild["kommentar"]);
                $this->get("migrate_data.service")->migrateIsParent($newGrandchild, $newMarriagePartner,$oldGrandchild["kommentar"]);
            }else{
                $this->createGrandchild($newPerson,$newChild,$newMarriagePartner, $oldGrandchild, $oldPersonID, $oldDBManager);                
            }
        }

    }

    private function createGrandchild($newPerson,$newChild,$newMarriagePartner, $oldGrandchild){
        //$firstName, $patronym, $lastName, $gender, $nation, $comment
        $grandchild = $this->get("migrate_data.service")->migrateRelative($oldGrandchild["vornamen"], $oldGrandchild["russ_vornamen"], $oldGrandchild["name"], $oldGrandchild["geschlecht"], null, $oldGrandchild["kommentar"]);

        $grandchild->setForeName($oldGrandchild["rufnamen"]);

         //birth
        if(!is_null($oldGrandchild["geburtsort"]) || 
            !is_null($oldGrandchild["geboren"])){
            //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
            $this->get("migrate_data.service")->migrateBirth($grandchild,null,null,null,null, $oldGrandchild["geburtsort"], $oldGrandchild["geboren"]);
        }
 
        //death
        if(!is_null($oldGrandchild["gestorben"])){
            //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
            $this->get("migrate_data.service")->migrateDeath($grandchild,null,$oldGrandchild["gestorben"]);
        }

       
        //status
        if(!is_null($oldGrandchild["stand"])){
            $this->get("migrate_data.service")->migrateStatus($grandchild,1, $oldGrandchild["stand"]);
        }

        //rank
        if(!is_null($oldGrandchild["rang"])){
            $this->get("migrate_data.service")->migrateRank(1, $oldGrandchild["rang"]);
        }


        //property
        if(!is_null($oldGrandchild["besitz"])){
            $this->get("migrate_data.service")->migrateProperty($grandchild,1, $oldGrandchild["besitz"]);
        }


        //job
        if(!is_null($oldGrandchild["beruf"])){
            $jobID = $this->get("migrate_data.service")->migrateJob($oldGrandchild["beruf"]);

            $grandchild->setJob($jobID);
        }

        //education
        if(!is_null($oldGrandchild["bildungsabschluss"])){
            //$educationOrder, $label, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $graduationLabel=null, $graduationDate=null, $graduationLocation=null, $comment=null
            $this->get("migrate_data.service")->migrateEducation($grandchild,1, null, null, null, null, null, null, null, $oldGrandchild["bildungsabschluss"]);
        }

        if(!is_null($oldGrandchild["wohnort"])){
            $this->get("migrate_data.service")->migrateResidence($grandchild,1,null,null,$oldGrandchild["wohnort"]);
        }

        $paternal = true;

        $this->get("migrate_data.service")->migrateIsGrandparent($grandchild, $newPerson, $paternal);

        $this->addSecondGrandparentToChild($newPerson, $grandchild, $oldGrandchild);

        $this->get("migrate_data.service")->migrateIsParent($grandchild, $newChild);
        $this->get("migrate_data.service")->migrateIsParent($grandchild, $newMarriagePartner);
    }

    private function addSecondGrandparentToChild($newPerson, $grandchild, $oldGrandchild){
        
    }

    private function getGrandchildWithNativeQuery($oldPersonID,$parentMarriageOrder, $childOrder,$childMarriageOrder, $oldDBManager){
        $sql = "SELECT ID,`order`, order2, order3, order4, vornamen, russ_vornamen, name, rufnamen, 
        geschlecht, `enkel_id-nr`, geboren, geburtsort, 
        gestorben, bildungsabschluss, beruf, wohnort, besitz, stand, rang, kommentar
                    FROM `enkelkind` WHERE ID=:personID AND `order`=:parentMarriageOrder AND order2=:childOrder AND order3=:childMarriageOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('parentMarriageOrder', $parentMarriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->bindValue('childMarriageOrder', $childMarriageOrder);
        $stmt->execute();


        return $stmt->fetchAll();
    }


}
