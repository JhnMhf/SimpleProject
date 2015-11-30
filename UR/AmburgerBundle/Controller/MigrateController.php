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


        $deathId = $this->migrateDeath($ID, $oldDBManager);

        $birthId = null;

        $religionId = null;

        $originalNationid = null;

        
        /*
        $newQuelleId = $this->get("migrate_data.service")->migrateQuelle($quelle->getBezeichnung(), $quelle->getFundstelle(), $quelle->getBemerkung(), $quelle->getKommentar());

        $newWerkeId = $this->get("migrate_data.service")->migrateWerke($werke->getKommentar());
        //Werke hat kein Label in der alten DB???????

        $newId = $this->get("migrate_data.service")->migrate($->(), $->getKommentar());
        */



        $newPersonId = $this->get("migrate_data.service")->migratePerson($IDData->getOid(), $person->getVornamen(), $person->getRussVornamen(), $person->getName(), $person->getRufnamen(),$person->getGeburtsname(), $person->getGeschlecht(), $birthId, $deathId, $religionId, $originalNationid, $person->getKommentar());



        return new Response(
            'Migrated Database entry: '.$newPersonId
        );
    }

    private function migrateDeath($oldPersonID, $oldDBManager){

        $tod = $oldDBManager->getRepository('OldBundle:Tod')->findOneById($oldPersonID);

        //if necessary get more informations from other tables

        $newTodId = $this->get("migrate_data.service")->migrateDeath($tod->getTodesort(), $tod->getGestorben(), $tod->getTodesland(), $tod->getTodesursache(), $tod->getTodesterritorium(), $tod->getFriedhof(), $tod->getBegräbnisort(), $tod->getBegraben(), $tod->getKommentar());

        return $newTodId;
    }


}
