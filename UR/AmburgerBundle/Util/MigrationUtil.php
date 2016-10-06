<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UR\AmburgerBundle\Util;

use UR\AmburgerBundle\Exception;

/**
 * Description of MigrationUtil
 *
 * @author johanna
 */
class MigrationUtil {

    private $LOGGER;
    private $container;
    private $migrationService;
    private $normalizationService;

    public function __construct($container) {
        $this->container = $container;
    }

    private function get($identifier) {
        return $this->container->get($identifier);
    }

    private function getMigrationService() {
        if (is_null($this->migrationService)) {
            $this->migrationService = $this->get("migrate_data.service");
        }

        return $this->migrationService;
    }

    private function getNormalizationService() {
        if (is_null($this->normalizationService)) {
            $this->normalizationService = $this->get("normalization.service");
        }

        return $this->normalizationService;
    }

    private function getLogger() {
        if (is_null($this->LOGGER)) {
            $this->LOGGER = $this->get('monolog.logger.migrateOld');
        }

        return $this->LOGGER;
    }

    private function getIDForOID($OID, $oldDBManager) {

        $IDData = $oldDBManager->getRepository('OldBundle:Ids')->findOneByOid($OID);
        
        if(!is_null($IDData)){
            return $IDData->getId();
        }

        throw new \UR\AmburgerBundle\Exception\NotFoundException("Could not find ID for OID: ".$OID, 404);
    }

    public function migratePerson($ID, $OID = null) {
        $this->getLogger()->info("Migrating Person with ID: " . $ID);
        // get old data
        $oldDBManager = $this->get('doctrine')->getManager('old');

        $person = $oldDBManager->getRepository('OldBundle:Person')->findOneById($ID);

        if (!$person) {
            $this->getLogger()->error("Could not find person with ID: " . $ID);
            return null;
        }

        if (is_null($OID)) {
            $this->getLogger()->info("Loading oid for ID: " . $ID);
            $IDData = $oldDBManager->getRepository('OldBundle:Ids')->findOneById($ID);

            $OID = $IDData->getOid();
        }


        $this->getLogger()->info("Checking if person already exists for OID: " . $OID);
        $existingPerson = $this->getMigrationService()->getNewPersonForOid($OID);

        if (!is_null($existingPerson)) {
            $this->getLogger()->info("Person already exists, returning existing person " . $existingPerson);
            //person already migrated, so just return it
            return $existingPerson;
        }
        $this->getLogger()->info("Person does not already exists... migrating person.");

        $newPerson = $this->getMigrationService()->migratePerson($OID, $person->getVornamen(), $person->getRussVornamen(), $person->getName(), $person->getRufnamen(), $person->getGeburtsname(), $person->getGeschlecht(), $person->getBerufsklasse(), $person->getKommentar());

        $this->migrateDataFromIndexID($newPerson, $ID, $oldDBManager);

        $this->migrateBirthController($newPerson, $ID, $oldDBManager);

        $this->migrateBaptismController($newPerson, $ID, $oldDBManager);

        $this->migrateDeathController($newPerson, $ID, $oldDBManager);

        $this->migrateReligionController($newPerson, $ID, $oldDBManager);

        $this->migrateNation($newPerson, $person);

        $this->migrateSource($newPerson, $ID, $oldDBManager);

        $this->migrateWorks($newPerson, $ID, $oldDBManager);

        $this->migrateHonour($newPerson, $ID, $oldDBManager);

        $this->migrateProperty($newPerson, $ID, $oldDBManager);

        $this->migrateRank($newPerson, $ID, $oldDBManager);

        $this->migrateEducation($newPerson, $ID, $oldDBManager);

        $this->migrateStatus($newPerson, $ID, $oldDBManager);

        $this->migrateRoadOfLife($newPerson, $ID, $oldDBManager);


        //save updated newPerson to database

        $this->getMigrationService()->saveObject($newPerson);


        //migrate relations (including data about relatives)
        $mother = $this->migrateMother($newPerson, $ID, $oldDBManager);

        $this->migrateFather($newPerson, $ID, $mother, $oldDBManager);

        $this->migrateSibling($newPerson, $ID, $oldDBManager);

        $this->migrateMarriagePartner($newPerson, $ID, $oldDBManager);

        // migrate GrandChild after marriagepartners of child

        $this->LOGGER->info("Saving the person at the end again: " . $newPerson);
        $this->getMigrationService()->saveObject($newPerson);

        return $newPerson;
    }

    private function migrateDeathController($newPerson, $oldPersonID, $oldDBManager) {

        $tod = $oldDBManager->getRepository('OldBundle:Tod')->findOneById($oldPersonID);

        //if necessary get more informations from other tables
        if (!is_null($tod)) {
            $this->getMigrationService()->migrateDeath($newPerson, $tod->getTodesort(), $tod->getGestorben(), $tod->getTodesland(), $tod->getTodesursache(), $tod->getTodesterritorium(), $tod->getFriedhof(), $tod->getBegräbnisort(), $tod->getBegraben(), $tod->getKommentar());
        }
    }

    private function migrateBirthController($newPerson, $oldPersonID, $oldDBManager) {

        $birth = $oldDBManager->getRepository('OldBundle:Herkunft')->findOneById($oldPersonID);

        //if necessary get more informations from other tables
        if (!is_null($birth)) {
            if ($birth->getHerkunftsland() != null ||
                    $birth->getHerkunftsterritorium() != null ||
                    $birth->getHerkunftsort() != null ||
                    $birth->getGeburtsland() != null ||
                    $birth->getGeburtsort() != null ||
                    $birth->getGeboren() != null ||
                    $birth->getGeburtsterritorium() != null ||
                    $birth->getKommentar() != null) {

                $this->getMigrationService()->migrateBirth($newPerson, $birth->getHerkunftsland(), $birth->getHerkunftsterritorium(), $birth->getHerkunftsort(), $birth->getGeburtsland(), $birth->getGeburtsort(), $birth->getGeboren(), $birth->getGeburtsterritorium(), $birth->getKommentar());
            }
        }
    }

    private function migrateBaptismController($newPerson, $oldPersonID, $oldDBManager) {

        $birth = $oldDBManager->getRepository('OldBundle:Herkunft')->findOneById($oldPersonID);

        //if necessary get more informations from other tables
        if (!is_null($birth) && (!is_null($birth->getGetauft()) || !is_null($birth->getTaufort()))) {
            $this->getMigrationService()->migrateBaptism($newPerson, $birth->getGetauft(), $birth->getTaufort());
        }
    }

    private function migrateReligionController($newPerson, $oldPersonID, $oldDBManager) {
        //find by id because there can be multiple religion for one person
        //$religions = $oldDBManager->getRepository('OldBundle:Religion')->findById($oldPersonID);
        $religions = $this->getReligionDataWithNativeQuery($oldPersonID, $oldDBManager);

        if (count($religions) > 0) {
            for ($i = 0; $i < count($religions); $i++) {
                $oldReligion = $religions[$i];
                $this->getMigrationService()->migrateReligion($newPerson, $oldReligion["konfession"], $oldReligion["order"], $oldReligion["konversion"], $oldReligion["belegt"], $oldReligion["von-ab"], $oldReligion["kommentar"]);
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

    private function getReligionDataWithNativeQuery($oldPersonID, $oldDBManager) {
        $sql = "SELECT ID, `order`, `von-ab`, konfession, konversion, belegt, kommentar FROM `religion` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function migrateNation($newPerson, $person) {

        if (!is_null($person->getUrspNation())) {
            $nationId = $this->getMigrationService()->migrateNation($person->getUrspNation(), "");

            $newPerson->setNation($nationId);
        }
    }

    private function migrateSource($newPerson, $oldPersonID, $oldDBManager) {

        $sources = $this->getSourcesWithNativeQuery($oldPersonID, $oldDBManager);

        if (count($sources) > 0) {
            for ($i = 0; $i < count($sources); $i++) {
                $quelle = $sources[$i];
                $this->getMigrationService()->migrateSource($newPerson, $quelle["order"], $quelle["bezeichnung"], $quelle["fundstelle"], $quelle["bemerkung"], $quelle["kommentar"]);
            }
        }
    }

    private function getSourcesWithNativeQuery($oldPersonID, $oldDBManager) {
        $sql = "SELECT ID, `order`, bezeichnung, fundstelle, bemerkung, kommentar FROM `quelle` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function migrateWorks($newPerson, $oldPersonID, $oldDBManager) {

        $works = $this->getWorksWithNativeQuery($oldPersonID, $oldDBManager);

        if (count($works) > 0) {
            for ($i = 0; $i < count($works); $i++) {
                $work = $works[$i];
                $this->getMigrationService()->migrateWork($newPerson, $work['werke'], $work['order'], $work['land'], $work['ort'], $work['von-ab'], $work['bis'], $work['territorium'], $work['belegt'], $work['kommentar']);
            }
        }
    }

    private function getWorksWithNativeQuery($oldPersonID, $oldDBManager) {
        $sql = "SELECT `ID`, `order`, `land`, `werke`, `ort`, `von-ab`, `bis`, `belegt`, `kommentar`, `territorium` FROM `werke` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function migrateDataFromIndexID($newPerson, $oldPersonID, $oldDBManager) {

        $idData = $oldDBManager->getRepository('OldBundle:Ids')->findOneById($oldPersonID);

        $newPerson->setComplete($idData->getVollständig());
        $newPerson->setControl($idData->getKontrolle());
    }

    private function migrateHonour($newPerson, $oldPersonID, $oldDBManager) {

        $honours = $this->getHonourWithNativeQuery($oldPersonID, $oldDBManager);

        if (count($honours) > 0) {
            for ($i = 0; $i < count($honours); $i++) {
                $honour = $honours[$i];
                $this->getMigrationService()->migrateHonour($newPerson, $honour["order"], $honour["ehren"], $honour["land"], $honour["territorium"], $honour["ort"], $honour["von-ab"], $honour["bis"], $honour["belegt"], $honour["kommentar"]);
            }
        }
    }

    private function getHonourWithNativeQuery($oldPersonID, $oldDBManager) {
        $sql = "SELECT ID, `order`, ort, territorium, land, ehren, `von-ab`, bis, belegt, kommentar FROM `ehren` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function migrateProperty($newPerson, $oldPersonID, $oldDBManager) {

        $properties = $this->getPropertyWithNativeQuery($oldPersonID, $oldDBManager);

        if (count($properties) > 0) {
            for ($i = 0; $i < count($properties); $i++) {
                $property = $properties[$i];
                $propertyID = $this->getMigrationService()->migrateProperty($newPerson, $property["order"], $property["besitz"], $property["land"], $property["territorium"], $property["ort"], $property["von-ab"], $property["bis"], $property["belegt"], $property["kommentar"]);
            }
        }
    }

    private function getPropertyWithNativeQuery($oldPersonID, $oldDBManager) {
        $sql = "SELECT ID, `order`, land, ort, territorium, besitz, `von-ab`, bis, belegt, kommentar FROM `besitz` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function migrateRank($newPerson, $oldPersonID, $oldDBManager) {

        $ranks = $this->getRankWithNativeQuery($oldPersonID, $oldDBManager);

        if (count($ranks) > 0) {
            for ($i = 0; $i < count($ranks); $i++) {
                $rank = $ranks[$i];
                $this->getMigrationService()->migrateRank($newPerson, $rank["order"], $rank["rang"], $rank["rangklasse"], $rank["land"], $rank["territorium"], $rank["ort"], $rank["von-ab"], $rank["bis"], $rank["belegt"], $rank["kommentar"]);
            }
        }
    }

    private function getRankWithNativeQuery($oldPersonID, $oldDBManager) {
        $sql = "SELECT ID, `order`, ort, territorium, land, `von-ab`, bis, rang, rangklasse, belegt, kommentar FROM `rang` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function migrateEducation($newPerson, $oldPersonID, $oldDBManager) {

        $educations = $this->getEducationWithNativeQuery($oldPersonID, $oldDBManager);

        if (count($educations) > 0) {
            for ($i = 0; $i < count($educations); $i++) {
                $education = $educations[$i];
                $this->getMigrationService()->migrateEducation($newPerson, $education["order"], $education["ausbildung"], $education["land"], $education["territorium"], $education["ort"], $education["von-ab"], $education["bis"], $education["belegt"], $education["bildungsabschluss"], $education["bildungsabschlussdatum"], $education["bildungsabschlussort"], $education["kommentar"]);
            }
        }
    }

    private function getEducationWithNativeQuery($oldPersonID, $oldDBManager) {
        $sql = "SELECT ID, `order`, ort, land, territorium, ausbildung, bildungsabschluss, bildungsabschlussdatum, bildungsabschlussort, `von-ab`, bis, belegt, kommentar FROM `ausbildung` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function migrateStatus($newPerson, $oldPersonID, $oldDBManager) {

        $stati = $this->getStatusWithNativeQuery($oldPersonID, $oldDBManager);

        if (count($stati) > 0) {
            for ($i = 0; $i < count($stati); $i++) {
                $status = $stati[$i];
                $this->getMigrationService()->migrateStatus($newPerson, $status["order"], $status["stand"], $status["land"], $status["territorium"], $status["ort"], $status["von-ab"], $status["bis"], $status["belegt"], $status["kommentar"]);
            }
        }
    }

    private function getStatusWithNativeQuery($oldPersonID, $oldDBManager) {
        $sql = "SELECT ID, `order`, ort, territorium, land, `von-ab`, bis, stand, belegt, kommentar FROM `stand`  WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function migrateRoadOfLife($newPerson, $oldPersonID, $oldDBManager) {

        $roadOfLife = $this->getRoadOfLifeWithNativeQuery($oldPersonID, $oldDBManager);

        if (count($roadOfLife) > 0) {
            for ($i = 0; $i < count($roadOfLife); $i++) {
                $step = $roadOfLife[$i];
                $this->getMigrationService()->migrateRoadOfLife($newPerson, $step["order"], $step["stammland"], $step["stammterritorium"], $step["beruf"], $step["land"], $step["territorium"], $step["ort"], $step["von-ab"], $step["bis"], $step["belegt"], $step["kommentar"]);
            }
        }
    }

    private function getRoadOfLifeWithNativeQuery($oldPersonID, $oldDBManager) {
        $sql = "SELECT ID, `order`, ort, territorium, land, stammterritorium, stammland, `von-ab`, bis, beruf, belegt, kommentar FROM `lebensweg` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }
    
    private function migrateNonPaternalGrandparents($newPerson, $mother, $oldPersonID, $oldDBManager){
        //non paternal
        $grandmothers = $oldDBManager->getRepository('OldBundle:GroßmutterMuetterlicherseits')->findById($oldPersonID);

        for ($i = 0; $i < count($grandmothers); $i++) {
            $oldGrandmother = $grandmothers[$i];

            //$firstName, $patronym, $lastName, $gender, $nation, $comment
            $grandmother = $this->getMigrationService()->migrateRelative($oldGrandmother->getVornamen(), null, $oldGrandmother->getName(), "weiblich", $oldGrandmother->getNation());

            $this->trackOriginOfData($grandmother->getId(), $oldGrandmother->getId(), 'großmutter_muetterlicherseits', $oldGrandmother->getOrder(), $oldGrandmother->getOrder2());
            
            $this->getMigrationService()->migrateIsGrandparent($newPerson, $grandmother, false);
            $this->getMigrationService()->migrateIsParent($mother, $grandmother);
        }
        
        //non paternal
        $grandfathers = $this->getGrandfatherMaternalWithNativeQuery($oldPersonID, $oldDBManager);

        for ($i = 0; $i < count($grandfathers); $i++) {
            $oldGrandfather = $grandfathers[$i];

            $grandfather = $this->getMigrationService()->migrateRelative($oldGrandfather["vornamen"], null, $oldGrandfather["name"], "männlich", $oldGrandfather["nation"], $oldGrandfather["kommentar"]);

            $this->trackOriginOfData($grandfather->getId(), $oldGrandfather["ID"], 'großvater_muetterlicherseits', $oldGrandfather["order"], $oldGrandfather["order2"]);
            
            //insert additional data
            if (!is_null($oldGrandfather["beruf"])) {
                $jobID = $this->getMigrationService()->migrateJob($oldGrandfather["beruf"]);

                $grandfather->setJob($jobID);
            }

            if (!is_null($oldGrandfather["wohnort"])) {
                $this->getMigrationService()->migrateResidence($grandfather, 1, null, null, $oldGrandfather["wohnort"]);
            }

            if (!is_null($oldGrandfather["gestorben"])) {
                $this->getMigrationService()->migrateDeath($grandfather, null, $oldGrandfather["gestorben"]);
            }

            //check if reference to person
            if (!is_null($oldGrandfather["mütterl_großvater_id-nr"])) {
                $grandfathersOID = $oldGrandfather["mütterl_großvater_id-nr"];
                try{
                    $grandfatherMainID = $this->getIDForOID($grandfathersOID, $oldDBManager);
                    $newGrandfather = $this->migratePerson($grandfatherMainID, $grandfathersOID);
                    $grandfather = $this->get("person_merging.service")->mergePersons($grandfather, $newGrandfather);
                }catch(\UR\AmburgerBundle\Exception\NotFoundException $e){
                    $this->LOGGER->info("Could not found ID for OID: ".$grandfathersOID);
                    $grandfather->setComment($grandfather->getComment() ? $grandfather->getComment()."ReferencedOID: ".$grandfathersOID : "ReferencedOID: ".$grandfathersOID);
                }
            }

            $this->getMigrationService()->migrateIsGrandparent($newPerson, $grandfather, false);
            $this->getMigrationService()->migrateIsParent($mother, $grandfather);
        }
    }
    
    private function migratePaternalGrandparents($newPerson, $father, $oldPersonID, $oldDBManager){
       //paternal
        $grandmothers = $oldDBManager->getRepository('OldBundle:GroßmutterVaeterlicherseits')->findById($oldPersonID);

        for ($i = 0; $i < count($grandmothers); $i++) {
            $oldGrandmother = $grandmothers[$i];

            //$firstName, $patronym, $lastName, $gender, $nation, $comment
            $grandmother = $this->getMigrationService()->migrateRelative($oldGrandmother->getVornamen(), null, $oldGrandmother->getName(), "weiblich");

            $this->trackOriginOfData($grandmother->getId(), $oldGrandmother->getId(), 'großmutter_vaeterlicherseits', $oldGrandmother->getOrder(), $oldGrandmother->getOrder2());
            
            //insert additional data
            if (!is_null($oldGrandmother->getGeburtsland())) {
                $this->getMigrationService()->migrateBirth($grandmother, null, null, null, $oldGrandmother->getGeburtsland());
            }

            if (!is_null($oldGrandmother->getBeruf())) {
                $jobID = $this->getMigrationService()->migrateJob($oldGrandmother->getBeruf());

                $grandmother->setJob($jobID);
            }

            $this->getMigrationService()->migrateIsParent($father, $grandmother);
            $this->getMigrationService()->migrateIsGrandparent($newPerson, $grandmother, true);
        }
        
        
        //paternal
        $grandfathers = $this->getGrandfatherPaternalWithNativeQuery($oldPersonID, $oldDBManager);

        for ($i = 0; $i < count($grandfathers); $i++) {
            $oldGrandfather = $grandfathers[$i];

            $grandfather = $this->createGrandfatherPaternal($oldGrandfather);
            
            //check if reference to person
            if (!is_null($oldGrandfather["vät_großvater_id-nr"])) {
                $this->LOGGER->debug("Found paternal grandfather reference");
                $grandfathersOID = $oldGrandfather["vät_großvater_id-nr"];

                try{
                    $grandfatherMainID = $this->getIDForOID($grandfathersOID, $oldDBManager);
                    $newGrandfatherObj = $this->migratePerson($grandfatherMainID, $grandfathersOID);
                    $grandfather = $this->get("person_merging.service")->mergePersons($grandfather, $newGrandfatherObj);

                }catch(\UR\AmburgerBundle\Exception\NotFoundException $e){
                    $this->LOGGER->info("Could not found ID for OID: ".$grandfathersOID);
                    $grandfather->setComment($grandfather->getComment() ? $grandfather->getComment()."ReferencedOID: ".$grandfathersOID : "ReferencedOID: ".$grandfathersOID);
                }
            }

            $this->getMigrationService()->migrateIsParent($father, $grandfather);
            $this->getMigrationService()->migrateIsGrandparent($newPerson, $grandfather, true);
        }
    }
    
    private function createGrandfatherPaternal($oldGrandfather) {
        $grandfather = $this->getMigrationService()->migrateRelative($oldGrandfather["vornamen"], null, $oldGrandfather["name"], "männlich", $oldGrandfather["nation"], $oldGrandfather["kommentar"]);

        $this->trackOriginOfData($grandfather->getId(), $oldGrandfather["ID"], 'großvater_vaeterlicherseits', $oldGrandfather["order"], $oldGrandfather["order2"]);  
        
        //insert additional data
        if (!is_null($oldGrandfather["beruf"])) {
            $jobID = $this->getMigrationService()->migrateJob($oldGrandfather["beruf"]);

            $grandfather->setJob($jobID);
        }

        if (!is_null($oldGrandfather["geburtsort"]) ||
                !is_null($oldGrandfather["geburtsland"]) ||
                !is_null($oldGrandfather["geburtsterritorium"]) ||
                !is_null($oldGrandfather["geboren"])) {
            $this->getMigrationService()->migrateBirth($grandfather, null, null, null, $oldGrandfather["geburtsland"], $oldGrandfather["geburtsort"], $oldGrandfather["geboren"], $oldGrandfather["geburtsterritorium"]);
        }

        if (!is_null($oldGrandfather["wohnort"]) ||
                !is_null($oldGrandfather["wohnterritorium"])) {
            $this->getMigrationService()->migrateResidence($grandfather, 1, null, $oldGrandfather["wohnterritorium"], $oldGrandfather["wohnort"]);
        }

        if (!is_null($oldGrandfather["gestorben"])) {
            $this->getMigrationService()->migrateDeath($grandfather, null, $oldGrandfather["gestorben"]);
        }

        if (!is_null($oldGrandfather["rang"])) {
            $this->getMigrationService()->migrateRank($grandfather, 1, $oldGrandfather["rang"]);
        }

        if (!is_null($oldGrandfather["stand"])) {
            $this->getMigrationService()->migrateStatus($grandfather, 1, $oldGrandfather["stand"]);
        }

        return $grandfather;
    }

    private function getGrandfatherMaternalWithNativeQuery($oldPersonID, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, vornamen, name, gestorben, wohnort, nation, beruf, `mütterl_großvater_id-nr`, kommentar FROM `großvater_muetterlicherseits` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getGrandfatherPaternalWithNativeQuery($oldPersonID, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, vornamen, name, geboren, geburtsort, geburtsland, geburtsterritorium, gestorben, wohnort, wohnterritorium, nation, beruf, rang, stand, `vät_großvater_id-nr`, kommentar FROM `großvater_vaeterlicherseits` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    public function migrateMother($newPerson, $oldPersonID, $oldDBManager) {

        $mothers = $this->getMotherWithNativeQuery($oldPersonID, $oldDBManager);

        $this->LOGGER->debug("Loaded " . count($mothers) . " mother entries");

        if (count($mothers) == 1) {
            $oldMother = $mothers[0];

            $newMother = $this->createMother($oldMother);
            //check if reference to person
            if (!is_null($oldMother["mutter_id-nr"])) {


                //problem since some mutter_id-nrs are referencing sons others are referencing entries for the mother in the person table
                if ($this->checkIfMotherReferenceContainsChildPerson($oldPersonID, $oldMother["mutter_id-nr"], $oldDBManager)) {
                    $this->getLogger()->info("Child reference found...");
                    // child reference found what to do now?
                    //TODO: Not happening? for now just insert the data... perhaps in future create one relative and reference to it from all childs?
                    //$newMother = $this->createMother($oldMother);
                } else {
                    $this->getLogger()->info("Mother reference found...");
                    //reference to person entry for mother
                    $mothersOID = $oldMother["mutter_id-nr"];
                    try{
                        $mothersMainID = $this->getIDForOID($mothersOID, $oldDBManager);
                        $newMotherObj = $this->migratePerson($mothersMainID, $mothersOID);
                        $newMother = $this->get("person_merging.service")->mergePersons($newMother, $newMotherObj);
                    }catch(\UR\AmburgerBundle\Exception\NotFoundException $e){
                        $this->LOGGER->info("Could not find ID for OID: ".$mothersOID);
                        $newMother->setComment($newMother->getComment() ? $newMother->getComment()."ReferencedOID: ".$mothersOID : "ReferencedOID: ".$mothersOID);
                    }
                }
            }

            $this->getMigrationService()->migrateIsParent($newPerson, $newMother);

            $this->migrateNonPaternalGrandparents($newPerson, $newMother, $oldPersonID, $oldDBManager);
            
            //partners of mother
            $this->migratePartnersOfMother($newMother, $oldPersonID, $oldDBManager);

            return $newMother;
        }

        return null;
    }

    private function createMother($oldMother) {
        //$firstName, $patronym, $lastName, $gender, $nation, $comment
        $mother = $this->getMigrationService()->migrateRelative($oldMother["vornamen"], $oldMother["russ_vornamen"], $oldMother["name"], "weiblich", $oldMother["nation"], $oldMother["kommentar"]);

        $this->trackOriginOfData($mother->getId(), $oldMother["ID"], 'mutter', $oldMother["order"]);
        
        $mother->setForeName($this->getNormalizationService()->writeOutNameAbbreviations($oldMother["rufnamen"]));

        //additional data
        //birth
        if (!is_null($oldMother["herkunftsort"]) ||
                !is_null($oldMother["herkunftsland"]) ||
                !is_null($oldMother["herkunftsterritorium"]) ||
                !is_null($oldMother["geburtsort"]) ||
                !is_null($oldMother["geboren"])) {
            //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
            $this->getMigrationService()->migrateBirth($mother, $oldMother["herkunftsland"], $oldMother["herkunftsterritorium"], $oldMother["herkunftsort"], null, $oldMother["geburtsort"], $oldMother["geboren"]);
        }

        //baptism
        if (!is_null($oldMother["getauft"])) {
            $this->getMigrationService()->migrateBaptism($mother, $oldMother["getauft"]);
        }

        //death
        if (!is_null($oldMother["gestorben"]) ||
                !is_null($oldMother["todesort"]) ||
                !is_null($oldMother["todesterritorium"]) ||
                !is_null($oldMother["begraben"]) ||
                !is_null($oldMother["friedhof"])) {
            //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
            $this->getMigrationService()->migrateDeath($mother, $oldMother["todesort"], $oldMother["gestorben"], null, null, $oldMother["todesterritorium"], $oldMother["friedhof"], null, $oldMother["begraben"]);
        }

        //residence
        if (!is_null($oldMother["wohnort"])) {
            $this->getMigrationService()->migrateResidence($mother, 1, null, null, $oldMother["wohnort"]);
        }

        //religion
        if (!is_null($oldMother["konfession"])) {
            $this->getMigrationService()->migrateReligion($mother, $oldMother["konfession"], 1);
        }

        //status
        if (!is_null($oldMother["stand"])) {
            $this->getMigrationService()->migrateStatus($mother, 1, $oldMother["stand"]);
        }

        //rank
        if (!is_null($oldMother["rang"])) {
            $this->getMigrationService()->migrateRank($mother, 1, $oldMother["rang"]);
        }


        //property
        if (!is_null($oldMother["besitz"])) {
            $this->getMigrationService()->migrateProperty($mother, 1, $oldMother["besitz"]);
        }


        //job
        if (!is_null($oldMother["beruf"])) {
            $jobID = $this->getMigrationService()->migrateJob($oldMother["beruf"]);

            $mother->setJob($jobID);
        }

        //born_in_marriage
        if (!is_null($oldMother["ehelich"])) {
            $mother->setBornInMarriage($this->getNormalizationService()->writeOutAbbreviations($oldMother["ehelich"]));
        }

        return $mother;
    }

    private function getMotherWithNativeQuery($oldPersonID, $oldDBManager) {
        $sql = "SELECT ID, `order`, vornamen, russ_vornamen, name, rufnamen, geboren, geburtsort, getauft, gestorben, todesort, todesterritorium, begraben, friedhof, herkunftsort, herkunftsland, herkunftsterritorium, wohnort, nation, konfession, ehelich, stand, rang, besitz, beruf, `mutter_id-nr`, kommentar FROM `mutter` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function checkIfMotherReferenceContainsChildPerson($childID, $motherReferenceOid, $oldDBManager) {

        /*
          Für problematische Mutter-Kind Einträge in der mutter_id-nr:
          Schritt 1: ID für OID in mutter_id-nr auslesen.
          Schritt 2: Prüfen, ob ein weiterer Eintrag in Mutter für diese ID vorhanden ist.
          Schritt 3: Prüfen, ob die OID dieses Eintrags auf die ID des ersten Eintrags verweist.
          Wenn ja ==> Sonderfall gefunden
          (Wenn nein, eventuell das Ganze so weit weiter machen, bis mutter_id-nr leer ist/kein weiterer Verweis vorhanden ist?)
          (Dies könnte nötig sein, um einen Kreisverweis von mehr als 2 Kindern abzufangen)
         */

        $this->getLogger()->info("Checking against ID " . $childID . " for OID " . $motherReferenceOid);

        $referenceMotherID = $this->getIDForOID($motherReferenceOid, $oldDBManager);

        $mother = $this->getMotherWithNativeQuery($referenceMotherID, $oldDBManager);

        if (count($mother) > 0) {
            $this->getLogger()->debug("There is a mother for : " . $referenceMotherID);
            if (!is_null($mother[0]["mutter_id-nr"]) && $mother[0]["mutter_id-nr"] != "") {
                $this->getLogger()->info("New mother Oid found: " . $mother[0]["mutter_id-nr"]);
                $nextReferenceOid = $mother[0]["mutter_id-nr"];

                $nextReferenceID = $this->getIDForOID($nextReferenceOid, $oldDBManager);

                if ($childID == $nextReferenceID) {
                    return true;
                }

                //check next reference?
                return $this->checkIfMotherReferenceContainsChildPerson($childID, $nextReferenceOid, $oldDBManager);
            }
        }

        return false;
    }

    private function migrateFather($newPerson, $oldPersonID, $mother, $oldDBManager) {
        //non paternal
        $fathers = $this->getFatherWithNativeQuery($oldPersonID, $oldDBManager);

        $this->LOGGER->debug("Loaded " . count($fathers) . " father entries");

        $nrOfFathers = count($fathers);

        if ($nrOfFathers == 1) {
            $oldFather = $fathers[0];

            if (!is_null($oldFather["vater_id-nr"])) {
                $this->getLogger()->info("Father reference found: ".$oldFather["vater_id-nr"]);

                $result = $this->separateReferenceIdsAndComment($oldFather["vater_id-nr"]);

                $referenceIds = $this->extractReferenceIdsArray($result[0]);

                if (count($referenceIds) > 0) {
                    for ($j = 0; $j < count($referenceIds); $j++) {
                        //check it?
                        $fathersOID = $referenceIds[$j];
                                                
                        $this->getLogger()->debug("Extracted father OID: ".$fathersOID);
                        
                        $newFather = $this->createFather($oldFather, $mother, $newPerson);
                        
                        try{
                            $fathersMainID = $this->getIDForOID($fathersOID, $oldDBManager);
                            $newFatherObj = $this->migratePerson($fathersMainID, $fathersOID);
                            $newFather = $this->get("person_merging.service")->mergePersons($newFatherObj, $newFather);
                        }catch(\UR\AmburgerBundle\Exception\NotFoundException $e){
                            $this->LOGGER->info("Could not found ID for OID: ".$fathersOID);
                            $newFather->setComment($newFather->getComment() ? $newFather->getComment()."ReferencedOID: ".$fathersOID : "ReferencedOID: ".$fathersOID);
                        }

                        $this->getMigrationService()->migrateIsParent($newPerson, $newFather, $result[1]);
                        
                        $this->migratePaternalGrandparents($newPerson, $newFather, $oldPersonID, $oldDBManager);
                        
                        $this->migratePartnersOfFather($newFather, $oldPersonID, $oldDBManager);
                    }
                } else {
                    $newFather = $this->createFather($oldFather, $mother, $newPerson);

                    $this->getMigrationService()->migrateIsParent($newPerson, $newFather, $result[1]);
                    
                    $this->migratePaternalGrandparents($newPerson, $newFather, $oldPersonID, $oldDBManager);

                    $this->migratePartnersOfFather($newFather, $oldPersonID, $oldDBManager);
                }
            } else {
                $newFather = $this->createFather($oldFather, $mother, $newPerson);

                $this->getMigrationService()->migrateIsParent($newPerson, $newFather);
                
                $this->migratePaternalGrandparents($newPerson, $newFather, $oldPersonID, $oldDBManager);

                $this->migratePartnersOfFather($newFather, $oldPersonID, $oldDBManager);
            }
        } else {
            $this->LOGGER->debug("Found " . $nrOfFathers == 0 ? "no" : $nrOfFathers . " fathers");
        }
    }

    private function createFather($oldFather, $mother, $newPerson) {
        $lastName = $oldFather['name'];
        
        if(is_null($lastName) || $lastName == ''){
            $lastName = $newPerson->getLastName();
            $this->getLogger()->info("Setting main persons lastname for this father: ".$lastName);
        }
        
        $this->LOGGER->info("Creating Father for " . $oldFather["vornamen"] . " " . $lastName);
        //$firstName, $patronym, $lastName, $gender, $nation, $comment
        $father = $this->getMigrationService()->migrateRelative($oldFather["vornamen"], $oldFather["russ_vornamen"], $lastName, "männlich", $oldFather["nation"], $oldFather["kommentar"]);

        $this->trackOriginOfData($father->getId(), $oldFather["ID"], 'vater', $oldFather["order"]);
        
        $father->setForeName($this->getNormalizationService()->writeOutNameAbbreviations($oldFather["rufnamen"]));

        //additional data
        //birth
        if (!is_null($oldFather["herkunftsort"]) ||
                !is_null($oldFather["herkunftsland"]) ||
                !is_null($oldFather["herkunftsterritorium"]) ||
                !is_null($oldFather["geburtsort"]) ||
                !is_null($oldFather["geburtsterritorium"]) ||
                !is_null($oldFather["geburtsland"]) ||
                !is_null($oldFather["geboren"])) {
            //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
            $this->getMigrationService()->migrateBirth($father, $oldFather["herkunftsland"], $oldFather["herkunftsterritorium"], $oldFather["herkunftsort"], $oldFather["geburtsland"], $oldFather["geburtsort"], $oldFather["geboren"], $oldFather["geburtsterritorium"]);
        }

        //baptism
        if (!is_null($oldFather["getauft"]) ||
                !is_null($oldFather["taufort"])) {
            $this->getMigrationService()->migrateBaptism($father, $oldFather["getauft"], $oldFather["taufort"]);
        }

        //death
        if (!is_null($oldFather["gestorben"]) ||
                !is_null($oldFather["todesort"]) ||
                !is_null($oldFather["todesterritorium"]) ||
                !is_null($oldFather["begraben"]) ||
                !is_null($oldFather["begräbnisort"])) {
            //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
            $this->getMigrationService()->migrateDeath($father, $oldFather["todesort"], $oldFather["gestorben"], null, null, $oldFather["todesterritorium"], null, $oldFather["begräbnisort"], $oldFather["begraben"]);
        }

        //residence
        if (!is_null($oldFather["wohnort"]) ||
                !is_null($oldFather["wohnterritorium"]) ||
                !is_null($oldFather["wohnland"])) {
            $this->getMigrationService()->migrateResidence($father, 1, $oldFather["wohnland"], $oldFather["wohnterritorium"], $oldFather["wohnort"]);
        }

        //religion
        if (!is_null($oldFather["konfession"])) {
            $this->getMigrationService()->migrateReligion($father, $oldFather["konfession"], 1);
        }

        //status
        if (!is_null($oldFather["stand"])) {
            $this->getMigrationService()->migrateStatus($father, 1, $oldFather["stand"]);
        }

        //rank
        if (!is_null($oldFather["rang"])) {
            $this->getMigrationService()->migrateRank($father, 1, $oldFather["rang"]);
        }


        //property
        if (!is_null($oldFather["besitz"])) {
            $this->getMigrationService()->migrateProperty($father, 1, $oldFather["besitz"]);
        }


        //job
        if (!is_null($oldFather["beruf"])) {
            $job = $this->getMigrationService()->migrateJob($oldFather["beruf"]);

            $father->setJob($job);
        }

        //education
        if (!is_null($oldFather["ausbildung"]) ||
                !is_null($oldFather["bildungsabschluss"])) {
            //$educationOrder, $label, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $graduationLabel=null, $graduationDate=null, $graduationLocation=null, $comment=null
            $this->getMigrationService()->migrateEducation($father, 1, $oldFather["ausbildung"], null, null, null, null, null, null, $oldFather["bildungsabschluss"]);
        }

        //honour
        if (!is_null($oldFather["ehren"])) {
            $this->getMigrationService()->migrateHonour($father, 1, $oldFather["ehren"]);
        }

        //born_in_marriage
        if (!is_null($oldFather["ehelich"])) {
            $father->setBornInMarriage($this->getNormalizationService()->writeOutAbbreviations($oldFather["ehelich"]));
        }

        //wedding with mother
        if (!is_null($oldFather['hochzeitstag']) || !is_null($mother)) {
            $this->getMigrationService()->migrateWedding(1, $father, $mother, $oldFather['hochzeitstag']);
        } else {
            $this->getLogger()->debug("Skipping wedding creation, since neither mother nor weddingday is set.");
        }

        $this->getMigrationService()->saveObject($father);

        $this->LOGGER->info("Created father: " . $father);

        return $father;
    }

    private function getFatherWithNativeQuery($oldPersonID, $oldDBManager) {
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

    private function migrateSibling($newPerson, $oldPersonID, $oldDBManager) {
        //non paternal
        $siblings = $this->getSiblingWithNativeQuery($oldPersonID, $oldDBManager);

        for ($i = 0; $i < count($siblings); $i++) {
            $oldSibling = $siblings[$i];

            $newSibling = $this->createSibling($oldSibling, $oldPersonID, $oldDBManager);

            //check if reference to person
            if (!is_null($oldSibling["geschwister_id-nr"]) && trim($oldSibling["geschwister_id-nr"]) != "") {
                $this->getLogger()->info("Sibling reference found...");
                //check it?
                $siblingsOID = $oldSibling["geschwister_id-nr"];
                
                try{
                    $siblingsMainId = $this->getIDForOID($siblingsOID, $oldDBManager);
                    $newSiblingObj = $this->migratePerson($siblingsMainId, $siblingsOID);
                    $newSibling = $this->get("person_merging.service")->mergePersons($newSibling, $newSiblingObj);
                }catch(\UR\AmburgerBundle\Exception\NotFoundException $e){
                    $this->LOGGER->info("Could not found ID for OID: ".$siblingsOID);
                    $newSibling->setComment($newSibling->getComment() ? $newSibling->getComment()."ReferencedOID: ".$siblingsOID : "ReferencedOID: ".$siblingsOID);
                }

            }

            $this->getMigrationService()->migrateIsSibling($newPerson, $newSibling);

            $this->migrateMarriagePartnersOfSibling($newSibling, $oldSibling["order"], $oldPersonID, $oldDBManager);

            $this->migrateFatherOfSibling($newSibling, $oldSibling["order"], $oldPersonID, $oldDBManager);
            $this->migrateMotherOfSibling($newSibling, $oldSibling["order"], $oldPersonID, $oldDBManager);
        }
    }

    private function createSibling($oldSibling, $oldPersonID, $oldDBManager) {
        //$firstName, $patronym, $lastName, $gender, $nation, $comment
        $sibling = $this->getMigrationService()->migrateRelative($oldSibling["vornamen"], $oldSibling["russ_vornamen"], $oldSibling["name"], $oldSibling["geschlecht"], null, $oldSibling["kommentar"]);

        $this->trackOriginOfData($sibling->getId(), $oldSibling["ID"], 'geschwister', $oldSibling["order"]);
        
        $sibling->setForeName($this->getNormalizationService()->writeOutNameAbbreviations($oldSibling["rufnamen"]));

        //additional data
        $siblingEducation = $this->getSiblingsEducationWithNativeQuery($oldPersonID, $oldSibling["order"], $oldDBManager);

        $siblingHonour = $this->getSiblingsHonourWithNativeQuery($oldPersonID, $oldSibling["order"], $oldDBManager);

        $siblingOrigin = $this->getSiblingsOriginWithNativeQuery($oldPersonID, $oldSibling["order"], $oldDBManager);

        $siblingRoadOfLife = $this->getSiblingsRoadOfLifeWithNativeQuery($oldPersonID, $oldSibling["order"], $oldDBManager);

        $siblingRank = $this->getSiblingsRankWithNativeQuery($oldPersonID, $oldSibling["order"], $oldDBManager);

        $siblingStatus = $this->getSiblingsStatusWithNativeQuery($oldPersonID, $oldSibling["order"], $oldDBManager);

        $siblingDeath = $this->getSiblingsDeathWithNativeQuery($oldPersonID, $oldSibling["order"], $oldDBManager);




        if (count($siblingEducation) > 0) {
            //education
            for ($i = 0; $i < count($siblingEducation); $i++) {
                $education = $siblingEducation[$i];
                $this->getMigrationService()->migrateEducation($sibling, $education["order2"], $education["ausbildung"], $education["land"], null, $education["ort"], $education["von-ab"], $education["bis"], $education["belegt"], $education["bildungsabschluss"]);
            }
        }


        if (count($siblingHonour) > 0) {
            //honour
            for ($i = 0; $i < count($siblingHonour); $i++) {
                $honour = $siblingHonour[$i];
                $this->getMigrationService()->migrateHonour($sibling, $honour["order2"], $honour["ehren"], $honour["land"]);
            }
        }


        if (count($siblingOrigin) > 0) {
            //origin
            for ($i = 0; $i < count($siblingOrigin); $i++) {
                if ($siblingOrigin[$i]['geboren'] != null || $siblingOrigin[$i]['geburtsort'] != null || $siblingOrigin[$i]['geburtsland'] != null || $siblingOrigin[$i]['kommentar'] != null) {
                    //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
                    $this->getMigrationService()->migrateBirth($sibling, null, null, null, $siblingOrigin[$i]["geburtsland"], $siblingOrigin[$i]["geburtsort"], $siblingOrigin[$i]["geboren"], null, $siblingOrigin[$i]['kommentar']);
                }

                if ($siblingOrigin[$i]['getauft'] != null || $siblingOrigin[$i]['taufort'] != null) {
                    $this->getMigrationService()->migrateBaptism($sibling, $siblingOrigin[$i]["getauft"], $siblingOrigin[$i]["taufort"]);
                }
            }
        }


        if (count($siblingRoadOfLife) > 0) {
            //roadOfLife
            for ($i = 0; $i < count($siblingRoadOfLife); $i++) {
                $step = $siblingRoadOfLife[$i];
                $this->getMigrationService()->migrateRoadOfLife($sibling, $step["order2"], $step["stammland"], null, $step["beruf"], null, $step["territorium"], $step["ort"], $step["von-ab"], $step["bis"], $step["belegt"], $step["kommentar"]);
            }
        }


        if (count($siblingRank) > 0) {
            //rank
            for ($i = 0; $i < count($siblingRank); $i++) {
                $rank = $siblingRank[$i];
                $this->getMigrationService()->migrateRank($sibling, $rank["order2"], $rank["rang"], null, $rank["land"], null, null, null, null, null, $rank["kommentar"]);
            }
        }


        if (count($siblingStatus) > 0) {
            //status
            for ($i = 0; $i < count($siblingStatus); $i++) {
                $status = $siblingStatus[$i];
                $this->getMigrationService()->migrateStatus($sibling, $status["order2"], $status["stand"], $status["land"], null, null, $status["von-ab"]);
            }
        }


        if (count($siblingDeath) > 0) {
            //death
            for ($i = 0; $i < count($siblingDeath); $i++) {
                //death
                if (!is_null($siblingDeath[$i]["begräbnisort"]) ||
                        !is_null($siblingDeath[$i]["gestorben"]) ||
                        !is_null($siblingDeath[$i]["todesort"]) ||
                        !is_null($siblingDeath[$i]["friedhof"]) ||
                        !is_null($siblingDeath[$i]["todesursache"]) ||
                        !is_null($siblingDeath[$i]["kommentar"])) {
                    //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
                    $this->getMigrationService()->migrateDeath($sibling, $siblingDeath[$i]["todesort"], $siblingDeath[$i]["gestorben"], null, $siblingDeath[$i]["todesursache"], null, $siblingDeath[$i]["friedhof"], $siblingDeath[$i]["begräbnisort"], null, $siblingDeath[$i]["kommentar"]);
                }
            }
        }

        return $sibling;
    }

    private function getSiblingWithNativeQuery($oldPersonID, $oldDBManager) {
        $sql = "SELECT ID, `order`, vornamen, russ_vornamen, name, rufnamen, geschlecht, `geschwister_id-nr`, kommentar 
                    FROM `geschwister` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getSiblingsEducationWithNativeQuery($oldPersonID, $siblingNr, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, land, ort, `von-ab`, bis, ausbildung, bildungsabschluss, belegt 
        FROM `ausbildung_des_geschwisters` WHERE ID=:personID AND `order`=:siblingNr";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('siblingNr', $siblingNr);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getSiblingsHonourWithNativeQuery($oldPersonID, $siblingNr, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, land, ehren
                FROM `ehren_des_geschwisters` WHERE ID=:personID AND `order`=:siblingNr";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('siblingNr', $siblingNr);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getSiblingsOriginWithNativeQuery($oldPersonID, $siblingNr, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, geboren, geburtsort, geburtsland, getauft, taufort, kommentar
                FROM `herkunft_des_geschwisters` WHERE ID=:personID AND `order`=:siblingNr";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('siblingNr', $siblingNr);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getSiblingsRoadOfLifeWithNativeQuery($oldPersonID, $siblingNr, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, ort, territorium, stammland, beruf, `von-ab`, bis, belegt, kommentar
                FROM `lebensweg_des_geschwisters` WHERE ID=:personID AND `order`=:siblingNr";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('siblingNr', $siblingNr);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getSiblingsRankWithNativeQuery($oldPersonID, $siblingNr, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, land, rang, kommentar
                FROM `rang_des_geschwisters` WHERE ID=:personID AND `order`=:siblingNr";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('siblingNr', $siblingNr);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getSiblingsStatusWithNativeQuery($oldPersonID, $siblingNr, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, land, stand, `von-ab`
                FROM `stand_des_geschwisters` WHERE ID=:personID AND `order`=:siblingNr";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('siblingNr', $siblingNr);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getSiblingsDeathWithNativeQuery($oldPersonID, $siblingNr, $oldDBManager) {
        $sql = "SELECT `ID`,`order`,`order2`,`begräbnisort`,`gestorben`,`todesort`,`friedhof`,`kommentar`,`todesursache` 
                FROM `tod_des_geschwisters` WHERE ID=:personID AND `order`=:siblingNr";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('siblingNr', $siblingNr);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function migrateMarriagePartner($newPerson, $oldPersonID, $oldDBManager) {
        $this->getLogger()->info("Migrating Marriage Partners!");
        //non paternal
        $marriagePartners = $this->getMarriagePartnerWithNativeQuery($oldPersonID, $oldDBManager);

        $this->getLogger()->info("Found " . count($marriagePartners) . " partners.");

        for ($i = 0; $i < count($marriagePartners); $i++) {
            $oldMarriagePartner = $marriagePartners[$i];

            $newMarriagePartner = $this->createMarriagePartner($newPerson, $oldMarriagePartner);
            
            $comment = null;

            //check if reference to person
            if (!is_null($oldMarriagePartner["ehepartner_id-nr"]) ||
                    !is_null($oldMarriagePartner["partnerpartner_id-nr"])) {
                $this->getLogger()->info("Reference to marriage partners found!");
                
                //check it?
                $referenceValue = $oldMarriagePartner["ehepartner_id-nr"];

                if (is_null($referenceValue)) {
                    $referenceValue = $oldMarriagePartner["partnerpartner_id-nr"];
                }
                
                $result = $this->separateReferenceIdsAndComment($referenceValue);
                
                if(!is_null($result[0])){
                    $marriagePartnersOID = $result[0];
                    
                    try{
                        $marriagePartnersMainID = $this->getIDForOID($marriagePartnersOID, $oldDBManager);
                        $newMarriagePartnerObj = $this->migratePerson($marriagePartnersMainID, $marriagePartnersOID);
                        $newMarriagePartner = $this->get("person_merging.service")->mergePersons($newMarriagePartner, $newMarriagePartnerObj);
                    }catch(\UR\AmburgerBundle\Exception\NotFoundException $e){
                        $this->LOGGER->info("Could not found ID for OID: ".$marriagePartnersOID);
                        $newMarriagePartner->setComment($newMarriagePartner->getComment() ? $newMarriagePartner->getComment()."ReferencedOID: ".$marriagePartnersOID : "ReferencedOID: ".$marriagePartnersOID);
                    }
                }else {
                    $newMarriagePartner = $this->migratePerson($marriagePartnersMainID, $marriagePartnersOID);
                }

               $comment = $result[1];
            }

            $this->migrateWedding($newPerson, $newMarriagePartner, $oldMarriagePartner, $comment);

            //mother in law imports father in law!
            $this->migrateMotherInLaw($newPerson, $newMarriagePartner, $oldMarriagePartner["order"], $oldPersonID, $oldDBManager);

            //children?
            $this->migrateChild($newPerson, $newMarriagePartner, $oldMarriagePartner["order"], $oldPersonID, $oldDBManager);

            //other partners
            $this->migrateOtherPartners($newMarriagePartner, $oldMarriagePartner["order"], $oldPersonID, $oldDBManager);
        }
    }

    private function createMarriagePartner($newPerson, $oldMarriagePartner) {

        $gender = $this->getOppositeGender($newPerson);

        //$firstName, $patronym, $lastName, $gender, $nation, $comment
        $marriagePartner = $this->getMigrationService()->migratePartner($oldMarriagePartner["vornamen"], $oldMarriagePartner["russ_vornamen"], $oldMarriagePartner["name"], $gender, $oldMarriagePartner["nation"], $oldMarriagePartner["kommentar"]);

        $this->trackOriginOfData($marriagePartner->getId(), $oldMarriagePartner["ID"], 'ehepartner', $oldMarriagePartner["order"]);   
        
        $marriagePartner->setForeName($this->getNormalizationService()->writeOutNameAbbreviations($oldMarriagePartner["rufnamen"]));

        //additional data
        //birth
        if (!is_null($oldMarriagePartner["herkunftsort"]) ||
                !is_null($oldMarriagePartner["herkunftsland"]) ||
                !is_null($oldMarriagePartner["herkunftsterritorium"]) ||
                !is_null($oldMarriagePartner["geburtsort"]) ||
                !is_null($oldMarriagePartner["geburtsterritorium"]) ||
                !is_null($oldMarriagePartner["geburtsland"]) ||
                !is_null($oldMarriagePartner["geboren"])) {
            //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
            $this->getMigrationService()->migrateBirth($marriagePartner, $oldMarriagePartner["herkunftsland"], $oldMarriagePartner["herkunftsterritorium"], $oldMarriagePartner["herkunftsort"], $oldMarriagePartner["geburtsland"], $oldMarriagePartner["geburtsort"], $oldMarriagePartner["geboren"], $oldMarriagePartner["geburtsterritorium"]);
        }

        //baptism
        if (!is_null($oldMarriagePartner["getauft"]) ||
                !is_null($oldMarriagePartner["taufort"])) {
            $this->getMigrationService()->migrateBaptism($marriagePartner, $oldMarriagePartner["getauft"], $oldMarriagePartner["taufort"]);
        }

        //death
        if (!is_null($oldMarriagePartner["gestorben"]) ||
                !is_null($oldMarriagePartner["todesort"]) ||
                !is_null($oldMarriagePartner["todesterritorium"]) ||
                !is_null($oldMarriagePartner["todesursache"]) ||
                !is_null($oldMarriagePartner["todesland"]) ||
                !is_null($oldMarriagePartner["begraben"]) ||
                !is_null($oldMarriagePartner["friedhof"]) ||
                !is_null($oldMarriagePartner["begräbnisort"])) {
            //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
            $this->getMigrationService()->migrateDeath($marriagePartner, $oldMarriagePartner["todesort"], $oldMarriagePartner["gestorben"], $oldMarriagePartner["todesland"], $oldMarriagePartner["todesursache"], $oldMarriagePartner["todesterritorium"], $oldMarriagePartner["friedhof"], $oldMarriagePartner["begräbnisort"], $oldMarriagePartner["begraben"]);
        }


        //religion
        if (!is_null($oldMarriagePartner["konfession"])) {
            $this->getMigrationService()->migrateReligion($marriagePartner, $oldMarriagePartner["konfession"], 1);
        }

        //status
        if (!is_null($oldMarriagePartner["stand"])) {
            $this->getMigrationService()->migrateStatus($marriagePartner, 1, $oldMarriagePartner["stand"]);
        }

        //rank
        if (!is_null($oldMarriagePartner["rang"])) {
            $this->getMigrationService()->migrateRank($marriagePartner, 1, $oldMarriagePartner["rang"]);
        }


        //property
        if (!is_null($oldMarriagePartner["besitz"])) {
            $this->getMigrationService()->migrateProperty($marriagePartner, 1, $oldMarriagePartner["besitz"]);
        }


        //job
        if (!is_null($oldMarriagePartner["beruf"])) {
            $jobID = $this->getMigrationService()->migrateJob($oldMarriagePartner["beruf"]);

            $marriagePartner->setJob($jobID);
        }

        //education
        if (!is_null($oldMarriagePartner["bildungsabschluss"])) {
            //$educationOrder, $label, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $graduationLabel=null, $graduationDate=null, $graduationLocation=null, $comment=null
            $this->getMigrationService()->migrateEducation($marriagePartner, 1, null, null, null, null, null, null, null, $oldMarriagePartner["bildungsabschluss"]);
        }

        //honour
        if (!is_null($oldMarriagePartner["ehren"])) {
            $this->getMigrationService()->migrateHonour($marriagePartner, 1, $oldMarriagePartner["ehren"]);
        }

        return $marriagePartner;
    }

    private function getOppositeGender($newPerson) {
        $this->getLogger()->debug("Finding Gender.");
        $genderOfPerson = $newPerson->getGender();

        $oppositeGender = $this->getMigrationService()->getOppositeGender($genderOfPerson);

        $this->getLogger()->debug("Gender of Person: " . $genderOfPerson . " OppositeGender: " . $oppositeGender);


        return $oppositeGender;
    }

    private function migrateWedding($newPerson, $marriagePartner, $oldMarriagePartner, $comment = null) {
        //$weddingOrder, $husband, $wife, $weddingDateid, $weddingLocationid, $weddingTerritoryid, $bannsDateid, $breakupReason, $breakupDateid, $marriageComment, $beforeAfter, $comment
        $this->getMigrationService()->migrateWedding($oldMarriagePartner['order'], $newPerson, $marriagePartner, $oldMarriagePartner['hochzeitstag'], $oldMarriagePartner['hochzeitsort'], $oldMarriagePartner['hochzeitsterritorium'], $oldMarriagePartner['aufgebot'], $oldMarriagePartner['auflösung'], $oldMarriagePartner['gelöst'], $oldMarriagePartner['verheiratet'], $oldMarriagePartner['vorher-nachher'], $comment);
    }

    private function getMarriagePartnerWithNativeQuery($oldPersonID, $oldDBManager) {
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

    private function migrateChild($newPerson, $newMarriagePartner, $marriageOrder, $oldPersonID, $oldDBManager) {
        //non paternal
        $children = $this->getChildWithNativeQuery($oldPersonID, $marriageOrder, $oldDBManager);

        for ($i = 0; $i < count($children); $i++) {
            $oldChild = $children[$i];

            //check if reference to person
            if (!is_null($oldChild["kind_id-nr"])) {

                $result = $this->separateReferenceIdsAndComment($oldChild["kind_id-nr"]);

                $referenceIds = $this->extractReferenceIdsArray($result[0]);

                if (count($referenceIds) > 0) {
                    for ($j = 0; $j < count($referenceIds); $j++) {
                        $childsOID = $referenceIds[$j];

                        $childToMerge = $this->createChild($oldChild, $oldPersonID, $oldDBManager, $newPerson);

                        try{
                            $childsMainId = $this->getIDForOID($childsOID, $oldDBManager);
                            $newChildObj = $this->migratePerson($childsMainId, $childsOID);
                            $childToMerge = $this->get("person_merging.service")->mergePersons($childToMerge, $newChildObj);
                        }catch(\UR\AmburgerBundle\Exception\NotFoundException $e){
                            $this->LOGGER->info("Could not found ID for OID: ".$childsOID);
                            $childToMerge->setComment($childToMerge->getComment() ? $childToMerge->getComment()."ReferencedOID: ".$childsOID : "ReferencedOID: ".$childsOID);
                        }

                        $this->getMigrationService()->migrateIsParent($childToMerge, $newPerson, $result[1]);
                        $this->getMigrationService()->migrateIsParent($childToMerge, $newMarriagePartner, $result[1]);
                    }
                    //don't try to load marriage partners etc. because there won't be any and it would not be possible to related them
                } else {
                    $newChild = $this->createChild($oldChild, $oldPersonID, $oldDBManager, $newPerson);

                    $this->getMigrationService()->migrateIsParent($newChild, $newPerson, $result[1]);
                    $this->getMigrationService()->migrateIsParent($newChild, $newMarriagePartner, $result[1]);

                    $this->migrateMarriagePartnersOfChildren($newPerson, $newChild, $marriageOrder, $oldChild['order2'], $oldPersonID, $oldDBManager);
                }
            } else {
                $newChild = $this->createChild($oldChild, $oldPersonID, $oldDBManager, $newPerson);

                $this->getMigrationService()->migrateIsParent($newChild, $newPerson);
                $this->getMigrationService()->migrateIsParent($newChild, $newMarriagePartner);

                $this->migrateMarriagePartnersOfChildren($newPerson, $newChild, $marriageOrder, $oldChild['order2'], $oldPersonID, $oldDBManager);
            }
            //grandchild etc.
        }
    }

    private function createChild($oldChild, $oldPersonID, $oldDBManager, $newPerson) {
        $lastName = $oldChild['name'];
        
        if((is_null($lastName) || $lastName == '' ) && $newPerson->getGender() == \UR\DB\NewBundle\Utils\Gender::MALE){
            $lastName = $newPerson->getLastName();
            $this->getLogger()->info("Setting main persons lastname for this child: ".$lastName);
        }
        
        //$firstName, $patronym, $lastName, $gender, $nation, $comment
        $child = $this->getMigrationService()->migrateRelative($oldChild["vornamen"], $oldChild["russ_vornamen"], $lastName, $oldChild["geschlecht"], null, $oldChild["kommentar"]);

        $this->trackOriginOfData($child->getId(), $oldChild["ID"], 'kind', $oldChild["order"], $oldChild["order2"]); 
        
        $child->setForeName($this->getNormalizationService()->writeOutNameAbbreviations($oldChild["rufnamen"]));

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
        if (!is_null($oldChild["geboren"]) || !is_null($oldChild["geburtsort"]) || count($childOrigin) > 0) {

            if (count($childOrigin) == 0) {
                $this->getMigrationService()->migrateBirth($child, null, null, null, null, $oldChild["geburtsort"], $oldChild["geboren"]);
            } else {
                for ($i = 0; $i < count($childOrigin); $i++) {
                    $origin = $childOrigin[$i];

                    $geburtsOrt = $oldChild["geburtsort"];
                    $geboren = $oldChild["geboren"];

                    if (!is_null($origin['geburtsort']) && $origin['geburtsort'] != $geburtsOrt) {
                        if (!is_null($geburtsOrt)) {
                            // add it with oder
                            $geburtsOrt .= " ODER " . $origin['geburtsort'];
                        } else {
                            $geburtsOrt = $origin['geburtsort'];
                        }
                    }

                    if (!is_null($origin['geboren']) && $origin['geboren'] != $geboren) {
                        if (!is_null($geboren)) {
                            //create date array? add comment?
                            $geboren .= ";" . $origin['geboren'];
                        } else {
                            $geboren = $origin['geboren'];
                        }
                    }

                    if (!is_null($geboren) || !is_null($geburtsOrt) || !is_null($origin['geburtsland']) || !is_null($origin['geburtsterritorium']) || !is_null($origin['kommentar']) || !is_null($origin['belegt'])) {
                        //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
                        $this->getMigrationService()->migrateBirth($child, null, null, null, $origin["geburtsland"], $geburtsOrt, $geboren, $origin['geburtsterritorium'], $origin['kommentar'], $origin['belegt']);
                    }


                    if (!is_null($origin['getauft']) || !is_null($origin['taufort'])) {
                        $this->getMigrationService()->migrateBaptism($child, $origin["getauft"], $origin["taufort"]);
                    }
                }
            }
        }

        if (count($childEducation) > 0) {
            //education
            for ($i = 0; $i < count($childEducation); $i++) {
                $education = $childEducation[$i];
                $educationID = $this->getMigrationService()->migrateEducation($child, $education["order3"], $education["ausbildung"], $education["land"], null, $education["ort"], $education["von-ab"], $education["bis"], $education["belegt"], $education["bildungsabschluss"], $education["bildungsabschlussdatum"], $education["bildungsabschlussort"], $education["kommentar"]);
            }
        }


        if (count($childProperty) > 0) {
            //property
            for ($i = 0; $i < count($childProperty); $i++) {
                $property = $childProperty[$i];
                //$propertyOrder, $label, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $comment=null
                $this->getMigrationService()->migrateProperty($child, $property["order3"], $property["besitz"], $property["land"], $property['territorium'], $property["ort"], $property["von-ab"], null, $property["belegt"]);
            }
        }


        if (count($childHonour) > 0) {
            //honour
            for ($i = 0; $i < count($childHonour); $i++) {
                $honour = $childHonour[$i];
                //$honourOrder, $label, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $comment=null
                $this->getMigrationService()->migrateHonour($child, $honour["order3"], $honour["ehren"], $honour["land"], null, $honour["ort"], $honour["von-ab"]);
            }
        }

        if (count($childRoadOfLife) > 0) {
            //roadOfLife
            for ($i = 0; $i < count($childRoadOfLife); $i++) {
                $step = $childRoadOfLife[$i];
                //$roadOfLifeOrder, $originCountry=null, $originTerritory=null, $job=null, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $comment=null
                $this->getMigrationService()->migrateRoadOfLife($child, $step["order3"], $step["stammland"], null, $step["beruf"], $step["land"], $step["territorium"], $step["ort"], $step["von-ab"], $step["bis"], $step["belegt"], $step["kommentar"]);
            }
        }


        if (count($childRank) > 0) {
            //rank
            for ($i = 0; $i < count($childRank); $i++) {
                $rank = $childRank[$i];
                //$rankOrder, $label, $class=null, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $comment=null
                $this->getMigrationService()->migrateRank($child, $rank["order3"], $rank["rang"], $rank["rangklasse"], $rank["land"], null, $rank["ort"], $rank["von-ab"], $rank["bis"], $rank["belegt"], $rank["kommentar"]);
            }
        }

        //religoin
        if (count($childReligion) > 0) {
            //rank
            for ($i = 0; $i < count($childReligion); $i++) {
                $religion = $childReligion[$i];
                //$name, $religionOrder, $change_of_religion=null, $provenDate=null, $fromDate=null, $comment=null
                $this->getMigrationService()->migrateReligion($child, $religion["konfession"], $religion["order3"], null, null, null, $religion["kommentar"]);
            }
        }


        if (count($childStatus) > 0) {
            //status
            for ($i = 0; $i < count($childStatus); $i++) {
                $status = $childStatus[$i];
                //$statusOrder, $label, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $comment=null
                $this->getMigrationService()->migrateStatus($child, $status["order3"], $status["stand"], $status["land"], $status["territorium"], $status["ort"], $status["von-ab"], null, $status["belegt"], $status["kommentar"]);
            }
        }


        if (count($childDeath) > 0) {
            //death
            for ($i = 0; $i < count($childDeath); $i++) {
                $death = $childDeath[$i];
                //death
                //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
                $this->getMigrationService()->migrateDeath($child, $death["todesort"], $death["gestorben"], $death["todesland"], $death["todesursache"], $death["todesterritorium"], $death["friedhof"], $death["begräbnisort"], $death["begraben"], $death["kommentar"]);
            }
        }

        return $child;
    }

    private function getChildWithNativeQuery($oldPersonID, $marriageOrder, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, vornamen, russ_vornamen, name, rufnamen, geboren, geburtsort, geschlecht, `kind_id-nr`, kommentar
                    FROM `kind` WHERE ID=:personID AND `order`=:marriageOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getChildsEducationWithNativeQuery($oldPersonID, $marriageOrder, $childOrder, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, order3, land, ort, ausbildung, `von-ab`, bis, bildungsabschluss, bildungsabschlussdatum, bildungsabschlussort, belegt, kommentar
            FROM `ausbildung_des_kindes` WHERE ID=:personID AND `order`=:marriageOrder AND `order2`=:childOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getChildsPropertyWithNativeQuery($oldPersonID, $marriageOrder, $childOrder, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, order3, land, ort, territorium, besitz, `von-ab`, belegt
                FROM `besitz_des_kindes` WHERE ID=:personID AND `order`=:marriageOrder AND `order2`=:childOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function getChildsHonourWithNativeQuery($oldPersonID, $marriageOrder, $childOrder, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, order3, ort, land, `von-ab`, ehren
                    FROM `ehren_des_kindes` WHERE ID=:personID AND `order`=:marriageOrder AND `order2`=:childOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getChildsOriginWithNativeQuery($oldPersonID, $marriageOrder, $childOrder, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, order3, geboren, geburtsort, geburtsterritorium, geburtsland, getauft, taufort, belegt, kommentar
                FROM `herkunft_des_kindes` WHERE ID=:personID AND `order`=:marriageOrder AND `order2`=:childOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getChildsRoadOfLifeWithNativeQuery($oldPersonID, $marriageOrder, $childOrder, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, order3, ort, territorium, land, stammland, beruf, `von-ab`, bis, belegt, kommentar
                FROM `lebensweg_des_kindes` WHERE ID=:personID AND `order`=:marriageOrder AND `order2`=:childOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getChildsRankWithNativeQuery($oldPersonID, $marriageOrder, $childOrder, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, order3, ort, land, rang, rangklasse, `von-ab`, bis, belegt, kommentar
                FROM `rang_des_kindes` WHERE ID=:personID AND `order`=:marriageOrder AND `order2`=:childOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getChildsReligionWithNativeQuery($oldPersonID, $marriageOrder, $childOrder, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, order3, konfession, kommentar
                FROM `religion_des_kindes` WHERE ID=:personID AND `order`=:marriageOrder AND `order2`=:childOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getChildsStatusWithNativeQuery($oldPersonID, $marriageOrder, $childOrder, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, order3, ort, territorium, land, stand, `von-ab`, belegt, kommentar
                FROM `stand_des_kindes` WHERE ID=:personID AND `order`=:marriageOrder AND `order2`=:childOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getChildsDeathWithNativeQuery($oldPersonID, $marriageOrder, $childOrder, $oldDBManager) {
        $sql = "SELECT `ID`,`order`,`order2`,`order3`,`todesort`,`todesterritorium`,`gestorben`,`begräbnisort`,`todesursache`,`friedhof`,`begraben`,`todesland`,`kommentar` 
                FROM `tod_des_kindes` WHERE ID=:personID AND `order`=:marriageOrder AND `order2`=:childOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function migrateFatherInLaw($newPerson, $newMarriagePartner, $marriageOrder, $newMotherInLaw, $motherInLawOrder, $oldPersonID, $oldDBManager) {
        $fathersInLaw = $this->getFatherInLawWithNativeQuery($oldPersonID, $marriageOrder, $motherInLawOrder, $oldDBManager);

        for ($i = 0; $i < count($fathersInLaw); $i++) {
            $oldFatherInLaw = $fathersInLaw[$i];

            $newFatherInLaw = $this->createFatherInLaw($oldFatherInLaw);
            
            $comment = null;
            
            //check if reference to person
            if (!is_null($oldFatherInLaw["schwiegervater_id-nr"])) {
                $referenceValue = $oldFatherInLaw["schwiegervater_id-nr"];
                
                $result = $this->separateReferenceIdsAndComment($referenceValue);
                
                if(!is_null($result[0])){
                    //check it?
                    $fatherInLawOID = $result[0];
                    
                    try{
                        $fatherInLawsMainId = $this->getIDForOID($fatherInLawOID, $oldDBManager);
                        $newFatherInLawObj = $this->migratePerson($fatherInLawsMainId, $fatherInLawOID);
                        $newFatherInLaw = $this->get("person_merging.service")->mergePersons($newFatherInLaw, $newFatherInLawObj);
                    }catch(\UR\AmburgerBundle\Exception\NotFoundException $e){
                        $this->LOGGER->info("Could not found ID for OID: ".$fatherInLawOID);
                        $newFatherInLaw->setComment($newFatherInLaw->getComment() ? $newFatherInLaw->getComment()."ReferencedOID: ".$fatherInLawOID : "ReferencedOID: ".$fatherInLawOID);
                    }

                } else {
                    $newFatherInLaw = $this->migratePerson($fatherInLawsMainId, $fatherInLawOID);
                }
                
                $comment = $result[1];
            }

            $this->migrateWeddingOfParentsInLaw($newFatherInLaw, $newMotherInLaw, $oldFatherInLaw);

            $this->getMigrationService()->migrateIsParentInLaw($newPerson, $newFatherInLaw, $comment);
            $this->getMigrationService()->migrateIsParent($newMarriagePartner, $newFatherInLaw, $comment);
        }
    }

    private function migrateWeddingOfParentsInLaw($newFatherInLaw, $newMotherInLaw, $oldFatherInLaw) {
        //$weddingOrder, $husband, $wife, $weddingDateid, $weddingLocationid, $weddingTerritoryid, $bannsDateid, $breakupReason, $breakupDateid, $marriageComment, $beforeAfter, $comment
        $this->getMigrationService()->migrateWedding(1, $newFatherInLaw, $newMotherInLaw, null, $oldFatherInLaw['hochzeitsort']);
    }

    private function createFatherInLaw($oldFatherInLaw) {
        //$firstName, $patronym, $lastName, $gender, $nation, $comment
        $fatherInLaw = $this->getMigrationService()->migrateRelative($oldFatherInLaw["vornamen"], $oldFatherInLaw["russ_vornamen"], $oldFatherInLaw["name"], "männlich", $oldFatherInLaw["nation"], $oldFatherInLaw["kommentar"]);

        $this->trackOriginOfData($fatherInLaw->getId(), $oldFatherInLaw["ID"], 'schwiegervater', $oldFatherInLaw["order"], $oldFatherInLaw["order2"]); 

        //birth
        if (!is_null($oldFatherInLaw["herkunftsort"]) ||
                !is_null($oldFatherInLaw["herkunftsterritorium"]) ||
                !is_null($oldFatherInLaw["geboren"])) {
            //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
            $this->getMigrationService()->migrateBirth($fatherInLaw, null, $oldFatherInLaw["herkunftsterritorium"], $oldFatherInLaw["herkunftsort"], null, null, $oldFatherInLaw["geboren"]);
        }

        //baptism
        if (!is_null($oldFatherInLaw["getauft"]) ||
                !is_null($oldFatherInLaw["taufort"])) {
            $this->getMigrationService()->migrateBaptism($fatherInLaw, $oldFatherInLaw["getauft"], $oldFatherInLaw["taufort"]);
        }

        //death
        if (!is_null($oldFatherInLaw["gestorben"]) ||
                !is_null($oldFatherInLaw["todesort"]) ||
                !is_null($oldFatherInLaw["begraben"]) ||
                !is_null($oldFatherInLaw["begräbnisort"])) {
            //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
            $this->getMigrationService()->migrateDeath($fatherInLaw, $oldFatherInLaw["todesort"], $oldFatherInLaw["gestorben"], null, null, null, null, $oldFatherInLaw["begräbnisort"], $oldFatherInLaw["begraben"]);
        }

        //residence
        if (!is_null($oldFatherInLaw["wohnort"]) ||
                !is_null($oldFatherInLaw["wohnterritorium"]) ||
                !is_null($oldFatherInLaw["wohnland"])) {
            $this->getMigrationService()->migrateResidence($fatherInLaw, 1, $oldFatherInLaw["wohnland"], $oldFatherInLaw["wohnterritorium"], $oldFatherInLaw["wohnort"]);
        }

        //religion
        if (!is_null($oldFatherInLaw["konfession"])) {
            $this->getMigrationService()->migrateReligion($fatherInLaw, $oldFatherInLaw["konfession"], 1);
        }

        //status
        if (!is_null($oldFatherInLaw["stand"])) {
            $this->getMigrationService()->migrateStatus($fatherInLaw, 1, $oldFatherInLaw["stand"]);
        }

        //rank
        if (!is_null($oldFatherInLaw["rang"])) {
            $this->getMigrationService()->migrateRank($fatherInLaw, 1, $oldFatherInLaw["rang"]);
        }


        //property
        if (!is_null($oldFatherInLaw["besitz"])) {
            $this->getMigrationService()->migrateProperty($fatherInLaw, 1, $oldFatherInLaw["besitz"]);
        }


        //job
        if (!is_null($oldFatherInLaw["beruf"])) {
            $jobID = $this->getMigrationService()->migrateJob($oldFatherInLaw["beruf"]);

            $fatherInLaw->setJob($jobID);
        }

        //education
        if (!is_null($oldFatherInLaw["bildungsabschluss"])) {
            //$educationOrder, $label, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $graduationLabel=null, $graduationDate=null, $graduationLocation=null, $comment=null
            $this->getMigrationService()->migrateEducation($fatherInLaw, 1, null, null, null, null, null, null, null, $oldFatherInLaw["bildungsabschluss"]);
        }

        //honour
        if (!is_null($oldFatherInLaw["ehren"])) {
            $this->getMigrationService()->migrateHonour($fatherInLaw, 1, $oldFatherInLaw["ehren"]);
        }

        //born_in_marriage
        if (!is_null($oldFatherInLaw["ehelich"])) {
            $fatherInLaw->setBornInMarriage($this->getNormalizationService()->writeOutAbbreviations($oldFatherInLaw["ehelich"]));
        }

        return $fatherInLaw;
    }

    private function getFatherInLawWithNativeQuery($oldPersonID, $marriageOrder, $motherInLawOrder, $oldDBManager) {
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

    private function migrateMotherInLaw($newPerson, $newMarriagePartner, $marriageOrder, $oldPersonID, $oldDBManager) {
        $mothersInLaw = $this->getMotherInLawWithNativeQuery($oldPersonID, $marriageOrder, $oldDBManager);

        for ($i = 0; $i < count($mothersInLaw); $i++) {
            $oldMotherInLaw = $mothersInLaw[$i];
            $newMotherInLaw = $this->createMotherInLaw($newPerson, $newMarriagePartner, $oldMotherInLaw);
            $this->migrateFatherInLaw($newPerson, $newMarriagePartner, $marriageOrder, $newMotherInLaw, $oldMotherInLaw['order2'], $oldPersonID, $oldDBManager);
        }
    }

    private function createMotherInLaw($newPerson, $newMarriagePartner, $oldMotherInLaw) {
        //$firstName, $patronym, $lastName, $gender, $nation, $comment
        $motherInLaw = $this->getMigrationService()->migrateRelative($oldMotherInLaw["vornamen"], $oldMotherInLaw["russ_vornamen"], $oldMotherInLaw["name"], "weiblich", $oldMotherInLaw["nation"], $oldMotherInLaw["kommentar"]);

        $this->trackOriginOfData($motherInLaw->getId(), $oldMotherInLaw["ID"], 'schwiegermutter', $oldMotherInLaw["order"], $oldMotherInLaw["order2"]);   

        //birth
        if (!is_null($oldMotherInLaw["herkunftsort"]) ||
                !is_null($oldMotherInLaw["geburtsort"]) ||
                !is_null($oldMotherInLaw["geboren"])) {
            //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
            $this->getMigrationService()->migrateBirth($motherInLaw, null, null, $oldMotherInLaw["herkunftsort"], $oldMotherInLaw["geburtsort"], null, $oldMotherInLaw["geboren"]);
        }

        //death
        if (!is_null($oldMotherInLaw["gestorben"]) ||
                !is_null($oldMotherInLaw["todesort"])) {
            //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
            $this->getMigrationService()->migrateDeath($motherInLaw, $oldMotherInLaw["todesort"], $oldMotherInLaw["gestorben"]);
        }

        //status
        if (!is_null($oldMotherInLaw["stand"])) {
            $this->getMigrationService()->migrateStatus($motherInLaw, 1, $oldMotherInLaw["stand"]);
        }


        //job
        if (!is_null($oldMotherInLaw["beruf"])) {
            $jobID = $this->getMigrationService()->migrateJob($oldMotherInLaw["beruf"]);

            $motherInLaw->setJob($jobID);
        }

        //born_in_marriage
        if (!is_null($oldMotherInLaw["ehelich"])) {
            $motherInLaw->setBornInMarriage($this->getNormalizationService()->writeOutAbbreviations($oldMotherInLaw["ehelich"]));
        }

        $this->getMigrationService()->migrateIsParentInLaw($newPerson, $motherInLaw);
        $this->getMigrationService()->migrateIsParent($newMarriagePartner, $motherInLaw);

        return $motherInLaw;
    }

    private function getMotherInLawWithNativeQuery($oldPersonID, $marriageOrder, $oldDBManager) {
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
    private function migrateOtherPartners($newMarriagePartner, $marriageOrder, $oldPersonID, $oldDBManager) {
        $otherPartners = $this->getOtherPartnersWithNativeQuery($oldPersonID, $marriageOrder, $oldDBManager);

        for ($i = 0; $i < count($otherPartners); $i++) {
            $oldOtherPartner = $otherPartners[$i];

            $newOtherParner = $this->createOtherPartners($newMarriagePartner, $oldOtherPartner);
            //check if reference to person
            if (!is_null($oldOtherPartner["partnerpartner_id-nr"])) {
                //check it?
                $partnerPartnerOID = $oldOtherPartner["partnerpartner_id-nr"];
                
                try{
                    $partnerPartnerMainId = $this->getIDForOID($partnerPartnerOID, $oldDBManager);
                    $newPartnerPartner = $this->migratePerson($partnerPartnerMainId, $partnerPartnerOID);
                    $newOtherParner = $this->get("person_merging.service")->mergePersons($newOtherParner, $newPartnerPartner);
                }catch(\UR\AmburgerBundle\Exception\NotFoundException $e){
                    $this->LOGGER->info("Could not found ID for OID: ".$partnerPartnerOID);
                    $newOtherParner->setComment($newOtherParner->getComment() ? $newOtherParner->getComment()."ReferencedOID: ".$partnerPartnerOID : "ReferencedOID: ".$partnerPartnerOID);
                }
            }

            $this->migrateWeddingOfOtherPartners($newOtherParner, $newMarriagePartner, $oldOtherPartner);
        }
    }

    private function migrateWeddingOfOtherPartners($newPartnerPartner, $newMarriagePartner, $oldOtherPartners) {
        //$weddingOrder, $husband, $wife, $weddingDateid, $weddingLocationid, $weddingTerritoryid, $bannsDateid, $breakupReason, $breakupDateid, $marriageComment, $beforeAfter, $comment
        $this->getMigrationService()->migrateWedding($oldOtherPartners['order2'], $newPartnerPartner, $newMarriagePartner, $oldOtherPartners['hochzeitstag'], $oldOtherPartners['hochzeitsort'], null, $oldOtherPartners['aufgebot'], $oldOtherPartners['auflösung'], $oldOtherPartners['gelöst'], $oldOtherPartners['verheiratet'], $oldOtherPartners['vorher-nachher'], null);
    }

    private function createOtherPartners($newMarriagePartner, $oldOtherPartner) {
        //$firstName, $patronym, $lastName, $gender, $nation, $comment
        $gender = $this->getOppositeGender($newMarriagePartner);
        $otherPartner = $this->getMigrationService()->migratePartner($oldOtherPartner["vornamen"], $oldOtherPartner["russ_vornamen"], $oldOtherPartner["name"], $gender, null, $oldOtherPartner["kommentar"]);

        $this->trackOriginOfData($otherPartner->getId(), $oldOtherPartner["ID"], 'anderer_partner', $oldOtherPartner["order"], $oldOtherPartner["order2"]);   
        
        
        //birth
        if (!is_null($oldOtherPartner["herkunftsort"]) ||
                !is_null($oldOtherPartner["herkunftsterritorium"]) ||
                !is_null($oldOtherPartner["geburtsort"]) ||
                !is_null($oldOtherPartner["geboren"])) {
            //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
            $this->getMigrationService()->migrateBirth($otherPartner, null, $oldOtherPartner["herkunftsterritorium"], $oldOtherPartner["herkunftsort"], null, $oldOtherPartner["geburtsort"], $oldOtherPartner["geboren"]);
        }

        //death
        if (!is_null($oldOtherPartner["gestorben"]) ||
                !is_null($oldOtherPartner["friedhof"]) ||
                !is_null($oldOtherPartner["todesort"])) {
            //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
            $this->getMigrationService()->migrateDeath($otherPartner, $oldOtherPartner["todesort"], $oldOtherPartner["gestorben"], null, null, null, $oldOtherPartner["friedhof"]);
        }

        //religion
        if (!is_null($oldOtherPartner["konfession"])) {
            $this->getMigrationService()->migrateReligion($otherPartner, $oldOtherPartner["konfession"], 1);
        }

        //status
        if (!is_null($oldOtherPartner["stand"])) {
            $this->getMigrationService()->migrateStatus($otherPartner, 1, $oldOtherPartner["stand"]);
        }

        //rank
        if (!is_null($oldOtherPartner["rang"])) {
            $this->getMigrationService()->migrateRank($otherPartner, 1, $oldOtherPartner["rang"]);
        }


        //property
        if (!is_null($oldOtherPartner["besitz"])) {
            $this->getMigrationService()->migrateProperty($otherPartner, 1, $oldOtherPartner["besitz"]);
        }


        //job
        if (!is_null($oldOtherPartner["beruf"])) {
            $jobID = $this->getMigrationService()->migrateJob($oldOtherPartner["beruf"]);

            $otherPartner->setJob($jobID);
        }

        //education
        if (!is_null($oldOtherPartner["bildungsabschluss"])) {
            //$educationOrder, $label, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $graduationLabel=null, $graduationDate=null, $graduationLocation=null, $comment=null
            $this->getMigrationService()->migrateEducation($otherPartner, 1, null, null, null, null, null, null, null, $oldOtherPartner["bildungsabschluss"]);
        }

        //honour
        if (!is_null($oldOtherPartner["ehren"])) {
            $this->getMigrationService()->migrateHonour($otherPartner, 1, $oldOtherPartner["ehren"]);
        }

        return $otherPartner;
    }

    private function getOtherPartnersWithNativeQuery($oldPersonID, $marriageOrder, $oldDBManager) {
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
    private function migratePartnersOfFather($newFather, $oldPersonID, $oldDBManager) {
        $this->LOGGER->info("Migratin partners of " . $newFather);
        $partnersOfFather = $this->getPartnersOfFatherWithNativeQuery($oldPersonID, $oldDBManager);

        for ($i = 0; $i < count($partnersOfFather); $i++) {
            $oldPartnersOfFather = $partnersOfFather[$i];
            $this->createPartnersOfFather($newFather, $oldPartnersOfFather);
        }
    }

    private function createPartnersOfFather($newFather, $oldPartnersOfFather) {
        $partnerOfFather = $this->getMigrationService()->migratePartner($oldPartnersOfFather["vornamen"], null, $oldPartnersOfFather["name"], "weiblich", null, $oldPartnersOfFather["kommentar"]);

        $this->trackOriginOfData($partnerOfFather->getId(), $oldPartnersOfFather["ID"], 'partnerin_des_vaters', $oldPartnersOfFather["order"], $oldPartnersOfFather["order2"]);   
        
        //birth
        if (!is_null($oldPartnersOfFather["geburtsort"]) ||
                !is_null($oldPartnersOfFather["geboren"])) {
            //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
            $this->getMigrationService()->migrateBirth($partnerOfFather, null, null, null, $oldPartnersOfFather["geburtsort"], null, $oldPartnersOfFather["geboren"]);
        }

        //baptism
        if (!is_null($oldPartnersOfFather["getauft"]) ||
                !is_null($oldPartnersOfFather["taufort"])) {
            $this->getMigrationService()->migrateBaptism($partnerOfFather, $oldPartnersOfFather["getauft"], $oldPartnersOfFather["taufort"]);
        }


        //death
        if (!is_null($oldPartnersOfFather["gestorben"]) ||
                !is_null($oldPartnersOfFather["todesort"])) {
            //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
            $this->getMigrationService()->migrateDeath($partnerOfFather, $oldPartnersOfFather["todesort"], $oldPartnersOfFather["gestorben"]);
        }

        //status
        if (!is_null($oldPartnersOfFather["stand"])) {
            $this->getMigrationService()->migrateStatus($partnerOfFather, 1, $oldPartnersOfFather["stand"]);
        }

        //job
        if (!is_null($oldPartnersOfFather["beruf"])) {
            $jobID = $this->getMigrationService()->migrateJob($oldPartnersOfFather["beruf"]);

            $partnerOfFather->setJob($jobID);
        }

        $this->getMigrationService()->migrateWedding($oldPartnersOfFather['order2'], $newFather, $partnerOfFather, $oldPartnersOfFather['hochzeitstag'], $oldPartnersOfFather['hochzeitsort'], null, null, null, null, $oldPartnersOfFather['verheiratet'], $oldPartnersOfFather['vorher-nachher'], null);
    }

    private function getPartnersOfFatherWithNativeQuery($oldPersonID, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, vornamen, name, geboren, geburtsort, getauft, 
        taufort, gestorben, todesort, verheiratet, hochzeitstag, hochzeitsort, beruf, stand, `vorher-nachher`, kommentar
        FROM `partnerin_des_vaters` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function migratePartnersOfMother($newMother, $oldPersonID, $oldDBManager) {
        $partnersOfMother = $this->getPartnersOfMotherWithNativeQuery($oldPersonID, $oldDBManager);

        for ($i = 0; $i < count($partnersOfMother); $i++) {
            $oldPartnersOfMother = $partnersOfMother[$i];
            $this->createPartnersOfMother($newMother, $oldPartnersOfMother);
        }
    }

    private function createPartnersOfMother($newMother, $oldPartnersOfMother) {
        $partnerOfMother = $this->getMigrationService()->migratePartner($oldPartnersOfMother["vornamen"], null, $oldPartnersOfMother["name"], "männlich", null, $oldPartnersOfMother["kommentar"]);

        $this->trackOriginOfData($partnerOfMother->getId(), $oldPartnersOfMother["ID"], 'partner_der_mutter', $oldPartnersOfMother["order"], $oldPartnersOfMother["order2"]); 
        
        //status
        if (!is_null($oldPartnersOfMother["stand"])) {
            $this->getMigrationService()->migrateStatus($partnerOfMother, 1, $oldPartnersOfMother["stand"]);
        }

        //rank
        if (!is_null($oldPartnersOfMother["rang"])) {
            $this->getMigrationService()->migrateRank($partnerOfMother, 1, $oldPartnersOfMother["rang"]);
        }

        //job
        if (!is_null($oldPartnersOfMother["beruf"])) {
            $jobID = $this->getMigrationService()->migrateJob($oldPartnersOfMother["beruf"]);

            $partnerOfMother->setJob($jobID);
        }

        //belegt
        //$weddingOrder, $husband, $wife, $weddingDateid, $weddingLocationid, $weddingTerritoryid, $bannsDateid, $breakupReason, $breakupDateid, $marriageComment, $beforeAfter, $comment
        $this->getMigrationService()->migrateWedding($oldPartnersOfMother['order2'], $newMother, $partnerOfMother, $oldPartnersOfMother['hochzeitstag'], $oldPartnersOfMother['hochzeitsort'], null, null, $oldPartnersOfMother['auflösung'], $oldPartnersOfMother['gelöst'], $oldPartnersOfMother['verheiratet'], $oldPartnersOfMother['vorher-nachher'], null, $oldPartnersOfMother['belegt']);
    }

    private function getPartnersOfMotherWithNativeQuery($oldPersonID, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, vornamen, name, verheiratet, hochzeitstag, hochzeitsort, 
        auflösung, gelöst, `vorher-nachher`, rang, beruf, stand, belegt, kommentar
        FROM `partner_der_mutter` WHERE ID=:personID";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function migrateMarriagePartnersOfSibling($newSibling, $siblingOrder, $oldPersonID, $oldDBManager) {
        $marriagePartnersOfSibling = $this->getMarriagePartnersOfSiblingWithNativeQuery($oldPersonID, $siblingOrder, $oldDBManager);

        for ($i = 0; $i < count($marriagePartnersOfSibling); $i++) {
            $oldMarriagePartnersOfSibling = $marriagePartnersOfSibling[$i];

            $newMarriagePartner = $this->createMarriagePartnersOfSibling($newSibling, $oldMarriagePartnersOfSibling);

            if (!is_null($oldMarriagePartnersOfSibling["geschwisterpartner_id-nr"])) {
                //check it?
                $marriagePartnersOfSiblingOID = $oldMarriagePartnersOfSibling["geschwisterpartner_id-nr"];
                   
                try{
                    $marriagePartnersOfSiblingMainId = $this->getIDForOID($marriagePartnersOfSiblingOID, $oldDBManager);
                    $newMarriagePartnerObj = $this->migratePerson($marriagePartnersOfSiblingMainId, $marriagePartnersOfSiblingOID);
                    $newMarriagePartner = $this->get("person_merging.service")->mergePersons($newMarriagePartner, $newMarriagePartnerObj);
                }catch(\UR\AmburgerBundle\Exception\NotFoundException $e){
                    $this->LOGGER->info("Could not found ID for OID: ".$marriagePartnersOfSiblingOID);
                    $newMarriagePartner->setComment($newMarriagePartner->getComment() ? $newMarriagePartner->getComment()."ReferencedOID: ".$marriagePartnersOfSiblingOID : "ReferencedOID: ".$marriagePartnersOfSiblingOID);
                }
            }

            $this->migrateWeddingOfSibling($newSibling, $newMarriagePartner, $oldMarriagePartnersOfSibling);

            $this->migrateChildrenOfSibling($newSibling, $siblingOrder, $newMarriagePartner, $oldMarriagePartnersOfSibling["order2"], $oldPersonID, $oldDBManager);
            //migrate children of marriage partner                 
        }
    }

    private function createMarriagePartnersOfSibling($newSibling, $oldMarriagePartnersOfSibling) {
        $gender = $this->getOppositeGender($newSibling);
        $marriagePartnersOfSibling = $this->getMigrationService()->migratePartner($oldMarriagePartnersOfSibling["vornamen"], $oldMarriagePartnersOfSibling["russ_vornamen"], $oldMarriagePartnersOfSibling["name"], $gender, null, $oldMarriagePartnersOfSibling["kommentar"]);

        $this->trackOriginOfData($marriagePartnersOfSibling->getId(), $oldMarriagePartnersOfSibling["ID"], 'ehepartner_des_geschwisters', $oldMarriagePartnersOfSibling["order"], $oldMarriagePartnersOfSibling["order2"]); 
        
        //birth
        if (!is_null($oldMarriagePartnersOfSibling["herkunftsort"]) ||
                !is_null($oldMarriagePartnersOfSibling["geboren"])) {
            //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
            $this->getMigrationService()->migrateBirth($marriagePartnersOfSibling, null, null, $oldMarriagePartnersOfSibling["herkunftsort"], null, null, $oldMarriagePartnersOfSibling["geboren"]);
        }

        //death
        if (!is_null($oldMarriagePartnersOfSibling["gestorben"]) ||
                !is_null($oldMarriagePartnersOfSibling["friedhof"]) ||
                !is_null($oldMarriagePartnersOfSibling["begräbnisort"]) ||
                !is_null($oldMarriagePartnersOfSibling["begraben"])) {
            //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
            $this->getMigrationService()->migrateDeath($marriagePartnersOfSibling, null, $oldMarriagePartnersOfSibling["gestorben"], null, null, null, $oldMarriagePartnersOfSibling["friedhof"], $oldMarriagePartnersOfSibling["begräbnisort"], $oldMarriagePartnersOfSibling["begraben"]);
        }

        //religion
        if (!is_null($oldMarriagePartnersOfSibling["konfession"])) {
            $this->getMigrationService()->migrateReligion($marriagePartnersOfSibling, $oldMarriagePartnersOfSibling["konfession"], 1);
        }

        //status
        if (!is_null($oldMarriagePartnersOfSibling["stand"])) {
            $this->getMigrationService()->migrateStatus($marriagePartnersOfSibling, 1, $oldMarriagePartnersOfSibling["stand"]);
        }

        //rank
        if (!is_null($oldMarriagePartnersOfSibling["rang"])) {
            $this->getMigrationService()->migrateRank($marriagePartnersOfSibling, 1, $oldMarriagePartnersOfSibling["rang"]);
        }


        //job
        if (!is_null($oldMarriagePartnersOfSibling["beruf"])) {
            $jobID = $this->getMigrationService()->migrateJob($oldMarriagePartnersOfSibling["beruf"]);

            $marriagePartnersOfSibling->setJob($jobID);
        }

        //education
        if (!is_null($oldMarriagePartnersOfSibling["bildungsabschluss"])) {
            //$educationOrder, $label, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $graduationLabel=null, $graduationDate=null, $graduationLocation=null, $comment=null
            $this->getMigrationService()->migrateEducation($marriagePartnersOfSibling, 1, null, null, null, null, null, null, null, $oldMarriagePartnersOfSibling["bildungsabschluss"]);
        }

        //honour
        if (!is_null($oldMarriagePartnersOfSibling["ehren"])) {
            $this->getMigrationService()->migrateHonour($marriagePartnersOfSibling, 1, $oldMarriagePartnersOfSibling["ehren"]);
        }


        //verheiratet, 
        //hochzeitstag, hochzeitsort, auflösung,`vorher-nachher`,

        return $marriagePartnersOfSibling;
    }

    private function migrateWeddingOfSibling($newSibling, $marriagePartner, $oldMarriagePartnersOfSibling) {
        //$weddingOrder, $husband, $wife, $weddingDateid, $weddingLocationid, $weddingTerritoryid, $bannsDateid, $breakupReason, $breakupDateid, $marriageComment, $beforeAfter, $comment
        $this->getMigrationService()->migrateWedding($oldMarriagePartnersOfSibling['order2'], $newSibling, $marriagePartner, $oldMarriagePartnersOfSibling['hochzeitstag'], $oldMarriagePartnersOfSibling['hochzeitsort'], null, null, $oldMarriagePartnersOfSibling['auflösung'], null, $oldMarriagePartnersOfSibling['verheiratet'], $oldMarriagePartnersOfSibling['vorher-nachher'], null);
    }

    private function getMarriagePartnersOfSiblingWithNativeQuery($oldPersonID, $siblingOrder, $oldDBManager) {
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

    private function migrateChildrenOfSibling($newSibling, $siblingOrder, $newMarriagePartner, $marriageOrder, $oldPersonID, $oldDBManager) {
        $childrenOfSibling = $this->getChildrenOfSiblingWithNativeQuery($oldPersonID, $siblingOrder, $marriageOrder, $oldDBManager);

        for ($i = 0; $i < count($childrenOfSibling); $i++) {
            $oldChildrenOfSibling = $childrenOfSibling[$i];
            $this->createChildrenOfSibling($oldChildrenOfSibling, $newSibling, $newMarriagePartner);
        }
    }

    private function createChildrenOfSibling($oldChildOfSibling, $newSibling, $newMarriagePartner) {
        $childOfSibling = $this->getMigrationService()->migrateRelative($oldChildOfSibling["vornamen"], null, $oldChildOfSibling["name"], $oldChildOfSibling["geschlecht"], null, $oldChildOfSibling["kommentar"]);

        $this->trackOriginOfData($childOfSibling->getId(), $oldChildOfSibling["ID"], 'geschwisterkind', $oldChildOfSibling["order"], $oldChildOfSibling["order2"], $oldChildOfSibling["order3"]); 
        
        //birth
        if (!is_null($oldChildOfSibling["geburtsort"]) ||
                !is_null($oldChildOfSibling["geboren"])) {
            //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
            $this->getMigrationService()->migrateBirth($childOfSibling, null, null, null, null, $oldChildOfSibling["geburtsort"], $oldChildOfSibling["geboren"]);
        }

        //baptism
        if (!is_null($oldChildOfSibling["getauft"]) ||
                !is_null($oldChildOfSibling["taufort"])) {
            $this->getMigrationService()->migrateBaptism($childOfSibling, $oldChildOfSibling["getauft"], $oldChildOfSibling["taufort"]);
        }

        //death
        if (!is_null($oldChildOfSibling["gestorben"])) {
            //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
            $this->getMigrationService()->migrateDeath($childOfSibling, null, $oldChildOfSibling["gestorben"]);
        }

        //job
        if (!is_null($oldChildOfSibling["beruf"])) {
            $jobID = $this->getMigrationService()->migrateJob($oldChildOfSibling["beruf"]);

            $childOfSibling->setJob($jobID);
        }

        $this->getMigrationService()->migrateIsParent($childOfSibling, $newSibling);
        $this->getMigrationService()->migrateIsParent($childOfSibling, $newMarriagePartner);
    }

    private function getChildrenOfSiblingWithNativeQuery($oldPersonID, $siblingOrder, $marriageOrder, $oldDBManager) {
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

    private function migrateMarriagePartnersOfChildren($newPerson, $newChild, $parentMarriageOrder, $childOrder, $oldPersonID, $oldDBManager) {
        $marriagePartnersOfChildren = $this->getMarriagePartnersOfChildrenWithNativeQuery($oldPersonID, $parentMarriageOrder, $childOrder, $oldDBManager);

        for ($i = 0; $i < count($marriagePartnersOfChildren); $i++) {
            $oldMarriagePartner = $marriagePartnersOfChildren[$i];

            $newMarriagePartner = $this->createMarriagePartnersOfChildren($newChild, $oldMarriagePartner);

            //check if reference to person
            if (!is_null($oldMarriagePartner["kindespartner_id-nr"])) {
                //TODO: think about special case 88558<->87465
                //check it?
                $marriagePartnerOID = $oldMarriagePartner["kindespartner_id-nr"];
                            
                try{
                    $marriagePartnerMainId = $this->getIDForOID($marriagePartnerOID, $oldDBManager);

                    $newMarriagePartnerObj = $this->migratePerson($marriagePartnerMainId, $marriagePartnerOID);

                    $newMarriagePartner = $this->get("person_merging.service")->mergePersons($newMarriagePartner, $newMarriagePartnerObj);
                }catch(\UR\AmburgerBundle\Exception\NotFoundException $e){
                    $this->LOGGER->info("Could not found ID for OID: ".$marriagePartnerOID);
                    $newMarriagePartner->setComment($newMarriagePartner->getComment() ? $newMarriagePartner->getComment()."ReferencedOID: ".$marriagePartnerOID : "ReferencedOID: ".$marriagePartnerOID);
                }
            }

            $this->migrateWeddingOfChildren($newChild, $newMarriagePartner, $oldMarriagePartner);

            //other partners
            $this->migrateOtherPartnersOfChildren($newMarriagePartner, $parentMarriageOrder, $childOrder, $oldMarriagePartner['order3'], $oldPersonID, $oldDBManager);

            $this->migrateFatherInLawOfChildren($newChild, $newMarriagePartner, $parentMarriageOrder, $childOrder, $oldMarriagePartner['order3'], $oldPersonID, $oldDBManager);
            $this->migrateMotherInLawOfChildren($newChild, $newMarriagePartner, $parentMarriageOrder, $childOrder, $oldMarriagePartner['order3'], $oldPersonID, $oldDBManager);

            $this->migrateGrandchild($newPerson, $newChild, $newMarriagePartner, $parentMarriageOrder, $childOrder, $oldMarriagePartner['order3'], $oldPersonID, $oldDBManager);
        }
    }

    private function migrateWeddingOfChildren($newChild, $newMarriagePartner, $oldMarriagePartner) {
        //$weddingOrder, $husband, $wife, $weddingDateid, $weddingLocationid, $weddingTerritoryid, $bannsDateid, $breakupReason, $breakupDateid, $marriageComment, $beforeAfter, $comment
        $this->getMigrationService()->migrateWedding($oldMarriagePartner['order3'], $newChild, $newMarriagePartner, $oldMarriagePartner['hochzeitstag'], $oldMarriagePartner['hochzeitsort'], null, $oldMarriagePartner['aufgebot'], $oldMarriagePartner['auflösung'], $oldMarriagePartner['gelöst'], $oldMarriagePartner['verheiratet'], null, null);
    }

    private function createMarriagePartnersOfChildren($newChild, $oldMarriagePartnerOfChild) {
        $gender = $this->getOppositeGender($newChild);

        //$firstName, $patronym, $lastName, $gender, $nation, $comment
        $marriagePartnerOfChild = $this->getMigrationService()->migratePartner($oldMarriagePartnerOfChild["vornamen"], $oldMarriagePartnerOfChild["russ_vornamen"], $oldMarriagePartnerOfChild["name"], $gender, $oldMarriagePartnerOfChild["nation"], $oldMarriagePartnerOfChild["kommentar"]);

        $this->trackOriginOfData($marriagePartnerOfChild->getId(), $oldMarriagePartnerOfChild["ID"], 'ehepartner_des_kindes', $oldMarriagePartnerOfChild["order"], $oldMarriagePartnerOfChild["order2"], $oldMarriagePartnerOfChild["order3"]); 
        
        $marriagePartnerOfChild->setForeName($this->getNormalizationService()->writeOutNameAbbreviations($oldMarriagePartnerOfChild["rufnamen"]));

        //birth
        if (!is_null($oldMarriagePartnerOfChild["herkunftsort"]) ||
                !is_null($oldMarriagePartnerOfChild["herkunftsterritorium"]) ||
                !is_null($oldMarriagePartnerOfChild["geburtsort"]) ||
                !is_null($oldMarriagePartnerOfChild["geburtsterritorium"]) ||
                !is_null($oldMarriagePartnerOfChild["geboren"])) {
            //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
            $this->getMigrationService()->migrateBirth($marriagePartnerOfChild, null, $oldMarriagePartnerOfChild["herkunftsterritorium"], $oldMarriagePartnerOfChild["herkunftsort"], null, $oldMarriagePartnerOfChild["geburtsort"], $oldMarriagePartnerOfChild["geboren"], $oldMarriagePartnerOfChild["geburtsterritorium"]);
        }

        //death
        if (!is_null($oldMarriagePartnerOfChild["gestorben"]) ||
                !is_null($oldMarriagePartnerOfChild["friedhof"]) ||
                !is_null($oldMarriagePartnerOfChild["begräbnisort"]) ||
                !is_null($oldMarriagePartnerOfChild["todesort"])) {
            //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
            $this->getMigrationService()->migrateDeath($marriagePartnerOfChild, $oldMarriagePartnerOfChild["todesort"], $oldMarriagePartnerOfChild["gestorben"], null, null, null, $oldMarriagePartnerOfChild["friedhof"], $oldMarriagePartnerOfChild["begräbnisort"]);
        }


        //status
        if (!is_null($oldMarriagePartnerOfChild["stand"])) {
            $this->getMigrationService()->migrateStatus($marriagePartnerOfChild, 1, $oldMarriagePartnerOfChild["stand"]);
        }

        //rank
        if (!is_null($oldMarriagePartnerOfChild["rang"])) {
            $this->getMigrationService()->migrateRank($marriagePartnerOfChild, 1, $oldMarriagePartnerOfChild["rang"]);
        }


        //property
        if (!is_null($oldMarriagePartnerOfChild["besitz"])) {
            $this->getMigrationService()->migrateProperty($marriagePartnerOfChild, 1, $oldMarriagePartnerOfChild["besitz"]);
        }


        //job
        if (!is_null($oldMarriagePartnerOfChild["beruf"])) {
            $jobID = $this->getMigrationService()->migrateJob($oldMarriagePartnerOfChild["beruf"]);

            $marriagePartnerOfChild->setJob($jobID);
        }

        //education
        if (!is_null($oldMarriagePartnerOfChild["bildungsabschluss"])) {
            //$educationOrder, $label, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $graduationLabel=null, $graduationDate=null, $graduationLocation=null, $comment=null
            $this->getMigrationService()->migrateEducation($marriagePartnerOfChild, 1, null, null, null, null, null, null, null, $oldMarriagePartnerOfChild["bildungsabschluss"]);
        }



        return $marriagePartnerOfChild;
    }

    private function getMarriagePartnersOfChildrenWithNativeQuery($oldPersonID, $parentMarriageOrder, $childOrder, $oldDBManager) {
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

    private function migrateOtherPartnersOfChildren($newMarriagePartner, $parentMarriageOrder, $childOrder, $childMarriageOrder, $oldPersonID, $oldDBManager) {
        $otherPartnersOfChildren = $this->getOtherPartnersOfChildrenWithNativeQuery($oldPersonID, $parentMarriageOrder, $childOrder, $childMarriageOrder, $oldDBManager);

        for ($i = 0; $i < count($otherPartnersOfChildren); $i++) {
            $oldOtherPartnersOfChildren = $otherPartnersOfChildren[$i];
            $this->createOtherPartnersOfChildren($newMarriagePartner, $oldOtherPartnersOfChildren);
        }
    }

    private function createOtherPartnersOfChildren($newMarriagePartner, $oldOtherPartnerOfChild) {
        $gender = $this->getOppositeGender($newMarriagePartner);

        //$firstName, $patronym, $lastName, $gender, $nation, $comment
        $otherPartnerOfChild = $this->getMigrationService()->migratePartner($oldOtherPartnerOfChild["vornamen"], null, $oldOtherPartnerOfChild["name"], $gender, null, $oldOtherPartnerOfChild["kommentar"]);

        $this->trackOriginOfData($otherPartnerOfChild->getId(), $oldOtherPartnerOfChild["ID"], 'anderer_partner_des_kindes', $oldOtherPartnerOfChild["order"], $oldOtherPartnerOfChild["order2"], $oldOtherPartnerOfChild["order3"], $oldOtherPartnerOfChild["order4"]); 
        
        //birth
        if (!is_null($oldOtherPartnerOfChild["geburtsort"]) ||
                !is_null($oldOtherPartnerOfChild["geboren"])) {
            //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
            $this->getMigrationService()->migrateBirth($otherPartnerOfChild, null, null, null, null, $oldOtherPartnerOfChild["geburtsort"], $oldOtherPartnerOfChild["geboren"]);
        }

        //death
        if (!is_null($oldOtherPartnerOfChild["gestorben"]) ||
                !is_null($oldOtherPartnerOfChild["todesort"])) {
            //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
            $this->getMigrationService()->migrateDeath($otherPartnerOfChild, $oldOtherPartnerOfChild["todesort"], $oldOtherPartnerOfChild["gestorben"]);
        }

        //rank
        if (!is_null($oldOtherPartnerOfChild["rang"])) {
            $this->getMigrationService()->migrateRank($otherPartnerOfChild, 1, $oldOtherPartnerOfChild["rang"]);
        }

        $this->migrateWeddingOfOtherPartnersOfChild($newMarriagePartner, $otherPartnerOfChild, $oldOtherPartnerOfChild);
    }

    private function migrateWeddingOfOtherPartnersOfChild($newMarriagePartner, $otherPartnerOfChild, $oldOtherPartnerOfChild) {
        //$weddingOrder, $husband, $wife, $weddingDateid, $weddingLocationid, $weddingTerritoryid, $bannsDateid, $breakupReason, $breakupDateid, $marriageComment, $beforeAfter, $comment
        $this->getMigrationService()->migrateWedding($oldOtherPartnerOfChild['order4'], $newMarriagePartner, $otherPartnerOfChild, $oldOtherPartnerOfChild['hochzeitstag'], $oldOtherPartnerOfChild['hochzeitsort'], null, null, $oldOtherPartnerOfChild['auflösung'], null, $oldOtherPartnerOfChild['verheiratet'], $oldOtherPartnerOfChild['vorher-nachher']);
    }

    private function getOtherPartnersOfChildrenWithNativeQuery($oldPersonID, $parentMarriageOrder, $childOrder, $childMarriageOrder, $oldDBManager) {
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

    private function migrateFatherInLawOfChildren($newChild, $newMarriagePartner, $parentMarriageOrder, $childOrder, $childMarriageOrder, $oldPersonID, $oldDBManager) {
        $fatherInLawOfChildren = $this->getFatherInLawOfChildrenWithNativeQuery($oldPersonID, $parentMarriageOrder, $childOrder, $childMarriageOrder, $oldDBManager);

        for ($i = 0; $i < count($fatherInLawOfChildren); $i++) {
            $oldFatherInLawOfChildren = $fatherInLawOfChildren[$i];
            $this->createFatherInLawOfChildren($newChild, $newMarriagePartner, $oldFatherInLawOfChildren);
        }
    }

    private function createFatherInLawOfChildren($newChild, $newMarriagePartner, $oldFatherInLawOfChild) {
        $gender = "männlich";
        //$firstName, $patronym, $lastName, $gender, $nation, $comment
        $fatherInLawOfChild = $this->getMigrationService()->migrateRelative($oldFatherInLawOfChild["vornamen"], null, $oldFatherInLawOfChild["name"], $gender);

        $this->trackOriginOfData($fatherInLawOfChild->getId(), $oldFatherInLawOfChild["ID"], 'schwiegervater_des_kindes', $oldFatherInLawOfChild["order"], $oldFatherInLawOfChild["order2"], $oldFatherInLawOfChild["order3"], $oldFatherInLawOfChild["order4"]); 
        
        //rank
        if (!is_null($oldFatherInLawOfChild["rang"])) {
            $this->getMigrationService()->migrateRank($fatherInLawOfChild, 1, $oldFatherInLawOfChild["rang"]);
        }

        //job
        if (!is_null($oldFatherInLawOfChild["beruf"])) {
            $jobID = $this->getMigrationService()->migrateJob($oldFatherInLawOfChild["beruf"]);

            $fatherInLawOfChild->setJob($jobID);
        }

        //born_in_marriage
        if (!is_null($oldFatherInLawOfChild["ehelich"])) {
            $fatherInLawOfChild->setBornInMarriage($this->getNormalizationService()->writeOutAbbreviations($oldFatherInLawOfChild["ehelich"]));
        }

        if (!is_null($oldFatherInLawOfChild["wohnort"])) {
            $this->getMigrationService()->migrateResidence($fatherInLawOfChild, 1, null, null, $oldFatherInLawOfChild["wohnort"]);
        }

        $this->getMigrationService()->migrateIsParent($newMarriagePartner, $fatherInLawOfChild);
        $this->getMigrationService()->migrateIsParentInLaw($newChild, $fatherInLawOfChild);
    }

    private function getFatherInLawOfChildrenWithNativeQuery($oldPersonID, $parentMarriageOrder, $childOrder, $childMarriageOrder, $oldDBManager) {
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

    private function migrateMotherInLawOfChildren($newChild, $newMarriagePartner, $parentMarriageOrder, $childOrder, $childMarriageOrder, $oldPersonID, $oldDBManager) {
        $motherInLawOfChildren = $this->getMotherInLawOfChildrenWithNativeQuery($oldPersonID, $parentMarriageOrder, $childOrder, $childMarriageOrder, $oldDBManager);

        for ($i = 0; $i < count($motherInLawOfChildren); $i++) {
            $oldMotherInLawOfChildren = $motherInLawOfChildren[$i];
            $this->createMotherInLawOfChildren($newChild, $newMarriagePartner, $oldMotherInLawOfChildren);
        }
    }

    private function createMotherInLawOfChildren($newChild, $newMarriagePartner, $oldMotherInLawOfChild) {
        $gender = "weiblich";
        //$firstName, $patronym, $lastName, $gender, $nation, $comment
        $motherInLawOfChild = $this->getMigrationService()->migrateRelative($oldMotherInLawOfChild["vornamen"], null, $oldMotherInLawOfChild["name"], $gender);

        $this->trackOriginOfData($motherInLawOfChild->getId(), $oldMotherInLawOfChild["ID"], 'schwiegermutter_des_kindes', $oldMotherInLawOfChild["order"], $oldMotherInLawOfChild["order2"], $oldMotherInLawOfChild["order3"], $oldMotherInLawOfChild["order4"]); 
        
        
        //born_in_marriage
        if (!is_null($oldMotherInLawOfChild["ehelich"])) {
            $motherInLawOfChild->setBornInMarriage($this->getNormalizationService()->writeOutAbbreviations($oldMotherInLawOfChild["ehelich"]));
        }

        $this->getMigrationService()->migrateIsParent($newMarriagePartner, $motherInLawOfChild);
        $this->getMigrationService()->migrateIsParentInLaw($newChild, $motherInLawOfChild);
    }

    private function getMotherInLawOfChildrenWithNativeQuery($oldPersonID, $parentMarriageOrder, $childOrder, $childMarriageOrder, $oldDBManager) {
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

    private function migrateMotherOfSibling($newSibling, $siblingOrder, $oldPersonID, $oldDBManager) {
        $motherOfSibling = $this->getMotherOfSiblingWithNativeQuery($oldPersonID, $siblingOrder, $oldDBManager);

        for ($i = 0; $i < count($motherOfSibling); $i++) {
            $oldMotherOfSibling = $motherOfSibling[$i];
            $this->createMotherOfSibling($newSibling, $oldMotherOfSibling);
        }
    }

    private function createMotherOfSibling($newSibling, $oldMotherOfSibling) {
        $gender = "weiblich";
        //$firstName, $patronym, $lastName, $gender, $nation, $comment
        $motherOfSibling = $this->getMigrationService()->migrateRelative($oldMotherOfSibling["vornamen"], null, $oldMotherOfSibling["name"], $gender);

        $this->trackOriginOfData($motherOfSibling->getId(), $oldMotherOfSibling["ID"], 'mutter_des_geschwisters', $oldMotherOfSibling["order"], $oldMotherOfSibling["order2"]); 
        
        
        //birth
        if (!is_null($oldMotherOfSibling["geboren"])) {
            //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
            $this->getMigrationService()->migrateBirth($motherOfSibling, null, null, null, null, null, $oldMotherOfSibling["geboren"]);
        }

        //death
        if (!is_null($oldMotherOfSibling["gestorben"])) {
            //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
            $this->getMigrationService()->migrateDeath($motherOfSibling, null, $oldMotherOfSibling["gestorben"]);
        }

        //born_in_marriage
        if (!is_null($oldMotherOfSibling["ehelich"])) {
            $motherOfSibling->setBornInMarriage($this->getNormalizationService()->writeOutAbbreviations($oldMotherOfSibling["ehelich"]));
        }

        $this->getMigrationService()->migrateIsParent($newSibling, $motherOfSibling);
    }

    private function getMotherOfSiblingWithNativeQuery($oldPersonID, $siblingOrder, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, vornamen, name, geboren, gestorben, ehelich
        FROM `mutter_des_geschwisters` WHERE ID=:personID AND `order`=:siblingOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('siblingOrder', $siblingOrder);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function migrateFatherOfSibling($newSibling, $siblingOrder, $oldPersonID, $oldDBManager) {
        $fatherOfSibling = $this->getFatherOfSiblingWithNativeQuery($oldPersonID, $siblingOrder, $oldDBManager);

        for ($i = 0; $i < count($fatherOfSibling); $i++) {
            $oldFatherOfSibling = $fatherOfSibling[$i];
            if (!is_null($oldFatherOfSibling["geschwistervater_id-nr"])) {
                //check it?
                //no check for not found exception, since there is no way to recover from it.
                $fatherOfSiblingOID = $oldFatherOfSibling["geschwistervater_id-nr"];

                $fatherOfSiblingMainId = $this->getIDForOID($fatherOfSiblingOID, $oldDBManager);

                $fatherOfSibling = $this->migratePerson($fatherOfSiblingMainId, $fatherOfSiblingOID);

                $this->getMigrationService()->migrateIsParent($newSibling, $fatherOfSibling);
            } else {
                //not happening                
            }
        }
    }

    private function getFatherOfSiblingWithNativeQuery($oldPersonID, $siblingOrder, $oldDBManager) {
        $sql = "SELECT `order`,`order2`,`geschwistervater_id-nr` FROM `vater des geschwisters`
             WHERE ID=:personID AND `order`=:siblingOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('siblingOrder', $siblingOrder);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function migrateGrandchild($newPerson, $newChild, $newMarriagePartner, $parentMarriageOrder, $childOrder, $childMarriageOrder, $oldPersonID, $oldDBManager) {
        //
        $grandchildren = $this->getGrandchildWithNativeQuery($oldPersonID, $parentMarriageOrder, $childOrder, $childMarriageOrder, $oldDBManager);

        for ($i = 0; $i < count($grandchildren); $i++) {
            $oldGrandchild = $grandchildren[$i];

            $newGrandChild = $this->createGrandchild($oldGrandchild);

            //check if reference to person
            if (!is_null($oldGrandchild["enkel_id-nr"])) {
                //check it?
                $grandchildsOID = $oldGrandchild["enkel_id-nr"];
                
                try{
                    $grandchildsMainId = $this->getIDForOID($grandchildsOID, $oldDBManager);
                    $newGrandchildObj = $this->migratePerson($grandchildsMainId, $grandchildsOID);
                    $newGrandChild = $this->get("person_merging.service")->mergePersons($newGrandChild, $newGrandchildObj);
                }catch(\UR\AmburgerBundle\Exception\NotFoundException $e){
                    $this->LOGGER->info("Could not found ID for OID: ".$grandchildsOID);
                    $newGrandChild->setComment($newGrandChild->getComment() ? $newGrandChild->getComment()."ReferencedOID: ".$grandchildsOID : "ReferencedOID: ".$grandchildsOID);
                }
            }

            $paternal = true;
            $this->getMigrationService()->migrateIsGrandparent($newGrandChild, $newPerson, $paternal);
            
            $this->getMigrationService()->migrateIsParent($newGrandChild, $newChild);
            $this->getMigrationService()->migrateIsParent($newGrandChild, $newMarriagePartner);
        }
    }

    private function createGrandchild($oldGrandchild) {
        //$firstName, $patronym, $lastName, $gender, $nation, $comment
        $grandchild = $this->getMigrationService()->migrateRelative($oldGrandchild["vornamen"], $oldGrandchild["russ_vornamen"], $oldGrandchild["name"], $oldGrandchild["geschlecht"], null, $oldGrandchild["kommentar"]);

        $this->trackOriginOfData($grandchild->getId(), $oldGrandchild["ID"], 'enkelkind', $oldGrandchild["order"], $oldGrandchild["order2"], $oldGrandchild["order3"], $oldGrandchild["order4"]); 
        
        $grandchild->setForeName($this->getNormalizationService()->writeOutNameAbbreviations($oldGrandchild["rufnamen"]));

        //birth
        if (!is_null($oldGrandchild["geburtsort"]) ||
                !is_null($oldGrandchild["geboren"])) {
            //$originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null
            $this->getMigrationService()->migrateBirth($grandchild, null, null, null, null, $oldGrandchild["geburtsort"], $oldGrandchild["geboren"]);
        }

        //death
        if (!is_null($oldGrandchild["gestorben"])) {
            //$deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null
            $this->getMigrationService()->migrateDeath($grandchild, null, $oldGrandchild["gestorben"]);
        }


        //status
        if (!is_null($oldGrandchild["stand"])) {
            $this->getMigrationService()->migrateStatus($grandchild, 1, $oldGrandchild["stand"]);
        }

        //rank
        if (!is_null($oldGrandchild["rang"])) {
            $this->getMigrationService()->migrateRank($grandchild,1, $oldGrandchild["rang"]);
        }


        //property
        if (!is_null($oldGrandchild["besitz"])) {
            $this->getMigrationService()->migrateProperty($grandchild, 1, $oldGrandchild["besitz"]);
        }


        //job
        if (!is_null($oldGrandchild["beruf"])) {
            $jobID = $this->getMigrationService()->migrateJob($oldGrandchild["beruf"]);

            $grandchild->setJob($jobID);
        }

        //education
        if (!is_null($oldGrandchild["bildungsabschluss"])) {
            //$educationOrder, $label, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $graduationLabel=null, $graduationDate=null, $graduationLocation=null, $comment=null
            $this->getMigrationService()->migrateEducation($grandchild, 1, null, null, null, null, null, null, null, $oldGrandchild["bildungsabschluss"]);
        }

        if (!is_null($oldGrandchild["wohnort"])) {
            $this->getMigrationService()->migrateResidence($grandchild, 1, null, null, $oldGrandchild["wohnort"]);
        }

        return $grandchild;
    }

    private function getGrandchildWithNativeQuery($oldPersonID, $parentMarriageOrder, $childOrder, $childMarriageOrder, $oldDBManager) {
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

    private function separateReferenceIdsAndComment($stringOfReferenceIds) {
        $this->LOGGER->debug("Seperating ReferenceIds from Comments in: ".$stringOfReferenceIds);
        if (is_null($stringOfReferenceIds)) {
            return [];
        }

        $trimmedReferenceIds = trim($stringOfReferenceIds);

        if (count($trimmedReferenceIds) == 0) {
            return [];
        }

        $lowerCaseReferenceIds = strtolower($trimmedReferenceIds);

        $containsAnmerkung = strpos($lowerCaseReferenceIds, strtolower("- Anmerkung:"));
        $containsImOriginal = strpos($lowerCaseReferenceIds, strtolower("- im Original"));
        $containEvtl = strpos($lowerCaseReferenceIds, strtolower("evtl."));
        
        $containsOr = strpos($lowerCaseReferenceIds, strtolower("oder"));
        $containsBzw = strpos($lowerCaseReferenceIds, strtolower("bzw."));
        
        $result = [$trimmedReferenceIds, null];

        if ($containsAnmerkung !== false) {
            $this->LOGGER->debug("Found '-Anmerkung:' in" . $stringOfReferenceIds);
            $result[0] = trim(substr($stringOfReferenceIds, 0, $containsAnmerkung));
            $result[1] = substr($stringOfReferenceIds, $containsAnmerkung);
        } else if ($containsImOriginal !== false) {
            $this->LOGGER->debug("Found '-im Original' in" . $stringOfReferenceIds);
            $result[0] = trim(substr($stringOfReferenceIds, 0, $containsImOriginal));
            $result[1] = substr($stringOfReferenceIds, $containsImOriginal);
        } else if ($containEvtl !== false) {
            if($containEvtl == 0){
                $this->LOGGER->debug("Found 'evtl.' at the start of" . $stringOfReferenceIds);
                $result[0] = trim(substr($stringOfReferenceIds, strlen("evtl.")));
                $result[1] = substr($stringOfReferenceIds, 0, strlen("evtl."));
            } else {
                $this->LOGGER->debug("Found 'evtl.' at the end of" . $stringOfReferenceIds);
                $result[0] = trim(substr($stringOfReferenceIds, 0, $containEvtl));
                $result[1] = substr($stringOfReferenceIds, $containEvtl);
            }
        } else if ($containsOr !== false) {
            $this->LOGGER->debug("Found an 'oder' in " . $stringOfReferenceIds);
            //separate at oder, trim whitespaces and then recreate string but with ;, so that it will be separated again later 
            $result[0] = implode(";",array_map('trim', explode("oder", $stringOfReferenceIds)));
            $result[1] = $stringOfReferenceIds;
        } else if ($containsBzw !== false) {
            $this->LOGGER->debug("Found an 'bzw.' in " . $stringOfReferenceIds);
            //separate at oder, trim whitespaces and then recreate string but with ;, so that it will be separated again later
            $result[0] = implode(";",array_map('trim', explode("bzw.", $stringOfReferenceIds)));
            $result[1] = $stringOfReferenceIds;
        } else if (substr($lowerCaseReferenceIds, -2) == "??") {
            $this->LOGGER->debug("Found an '??' at the end of " . $stringOfReferenceIds);
            $result[0] = trim(substr($stringOfReferenceIds, 0, strlen($stringOfReferenceIds) - 2));
            $result[1] = "??";
        } else if (substr($lowerCaseReferenceIds, -1) == "?") {
            $this->LOGGER->debug("Found an '?' at the end of " . $stringOfReferenceIds);
            $result[0] = trim(substr($stringOfReferenceIds, 0, strlen($stringOfReferenceIds) - 1));
            $result[1] = "?";
        } else if (is_numeric($stringOfReferenceIds)){
            $this->LOGGER->debug($stringOfReferenceIds." is a number but could be separated by dot.");
            $result[0] = str_replace('.', '', $trimmedReferenceIds);
            $result[1] = null;

        } else {
            $this->LOGGER->debug($stringOfReferenceIds." is not an numeric!");
            $result[0] = null;
            $result[1] = $stringOfReferenceIds;
        }

        $this->LOGGER->debug("Separated '" . $stringOfReferenceIds . "' into ids: '" . $result[0] . "' and comment '" . $result[1] . "'");
        return $result;
    }

    private function extractReferenceIdsArray($stringOfReferenceIds) {
        $this->LOGGER->debug("Extracting ReferenceIds from: ".$stringOfReferenceIds);
        if (is_null($stringOfReferenceIds)) {
            return [];
        }

        $trimmedReferenceIds = trim($stringOfReferenceIds);

        if (count($trimmedReferenceIds) == 0) {
            return [];
        }

        $lowerCaseReferenceIds = strtolower($trimmedReferenceIds);

        $containsSemicolon = strpos($lowerCaseReferenceIds, ";");
        $containsCommata = strpos($lowerCaseReferenceIds, ",");
        if ($containsSemicolon !== false && $containsCommata !== false) {
            $referenceList = array();
            $separatedBySemicolon = explode(";", $stringOfReferenceIds);
            
            for($i = 0; $i < count($separatedBySemicolon); $i++){
                $separatedByCommata = explode(",", $separatedBySemicolon[$i]);
                
                for($j = 0; $j < count($separatedByCommata); $j++){
                    $referenceList[] = $separatedByCommata[$j];
                }
            }
            
            return $referenceList;
        } else if ($containsSemicolon !== false) {
            return explode(";", $stringOfReferenceIds);
        } else if ($containsCommata !== false) {
            return explode(",", $stringOfReferenceIds);
        }

        return [$stringOfReferenceIds];
    }

    
    private function trackOriginOfData($newDBIdOfPerson,$idOfMainPerson,  $tableAsString, $order, $order2=null, $order3=null,$order4=null){
        $data = array();
        $data['idOfMainPerson'] = $idOfMainPerson;
        $data['table'] = $tableAsString;
        $data['order'] = $order;
        $data['order2'] = $order2;
        $data['order3'] = $order3;
        $data['order4'] = $order4;

        $this->get('origin_of_data_tracker')->trackData($newDBIdOfPerson, $data);
    }
}
