<?php

namespace UR\DB\NewBundle\Utils;

use UR\DB\NewBundle\Entity\Person;
use UR\DB\NewBundle\Entity\Death;
use UR\DB\NewBundle\Entity\Location;
use UR\DB\NewBundle\Entity\Country;
use UR\DB\NewBundle\Entity\Territory;
use UR\DB\NewBundle\Entity\Job;
use UR\DB\NewBundle\Entity\JobClass;
use UR\DB\NewBundle\Entity\Date;

class MigrateData
{

    private $container;

	public function __construct($container)
    {
        $this->container = $container;
    }

    private function get($identifier){
        return $this->container->get($identifier);
    }

    /* helper method */


    public function getCountryId($countryName, $comment = null){

        if($countryName == "" || $countryName == null){
            return null;
        }

        // check if country exists
        $newDBManager = $this->get('doctrine')->getManager('new');

        $country = $newDBManager->getRepository('NewBundle:Country')->findOneByName($countryName);

        if($country != null){
            return $country->getId();
        }

        // if it does not exist, create it and return the new value
        $newCountry = new Country();

        $newCountry->setName($countryName);
        $newCountry->setComment($comment);
        
        $newDBManager->persist($newCountry);
        $newDBManager->flush();

        return $newCountry->getId();
    }

    public function getTerritoryId($territoryName, $comment = null){
        if($territoryName == "" || $territoryName == null){
            return null;
        }

        // check if territory exists
        $newDBManager = $this->get('doctrine')->getManager('new');

        $territory = $newDBManager->getRepository('NewBundle:Territory')->findOneByName($territoryName);

        if($territory != null){
            return $territory->getId();
        }

        // if it does not exist, create it and return the new value
        $newTerritory = new Territory();

        $newTerritory->setName($territoryName);
        $newTerritory->setComment($comment);
        
        $newDBManager->persist($newTerritory);
        $newDBManager->flush();

        return $newTerritory->getId();
    }

    public function getLocationId($locationName, $comment = null){
        if($locationName == "" || $locationName == null){
            return null;
        }

        // check if location exists
        $newDBManager = $this->get('doctrine')->getManager('new');

        $location = $newDBManager->getRepository('NewBundle:Location')->findOneByName($locationName);

        if($location != null){
            return $location->getId();
        }

        // if it does not exist, create it and return the new value
        $newLocation = new Location();

        $newLocation->setName($locationName);
        $newLocation->setComment($comment);
        
        $newDBManager->persist($newLocation);
        $newDBManager->flush();

        return $newLocation->getId();
    }

    public function getJobId($jobLabel, $comment = null){
        if($jobLabel == "" || $jobLabel == null){
            return null;
        }

        // check if job exists
        $newDBManager = $this->get('doctrine')->getManager('new');

        $job = $newDBManager->getRepository('NewBundle:Job')->findOneByLabel($jobLabel);

        if($job != null){
            return $job->getId();
        }

        // if it does not exist, create it and return the new value
        $newJob = new Job();

        $newJob->setLabel($jobLabel);
        $newJob->setComment($comment);
        
        $newDBManager->persist($newJob);
        $newDBManager->flush();

        return $newJob->getId();
    }

    public function getJobClassId($jobClassLabel){
        if($jobClassLabel == "" || $jobClassLabel = null){
            return null;
        }

        // check if jobClass exists
        $newDBManager = $this->get('doctrine')->getManager('new');

        $jobClass = $newDBManager->getRepository('NewBundle:JobClass')->findOneByLabel($jobClassLabel);

        if($jobClass != null){
            return $jobClass->getId();
        }

        // if it does not exist, create it and return the new value
        $newJobClass = new JobClass();

        $newJobClass->setLabel($jobClassLabel);
        
        $newDBManager->persist($newJobClass);
        $newDBManager->flush();

        return $newJobClass->getId();
    }

    //returns 0-many date objects
    public function getDate($dateString, $comment = null){
        $dateIdArray = [];

        if($dateString == "" || $dateString == null){
            return $dateIdArray;
        }

        // check if jobClass exists
        $newDBManager = $this->get('doctrine')->getManager('new');

        // if it does not exist, create it and return the new value
        $newDate = new Date();

        $newDate->setComment("what you want? ".$dateString);

        // Todo write code for it
        
        $newDBManager->persist($newDate);

        // if it does not exist, create it and return the new value
        $newDate2 = new Date();

        $newDate2->setComment("YOU again? ".$dateString);

        // Todo write code for it
        
        $newDBManager->persist($newDate2);
        $newDBManager->flush();

        $dateIdArray[] = $newDate->getId();
        $dateIdArray[] = $newDate2->getId();

        return $dateIdArray;
    }

    /* end helper method */

    public function migrateBirth($originCountryid, $originTerritoryid, $originLocationid, $birthCountryid, $birthLocationid, $birthDateid, $birthTerritoryid, $baptismDateid, $baptismLocationid, $comment){
        //insert into new data
        $newDBManager = $this->get('doctrine')->getManager('new');

        $newBirth = new Birth();

        $newBirth->setOriginCountryid($originCountryid);
        $newBirth->setOriginTerritoryid($originTerritoryid);
        $newBirth->setOriginLocationid($originLocationid);
        $newBirth->setBirthCountryid($birthCountryid);
        $newBirth->setBirthLocationid($birthLocationid);
        $newBirth->setBirthDateid($birthDateid);
        $newBirth->setBirthTerritoryid($birthTerritoryid);
        $newBirth->setBaptismDateid($baptismDateid);
        $newBirth->setBaptismLocationid($baptismLocationid);
        $newBirth->setComment($comment);
        
        $newDBManager->persist($newBirth);
        $newDBManager->flush();

        return $newBirth->getId();
    }

    public function migrateCountry($name, $comment){
        //insert into new data
        $newDBManager = $this->get('doctrine')->getManager('new');

        $newCountry = new Country();

        $newCountry->setName($name);
        $newCountry->setComment($comment);
        
        $newDBManager->persist($newCountry);
        $newDBManager->flush();

        return $newCountry->getId();
    }

    public function migrateDate($day, $month, $year, $weekday, $comment){
        //insert into new data
        $newDBManager = $this->get('doctrine')->getManager('new');

        $newDate = new Date();

        $newDate->setDay($day);
        $newDate->setMonth($month);
        $newDate->setYear($year);
        $newDate->setWeekday($weekday);
        $newDate->setComment($comment);
        
        $newDBManager->persist($newDate);
        $newDBManager->flush();

        return $newDate->getId();
    }

    public function migrateDeath($deathLocation, $deathDate, $deathCountry, $causeOfDeath, $territoryOfDeath, $graveyard, $funeralLocation, $funeralDate, $comment){
        //insert into new data
        $newDBManager = $this->get('doctrine')->getManager('new');

        $newDeath = new Death();

        $newDeath->setDeathLocationid($this->getLocationId($deathLocation));
        $newDeath->setDeathCountryid($this->getCountryId($deathCountry));
        $newDeath->setCauseOfDeath($causeOfDeath);
        $newDeath->setTerritoryOfDeathid($this->getTerritoryId($territoryOfDeath));
        $newDeath->setGraveyard($graveyard);
        $newDeath->setFuneralLocationid($this->getLocationId($funeralLocation));
        $newDeath->setComment($comment);

        $deathRepository = $newDBManager->getRepository("NewBundle:Death");

        $deathRepository->setDeathDates($newDeath, $this->getDate($deathDate));
        $deathRepository->setFuneralDates($newDeath, $this->getDate($funeralDate));
        
        $newDBManager->persist($newDeath);
        $newDBManager->flush();

        return $newDeath->getId();
    }

    public function migrateEducation($educationOrder, $label, $countryid, $territoryid, $locationid, $fromDateid, $toDateid, $provenDateid, $graduationLabel, $graduationDateid, $graduationLocationid, $comment){
        //insert into new data
        $newDBManager = $this->get('doctrine')->getManager('new');

        $newEducation = new Education();

        $newEducation->setEducationOrder($educationOrder);
        $newEducation->setLabel($label);
        $newEducation->setCountryid($countryid);
        $newEducation->setTerritoryid($territoryid);
        $newEducation->setLocationid($locationid);
        $newEducation->setFromDateid($fromDateid);
        $newEducation->setToDateid($toDateid);
        $newEducation->setProvenDateid($provenDateid);
        $newEducation->setGraduationLabel($graduationLabel);
        $newEducation->setGraduationDateid($graduationDateid);
        $newEducation->setGraduationLocationid($graduationLocationid);
        $newEducation->setComment($comment);
        
        $newDBManager->persist($newEducation);
        $newDBManager->flush();

        return $newEducation->getId();
    }

    public function migrateHonour($honourOrder, $label, $countryid, $territoryid, $locationid, $fromDateid, $toDateid, $provenDateid, $comment){
        //insert into new data
        $newDBManager = $this->get('doctrine')->getManager('new');

        $newHonour = new Honour();

        $newHonour->setHonourOrder($honourOrder);
        $newHonour->setLabel($label);
        $newHonour->setCountryid($countryid);
        $newHonour->setTerritoryid($territoryid);
        $newHonour->setLocationid($locationid);
        $newHonour->setFromDateid($fromDateid);
        $newHonour->setToDateid($toDateid);
        $newHonour->setProvenDateid($provenDateid);
        $newHonour->setComment($comment);
        
        $newDBManager->persist($newHonour);
        $newDBManager->flush();

        return $newHonour->getId();
    }

    public function migrateIsChild(){  //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        //insert into new data
        $newDBManager = $this->get('doctrine')->getManager('new');

        $newIsChild = new IsChild();

        //$newIsChild->;
        
        $newDBManager->persist($newIsChild);
        $newDBManager->flush();

        return $newIsChild->getId();
    }

    public function migrateIsGrandchild(){ //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!1
        //insert into new data
        $newDBManager = $this->get('doctrine')->getManager('new');

        $newIsGrandchild = new IsGrandchild();

        //$newIsGrandchild->;
        
        $newDBManager->persist($newIsGrandchild);
        $newDBManager->flush();

        return $newIsGrandchild->getId();
    }

    public function migrateIsGrandparent(){   //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        //insert into new data
        $newDBManager = $this->get('doctrine')->getManager('new');

        $newIsGrandparent = new IsGrandparent();

        //$newIsGrandparent->;
        
        $newDBManager->persist($newIsGrandparent);
        $newDBManager->flush();

        return $newIsGrandparent->getId();
    }

    public function migrateIsParent(){   //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        //insert into new data
        $newDBManager = $this->get('doctrine')->getManager('new');

        $newIsParent = new IsParent();

        //$newIsParent->;
        
        $newDBManager->persist($newIsParent);
        $newDBManager->flush();

        return $newIsParent->getId();
    }

    public function migrateIsParentInLaw(){  //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        //insert into new data
        $newDBManager = $this->get('doctrine')->getManager('new');

        $newIsParentInLaw = new IsParentInLaw();

        //$newIsParentInLaw->;
        
        $newDBManager->persist($newIsParentInLaw);
        $newDBManager->flush();

        return $newIsParentInLaw->getId();
    }

    public function migrateIsSibling(){  //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!1
        //insert into new data
        $newDBManager = $this->get('doctrine')->getManager('new');

        $newIsSibling = new IsSibling();

        //$newIsSibling->;
        
        $newDBManager->persist($newIsSibling);
        $newDBManager->flush();

        return $newIsSibling->getId();
    }

    public function migrateJob($label, $comment){
        //insert into new data
        $newDBManager = $this->get('doctrine')->getManager('new');

        $newJob = new Job();

        $newJob->setLabel($label);
        $newJob->setComment($comment);
        
        $newDBManager->persist($newJob);
        $newDBManager->flush();

        return $newJob->getId();
    }

    public function migrateLocation($name, $comment){
        //insert into new data
        $newDBManager = $this->get('doctrine')->getManager('new');

        $newLocation = new Location();

        $newLocation->setName($name);
        $newLocation->setComment($comment);
        
        $newDBManager->persist($newLocation);
        $newDBManager->flush();

        return $newLocation->getId();
    }

    public function migrateNation($name, $comment){
        //insert into new data
        $newDBManager = $this->get('doctrine')->getManager('new');

        $newNation = new Nation();

        $newNation->setName($name);
        $newNation->setComment($comment);
        
        $newDBManager->persist($newNation);
        $newDBManager->flush();

        return $newNation->getId();
    }

    public function migratePerson($oid, $firstName, $patronym, $lastName, $foreName, $birthName, $gender, $birthid, $deathid, $religionid, $originalNationid, $comment){
        //insert into new data
        $newDBManager = $this->get('doctrine')->getManager('new');

        $newPerson = new Person();

        $newPerson->setOid($oid);
        $newPerson->setFirstName($firstName);
        $newPerson->setPatronym($patronym);
        $newPerson->setLastName($lastName);
        $newPerson->setForeName($foreName);
        $newPerson->setBirthName($birthName);
        $newPerson->setGender($gender);
        $newPerson->setBirthid($birthid);
        $newPerson->setDeathid($deathid);
        $newPerson->setReligionid($religionid);
        $newPerson->setOriginalNationid($originalNationid);
        $newPerson->setComment($comment);
        
        $newDBManager->persist($newPerson);
        $newDBManager->flush();

        return $newPerson->getId();
    }

    public function migrateProperty($propertyOrder, $description, $countryid, $territoryid, $locationid, $fromDateid, $toDateid, $provenDateid, $comment){
        //insert into new data
        $newDBManager = $this->get('doctrine')->getManager('new');

        $newProperty = new Property();

        $newProperty->setPropertyOrder($propertyOrder);
        $newProperty->setDescription($description);
        $newProperty->setCountryid($countryid);
        $newProperty->setTerritoryid($territoryid);
        $newProperty->setLocationid($locationid);
        $newProperty->setFromDateid($fromDateid);
        $newProperty->setToDateid($toDateid);
        $newProperty->setProvenDateid($provenDateid);
        $newProperty->setComment($comment);
        
        $newDBManager->persist($newProperty);
        $newDBManager->flush();

        return $newProperty->getId();
    }

    public function migrateRank($rankOrder, $label, $class, $countryid, $territoryid, $locationid, $fromDateid, $toDateid, $provenDateid, $comment){
        //insert into new data
        $newDBManager = $this->get('doctrine')->getManager('new');

        $newRank = new Rank();

        $newRank->setRankOrder($rankOrder);
        $newRank->setLabel($label);
        $newRank->setClass($class);
        $newRank->setCountryid($countryid);
        $newRank->setTerritoryid($territoryid);
        $newRank->setLocationid($locationid);
        $newRank->setFromDateid($fromDateid);
        $newRank->setToDateid($toDateid);
        $newRank->setProvenDateid($provenDateid);
        $newRank->setComment($comment);
        
        $newDBManager->persist($newRank);
        $newDBManager->flush();

        return $newRank->getId();
    }

    public function migrateRelative(){  //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!1
        //insert into new data
        $newDBManager = $this->get('doctrine')->getManager('new');

        $newRelative = new Relative();

        //$newRelative->;
        
        $newDBManager->persist($newRelative);
        $newDBManager->flush();

        return $newRelative->getId();
    }

    public function migrateReligion($name, $comment){
        //insert into new data
        $newDBManager = $this->get('doctrine')->getManager('new');

        $newReligion = new Religion();

        $newReligion->setName($name);
        $newReligion->setComment($comment);
        
        $newDBManager->persist($newReligion);
        $newDBManager->flush();

        return $newReligion->getId();
    }

    public function migrateRoadOfLife($roadOfLifeOrder, $originCountryid, $originTerritoryid, $jobid, $countryid, $territoryid, $locationid, $fromDateid, $toDateid, $provenDateid, $comment){
        //insert into new data
        $newDBManager = $this->get('doctrine')->getManager('new');

        $newRoadOfLife = new RoadOfLife();

        $newRoadOfLife->setRoadOfLifeOrder($roadOfLifeOrder);
        $newRoadOfLife->setOriginCountryid($originCountryid);
        $newRoadOfLife->setOriginTerritoryid($originTerritoryid);
        $newRoadOfLife->setJobid($jobid);
        $newRoadOfLife->setCountryid($countryid);
        $newRoadOfLife->setTerritoryid($territoryid);
        $newRoadOfLife->setLocationid($locationid);
        $newRoadOfLife->setFromDateid($fromDateid);
        $newRoadOfLife->setToDateid($toDateid);
        $newRoadOfLife->setProvenDateid($provenDateid);
        $newRoadOfLife->setComment($comment);
        
        $newDBManager->persist($newRoadOfLife);
        $newDBManager->flush();

        return $newRoadOfLife->getId();
    }

    public function migrateSource($label, $placeOfDiscovery, $remark, $comment){
        //insert into new data
        $newDBManager = $this->get('doctrine')->getManager('new');

        $newSource = new Source();

        $newSource->setLabel($label);
        $newSource->setPlaceOfDiscovery($placeOfDiscovery);
        $newSource->setRemark($remark);
        $newSource->setComment($comment);
        
        $newDBManager->persist($newSource);
        $newDBManager->flush();

        return $newSource->getId();
    }

    public function migrateStatus($statusOrder, $label, $countryid, $territoryid, $locationid, $fromDateid, $toDateid, $provenDateid, $comment){
        //insert into new data
        $newDBManager = $this->get('doctrine')->getManager('new');

        $newStatus = new Status();

        $newStatus->setStatusOrder($statusOrder);
        $newStatus->setLabel($label);
        $newStatus->setCountryid($countryid);
        $newStatus->setTerritoryid($territoryid);
        $newStatus->setLocationid($locationid);
        $newStatus->setFromDateid($fromDateid);
        $newStatus->setToDateid($toDateid);
        $newStatus->setProvenDateid($provenDateid);
        $newStatus->setComment($comment);
        
        $newDBManager->persist($newStatus);
        $newDBManager->flush();

        return $newStatus->getId();
    }

    public function migrateTerritory($name, $comment){
        //insert into new data
        $newDBManager = $this->get('doctrine')->getManager('new');

        $newTerritory = new Territory();

        $newTerritory->setName($name);
        $newTerritory->setComment($comment);
        
        $newDBManager->persist($newTerritory);
        $newDBManager->flush();

        return $newTerritory->getId();
    }

    public function migrateWedding($weddingOrder, $husbandId, $wifeId, $relationType, $weddingDateid, $weddingLocationid, $weddingTerritoryid, $bannsDateid, $breakupReason, $breakupDateid, $marriageComment, $beforeAfter, $comment){
        //insert into new data
        $newDBManager = $this->get('doctrine')->getManager('new');

        $newWedding = new Wedding();

        $newWedding->setWeddingOrder($weddingOrder);
        $newWedding->setHusbandId($husbandId);
        $newWedding->setWifeId($wifeId);
        $newWedding->setRelationType($relationType);
        $newWedding->setWeddingDateid($weddingDateid);
        $newWedding->setWeddingLocationid($weddingLocationid);
        $newWedding->setWeddingTerritoryid($weddingTerritoryid);
        $newWedding->setBannsDateid($bannsDateid);
        $newWedding->setBreakupReason($breakupReason);
        $newWedding->setBreakupDateid($breakupDateid);
        $newWedding->setMarriageComment($marriageComment);
        $newWedding->setBeforeAfter($beforeAfter);
        $newWedding->setComment($comment);
        
        $newDBManager->persist($newWedding);
        $newDBManager->flush();

        return $newWedding->getId();
    }

    public function migrateWorks($label, $countryid, $locationid, $fromDateid, $toDateid, $territoryid, $provenDateid, $comment){
        //insert into new data
        $newDBManager = $this->get('doctrine')->getManager('new');

        $newWorks = new Works();

        $newWorks->setLabel($label);
        $newWorks->setCountryid($countryid);
        $newWorks->setLocationid($locationid);
        $newWorks->setFromDateid($fromDateid);
        $newWorks->setToDateid($toDateid);
        $newWorks->setTerritoryid($territoryid);
        $newWorks->setProvenDateid($provenDateid);
        $newWorks->setComment($comment);
        
        $newDBManager->persist($newWorks);
        $newDBManager->flush();

        return $newWorks->getId();
    }

}
