<?php

namespace UR\AmburgerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
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

        $religionId = null;

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
}
