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


        $deathId = $this->migrateDeathController($ID, $oldDBManager);

        $birthId = $this->migrateBirthController($ID, $oldDBManager);

        $baptismId = $this->migrateBaptismController($ID, $oldDBManager);

        $religionId = $this->migrateReligionController($ID, $oldDBManager);

        $originalNationid = null;

        
        /*
        $newQuelleId = $this->get("migrate_data.service")->migrateQuelle($quelle->getBezeichnung(), $quelle->getFundstelle(), $quelle->getBemerkung(), $quelle->getKommentar());

        $newWerkeId = $this->get("migrate_data.service")->migrateWerke($werke->getKommentar());
        //Werke hat kein Label in der alten DB???????

        $newId = $this->get("migrate_data.service")->migrate($->(), $->getKommentar());
        */



        $newPersonId = $this->get("migrate_data.service")->migratePerson($IDData->getOid(), $person->getVornamen(), $person->getRussVornamen(), $person->getName(), $person->getRufnamen(),$person->getGeburtsname(), $person->getGeschlecht(), $birthId, $deathId, $religionId, $originalNationid, $person->getKommentar(), $baptismId);



        return new Response(
            'Migrated Database entry: '.$newPersonId
        );
    }

    private function migrateDeathController($oldPersonID, $oldDBManager){

        $tod = $oldDBManager->getRepository('OldBundle:Tod')->findOneById($oldPersonID);

        //if necessary get more informations from other tables

        $newTodId = $this->get("migrate_data.service")->migrateDeath($tod->getTodesort(), $tod->getGestorben(), $tod->getTodesland(), $tod->getTodesursache(), $tod->getTodesterritorium(), $tod->getFriedhof(), $tod->getBegräbnisort(), $tod->getBegraben(), $tod->getKommentar());

        return $newTodId;
    }

    private function migrateBirthController($oldPersonID, $oldDBManager){

        $birth = $oldDBManager->getRepository('OldBundle:Herkunft')->findOneById($oldPersonID);

        //if necessary get more informations from other tables

        $newBirthId = $this->get("migrate_data.service")->migrateBirth($birth->getHerkunftsland(),$birth->getHerkunftsterritorium(),$birth->getHerkunftsort(),$birth->getGeburtsland(),$birth->getGeburtsort(),$birth->getGeboren(),$birth->getGeburtsterritorium(),$birth->getKommentar());

        return $newBirthId;
    }


    private function migrateBaptismController($oldPersonID, $oldDBManager){

        $birth = $oldDBManager->getRepository('OldBundle:Herkunft')->findOneById($oldPersonID);

        //if necessary get more informations from other tables

        $newBaptismId = $this->get("migrate_data.service")->migrateBaptism($birth->getGetauft(),$birth->getTaufort());

        return $newBaptismId;
    }

        private function migrateReligionController($oldPersonID, $oldDBManager){

        //find by id because there can be multiple religion for one person
        //$religions = $oldDBManager->getRepository('OldBundle:Religion')->findById($oldPersonID);
        $religions = $this->getReligionDataWithNativeQuery($oldPersonID, $oldDBManager);

        //if necessary get more informations from other tables

        $religionIDString = "";

        for($i = 0; $i < count($religions); $i++){
            $oldReligion = $religions[$i];
            $newReligionID = $this->get("migrate_data.service")->migrateReligion($oldReligion->getKonfession(), $oldReligion->getOrder(), $oldReligion->getKonversion(), $oldReligion->getBelegt(), $oldReligion->getVonAb(), $oldReligion->getKommentar());

            if($i != 0){
                $religionIDString .= ",";
            }

            $religionIDString .= $newReligionID;
        }


        return $religionIDString;
    }

    /*
        Documentation:
        http://forum.symfony-project.org/forum/23/topic/37872.html

        https://stackoverflow.com/questions/3325012/execute-raw-sql-using-doctrine-2

        http://doctrine-orm.readthedocs.org/projects/doctrine-orm/en/latest/reference/native-sql.html
    */

    private function getReligionDataWithNativeQuery($oldPersonID, $oldDBManager){
        $resultMap = new ResultSetMapping;
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

        return $query->getResult();
    }
}
