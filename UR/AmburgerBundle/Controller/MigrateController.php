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

        $newPerson = $this->get("migrate_data.service")->migratePerson($IDData->getOid(), $person->getVornamen(), $person->getRussVornamen(), $person->getName(), $person->getRufnamen(),$person->getGeburtsname(), $person->getGeschlecht(), $person->getKommentar());

        $this->migrateBirthController($newPerson, $ID, $oldDBManager);

        $this->migrateBaptismController($newPerson, $ID, $oldDBManager);

        $this->migrateDeathController($newPerson, $ID, $oldDBManager);

        $this->migrateReligionController($newPerson, $ID, $oldDBManager);

        $this->migrateOriginalNation($newPerson, $person);
        
        /*
        $newQuelleId = $this->get("migrate_data.service")->migrateQuelle($quelle->getBezeichnung(), $quelle->getFundstelle(), $quelle->getBemerkung(), $quelle->getKommentar());

        $newWerkeId = $this->get("migrate_data.service")->migrateWerke($werke->getKommentar());
        //Werke hat kein Label in der alten DB???????

        $newId = $this->get("migrate_data.service")->migrate($->(), $->getKommentar());
        */

        $this->get("migrate_data.service")->savePerson($newPerson);

        return new Response(
            'Migrated Database entry: '.$newPerson->getId()
        );
    }

    private function migrateDeathController($newPerson, $oldPersonID, $oldDBManager){

        $tod = $oldDBManager->getRepository('OldBundle:Tod')->findOneById($oldPersonID);

        //if necessary get more informations from other tables
        if(!is_null($tod)){
            $newTodId = $this->get("migrate_data.service")->migrateDeath($newPerson, $tod->getTodesort(), $tod->getGestorben(), $tod->getTodesland(), $tod->getTodesursache(), $tod->getTodesterritorium(), $tod->getFriedhof(), $tod->getBegräbnisort(), $tod->getBegraben(), $tod->getKommentar());
        }
    }

    private function migrateBirthController($newPerson, $oldPersonID, $oldDBManager){

        $birth = $oldDBManager->getRepository('OldBundle:Herkunft')->findOneById($oldPersonID);

        //if necessary get more informations from other tables


        if(!is_null($birth)){
            $newBirthId = $this->get("migrate_data.service")->migrateBirth($newPerson, $birth->getHerkunftsland(),$birth->getHerkunftsterritorium(),$birth->getHerkunftsort(),$birth->getGeburtsland(),$birth->getGeburtsort(),$birth->getGeboren(),$birth->getGeburtsterritorium(),$birth->getKommentar());
        }
    }


    private function migrateBaptismController($newPerson, $oldPersonID, $oldDBManager){

        $birth = $oldDBManager->getRepository('OldBundle:Herkunft')->findOneById($oldPersonID);

        //if necessary get more informations from other tables
        if(!is_null($birth) && (!is_null($birth->getGetauft()) || !is_null($birth->getTaufort()))){
            $newBaptismId = $this->get("migrate_data.service")->migrateBaptism($newPerson, $birth->getGetauft(),$birth->getTaufort());
        }
    }

    private function migrateReligionController($newPerson, $oldPersonID, $oldDBManager){
        //find by id because there can be multiple religion for one person
        //$religions = $oldDBManager->getRepository('OldBundle:Religion')->findById($oldPersonID);
        $religions = $this->getReligionDataWithNativeQuery($oldPersonID, $oldDBManager);

        //if necessary get more informations from other tables

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
            $this->get("migrate_data.service")->migrateOriginalNation($newPerson, $person->getUrspNation(), "");
        }
    }
}
