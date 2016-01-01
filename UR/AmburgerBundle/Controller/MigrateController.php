<?php

namespace UR\AmburgerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Query\ResultSetMapping;

use UR\DB\OldDBBundle\Entity\Person;
use UR\DB\NewDBBundle\Utils\MigrateData;


class MigrateController extends Controller
{
    public function personAction($ID)
    {
        // get old data
    	$oldDBManager = $this->get('doctrine')->getManager('old');

    	$person = $oldDBManager->getRepository('OldBundle:Person')->findOneById($ID);

        if(!$person){
        	return new Response("Invalid ID");
        }

        $IDData = $oldDBManager->getRepository('OldBundle:Ids')->findOneById($ID);

        $OID = $IDData->getOid();

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

        $this->migrateGrandmothers($newPerson, $ID, $oldDBManager);

        return new Response(
            'Migrated Database entry: '.$newPerson->getId()
        );
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
            $grandmother = $this->get("migrate_data.service")->migrateRelative($oldGrandmother->getVornamen(), null, $oldGrandmother->getName(), "weiblich", $oldGrandmother->getNation(), null);

            //insert additional data

            $this->get("migrate_data.service")->migrateIsGrandparent($newPerson->getId(), $grandmother->getId(), false, "dem sei groaßmudda", null);
        }

        //paternal
    }
}
