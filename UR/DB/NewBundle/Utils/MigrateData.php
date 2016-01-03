<?php

namespace UR\DB\NewBundle\Utils;

use UR\DB\NewBundle\Entity\Baptism;
use UR\DB\NewBundle\Entity\Birth;
use UR\DB\NewBundle\Entity\Country;
use UR\DB\NewBundle\Entity\Date;
use UR\DB\NewBundle\Entity\Death;
use UR\DB\NewBundle\Entity\Education;
use UR\DB\NewBundle\Entity\Honour;
use UR\DB\NewBundle\Entity\IsGrandparent;
use UR\DB\NewBundle\Entity\Job;
use UR\DB\NewBundle\Entity\JobClass;
use UR\DB\NewBundle\Entity\Location;
use UR\DB\NewBundle\Entity\Nation;
use UR\DB\NewBundle\Entity\Person;
use UR\DB\NewBundle\Entity\Property;
use UR\DB\NewBundle\Entity\Rank;
use UR\DB\NewBundle\Entity\Relative;
use UR\DB\NewBundle\Entity\Religion;
use UR\DB\NewBundle\Entity\Residence;
use UR\DB\NewBundle\Entity\RoadOfLife;
use UR\DB\NewBundle\Entity\Source;
use UR\DB\NewBundle\Entity\Status;
use UR\DB\NewBundle\Entity\Territory;
use UR\DB\NewBundle\Entity\Works;


abstract class RelationTypes
{
    const PERSON_TO_PERSON = 0;
    const PERSON_TO_RELATIVE = 1;
    const PERSON_TO_PARTNER = 2;

    const RELATIVE_TO_PERSON= 3;
    const RELATIVE_TO_RELATIVE = 4;
    const RELATIVE_TO_PARTNER = 5;

    const PARTNER_TO_PERSON = 6;
    const PARTNER_TO_RELATIVE = 7;
    const PARTNER_TO_PARTNER = 8;
}

class MigrateData
{

    //http://stackoverflow.com/questions/15491894/regex-to-validate-date-format-dd-mm-yyyy/26972181#26972181
    //private $DATE_REGEX = "/^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]|(?:Jan|Mar|May|Jul|Aug|Oct|Dec)))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2]|(?:Jan|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec))\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)(?:0?2|(?:Feb))\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9]|(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep))|(?:1[0-2]|(?:Oct|Nov|Dec)))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/";
    const DATE_REGEX = "/^(\D*)(0|0?[1-9]|[12][0-9]|3[01])[\.\-](0|0?[1-9]|1[012])[\.\-](\d{4})(.*)$/";

    const PERSON_CLASS = "UR\DB\NewBundle\Entity\Person";
    const RELATIVE_CLASS = "UR\DB\NewBundle\Entity\Relative";
    const PARTNER_CLASS = "UR\DB\NewBundle\Entity\Partner";



    private $container;
    private $newDBManager;

	public function __construct($container)
    {
        $this->container = $container;
        $this->newDBManager = $this->get('doctrine')->getManager('new');
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
        if($comment == null || $comment == ""){
            $country = $this->newDBManager->getRepository('NewBundle:Country')->findOneByName($countryName);

            if($country != null){
                return $country->getId();
            }
        }else{
            $country = $this->newDBManager->getRepository('NewBundle:Country')->findOneBy(array('name' => $countryName, 'comment' => $comment));

            if($country != null){
                return $country->getId();
            }
        }

        // if it does not exist, create it and return the new value
        $newCountry = new Country();

        $newCountry->setName($countryName);
        $newCountry->setComment($comment);
        
        $this->newDBManager->persist($newCountry);
        $this->newDBManager->flush();

        return $newCountry->getId();
    }

    public function getTerritoryId($territoryName, $comment = null){
        if($territoryName == "" || $territoryName == null){
            return null;
        }

        // check if territory exists
        if($comment == null || $comment == ""){
            $territory = $this->newDBManager->getRepository('NewBundle:Territory')->findOneByName($territoryName);

            if($territory != null){
                return $territory->getId();
            }
        }else{
            $territory = $this->newDBManager->getRepository('NewBundle:Territory')->findOneBy(array('name' => $territoryName, 'comment' => $comment));

            if($territory != null){
                return $territory->getId();
            }
        }

        // if it does not exist, create it and return the new value
        $newTerritory = new Territory();

        $newTerritory->setName($territoryName);
        $newTerritory->setComment($comment);
        
        $this->newDBManager->persist($newTerritory);
        $this->newDBManager->flush();

        return $newTerritory->getId();
    }

    public function getLocationId($locationName, $comment = null){
        if($locationName == "" || $locationName == null){
            return null;
        }

        // check if location exists
        if($comment == null || $comment == ""){
            $location = $this->newDBManager->getRepository('NewBundle:Location')->findOneByName($locationName);

            if($location != null){
                return $location->getId();
            }

        }else{
            $location = $this->newDBManager->getRepository('NewBundle:Location')->findOneBy(array('name' => $locationName, 'comment' => $comment));

            if($location != null){
                return $location->getId(); 
            }
        }

        // if it does not exist, create it and return the new value
        $newLocation = new Location();

        $newLocation->setName($locationName);
        $newLocation->setComment($comment);
        
        $this->newDBManager->persist($newLocation);
        $this->newDBManager->flush();

        return $newLocation->getId();
    }

    public function getNationId($nationName, $comment = null){
        if($nationName == "" || $nationName == null){
            return null;
        }

        // check if location exists
        if($comment == null || $comment == ""){
            $nation = $this->newDBManager->getRepository('NewBundle:Nation')->findOneByName($nationName);

            if($nation != null){
                return $nation->getId(); 
            }
        }else{
            $nation = $this->newDBManager->getRepository('NewBundle:Nation')->findOneBy(array('name' => $nationName, 'comment' => $comment));

            if($nation != null){
                return $nation->getId(); 
            }
        }

        // if it does not exist, create it and return the new value
        $newNation = new Nation();

        $newNation->setName($nationName);
        $newNation->setComment($comment);
        
        $this->newDBManager->persist($newNation);
        $this->newDBManager->flush();

        return $newNation->getId();
    }

    public function getJobId($jobLabel, $comment = null){
        if($jobLabel == "" || $jobLabel == null){
            return null;
        }

        // check if job exists
        if($comment == null || $comment == ""){
            $job = $this->newDBManager->getRepository('NewBundle:Job')->findOneByLabel($jobLabel);

            if($job != null){
                return $job->getId();
            }
        }else{
            $job = $this->newDBManager->getRepository('NewBundle:Job')->findOneBy(array('label' => $jobLabel, 'comment' => $comment));

            if($job != null){
                return $job->getId();
            }
        }

        // if it does not exist, create it and return the new value
        $newJob = new Job();

        $newJob->setLabel($jobLabel);
        $newJob->setComment($comment);
        
        $this->newDBManager->persist($newJob);
        $this->newDBManager->flush();

        return $newJob->getId();
    }

    // can get multiple jobclasses?
    public function getJobClassId($jobClassLabel, $comment = null){
        if($jobClassLabel == "" || $jobClassLabel == null){
            return null;
        }

        // check if jobClass exists
        if($comment == null || $comment == ""){
            $jobClass = $this->newDBManager->getRepository('NewBundle:JobClass')->findOneByLabel($jobClassLabel);

            if($jobClass != null){
                return $jobClass->getId();
            }
        }else{
            $jobClass = $this->newDBManager->getRepository('NewBundle:JobClass')->findOneBy(array('label' => $jobClassLabel, 'comment' => $comment));

            if($jobClass != null){
                return $jobClass->getId();
            }
        }

        // if it does not exist, create it and return the new value
        $newJobClass = new JobClass();

        $newJobClass->setLabel($jobClassLabel);
        
        $this->newDBManager->persist($newJobClass);
        $this->newDBManager->flush();

        return $newJobClass->getId();
    }

    //returns 0-many date objects
    public function getDate($dateString, $comment = null){
        $newDatesArray = [];

        if($dateString == "" || $dateString == null){
            return null;
        }

        // check if date exists
        $datesArray = $this->extractDatesArray($dateString);

        for($i = 0; $i < count($datesArray); $i++){
            $currDateString = $datesArray[$i];

            $newDate = $this->createRealDateFromString($currDateString);
            $newDatesArray[] = $newDate;
            $this->newDBManager->persist($newDate);
        }

        //first flush to get ids later
        $this->newDBManager->flush();

        //now collect ids for the calling method
        $dateIdArray = [];

        for($i = 0; $i < count($newDatesArray); $i++){
            $dateId = $newDatesArray[$i]->getId();
            $dateIdArray[] = $dateId;
        }

        return $this->createStringFromIdArray($dateIdArray);
    }

    private function createStringFromIdArray($idArray){

        $uniqueArray = array_unique($idArray);

        return implode(",", $uniqueArray);
    }

    private function extractDatesArray($dateString){
        $datesArray = [];

        //add more special cases!
        if(strpos($dateString, ";")){
            $datesArray = explode(";", $dateString);
        }else {
            //when only one exists?
            $datesArray[] = $dateString;
        }

        return $datesArray;
    }


    //@ToDO:
    //31.12.1793-1.1.1796   
    // for things like this return array with dates?? but how to persist between?
    //OLD DB ID => 204
    private function createRealDateFromString($dateString){
        //echo "real date: ".$dateString;

        $dateString = trim($dateString);

        preg_match(self::DATE_REGEX, $dateString, $date);

        //print_r($date);

        // create a date object
        $newDate = new Date();

        if(count($date) > 0){
            if(strpos($date[1], "- im Original")){
                $newDate->setComment($date[0]);
                return $newDate;
            }

            //found date, do the right things...
            $newDate->setDay($date[2]);
            $newDate->setMonth($date[3]);
            $newDate->setYear($date[4]);

            $commentString = "";

            if($date[1] != ""){
                if($date[1] == "-"){
                    $newDate->setBeforeDate(1);
                } else{
                    $commentString .= trim($date[1]);
                }
            }

            if($date[5] != ""){
                if($date[5] == "-"){
                    $newDate->setAfterDate(1);
                } else if(substr($date[5],0,1) == "-"){
                    $newDate->setAfterDate(1);
                     $commentString .= trim(substr($date[5],1));
                } else{
                    $commentString .= trim($date[5]);
                }
            }

            if($commentString != ""){
                $newDate->setComment($commentString);
            }

        }else{
            $newDate->setComment("ERROR: " . $dateString);
        }

        return $newDate;
    }


    private function getGender($genderString){
        //undefined = 0, male = 1, female = 2

        if($genderString == "männlich"){
            return 1;
        }else if($genderString == "weiblich"){
            return 2;
        }

        return 0;
    }

    /* end helper method */

    public function migrateBirth($originCountry, $originTerritory=null, $originLocation=null, $birthCountry=null, $birthLocation=null, $birthDate=null, $birthTerritory=null, $comment=null){
        //insert into new data
        $newBirth = new Birth();

        $newBirth->setOriginCountryid($this->getCountryId($originCountry));
        $newBirth->setOriginTerritoryid($this->getTerritoryId($originTerritory));
        $newBirth->setOriginLocationid($this->getLocationId($originLocation));
        $newBirth->setBirthCountryid($this->getCountryId($birthCountry));
        $newBirth->setBirthLocationid($this->getLocationId($birthLocation));
        $newBirth->setBirthTerritoryid($this->getTerritoryId($birthTerritory));
        $newBirth->setComment($comment);

        $newBirth->setBirthDateId($this->getDate($birthDate));

        $this->newDBManager->persist($newBirth);
        $this->newDBManager->flush();

        return $newBirth->getId();
    }

    public function migrateBaptism($baptismDate, $baptismLocation){
        //insert into new data
        $newBaptism = new Baptism();
        $newBaptism->setBaptismLocationid($this->getLocationId($baptismLocation));
        $newBaptism->setBaptismDateId($this->getDate($baptismDate));
        
        $this->newDBManager->persist($newBaptism);
        $this->newDBManager->flush();

        return $newBaptism->getId();
    }

    public function migrateCountry($name, $comment=null){
        //insert into new data
        return $this->getCountryId($name, $comment);
    }

    public function migrateDate($day, $month, $year, $weekday, $comment=null){
        //insert into new data
        $newDate = new Date();

        $newDate->setDay($day);
        $newDate->setMonth($month);
        $newDate->setYear($year);
        $newDate->setWeekday($weekday);
        $newDate->setComment($comment);
        
        $this->newDBManager->persist($newDate);
        $this->newDBManager->flush();

        return $newDate->getId();
    }

    public function migrateDeath($deathLocation, $deathDate, $deathCountry=null, $causeOfDeath=null, $territoryOfDeath=null, $graveyard=null, $funeralLocation=null, $funeralDate=null, $comment=null){
        //insert into new data
        $newDeath = new Death();

        $newDeath->setDeathLocationid($this->getLocationId($deathLocation));
        $newDeath->setDeathCountryid($this->getCountryId($deathCountry));
        $newDeath->setCauseOfDeath($causeOfDeath);
        $newDeath->setTerritoryOfDeathid($this->getTerritoryId($territoryOfDeath));
        $newDeath->setGraveyard($graveyard);
        $newDeath->setFuneralLocationid($this->getLocationId($funeralLocation));
        $newDeath->setComment($comment);
        $newDeath->setDeathDateId($this->getDate($deathDate));
        $newDeath->setFuneralDateId($this->getDate($funeralDate));
        
        $this->newDBManager->persist($newDeath);
        $this->newDBManager->flush();

        return $newDeath->getId();
    }

    public function migrateEducation($educationOrder, $label, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $graduationLabel=null, $graduationDate=null, $graduationLocation=null, $comment=null){
        //insert into new data
        $newEducation = new Education();

        $newEducation->setEducationOrder($educationOrder);
        $newEducation->setLabel($label);
        $newEducation->setCountryid($this->getCountryId($country));
        $newEducation->setTerritoryid($this->getTerritoryId($territory));
        $newEducation->setLocationid($this->getLocationId($location));
        $newEducation->setFromDateid($this->getDate($fromDate));
        $newEducation->setToDateid($this->getDate($toDate));
        $newEducation->setProvenDateid($this->getDate($provenDate));
        $newEducation->setGraduationLabel($graduationLabel);
        $newEducation->setGraduationDateid($this->getDate($graduationDate));
        $newEducation->setGraduationLocationid($this->getLocationId($graduationLocation));
        $newEducation->setComment($comment);
        
        $this->newDBManager->persist($newEducation);
        $this->newDBManager->flush();

        return $newEducation->getId();
    }

    public function migrateHonour($honourOrder, $label, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $comment=null){
        //insert into new data
        $newHonour = new Honour();

        $newHonour->setHonourOrder($honourOrder);
        $newHonour->setLabel($label);
        $newHonour->setCountryid($this->getCountryId($country));
        $newHonour->setTerritoryid($this->getTerritoryId($territory));
        $newHonour->setLocationid($this->getLocationId($location));
        $newHonour->setFromDateid($this->getDate($fromDate));
        $newHonour->setToDateid($this->getDate($toDate));
        $newHonour->setProvenDateid($this->getDate($provenDate));
        $newHonour->setComment($comment);
        
        $this->newDBManager->persist($newHonour);
        $this->newDBManager->flush();

        return $newHonour->getId();
    }

    public function migrateIsChild(){  //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        //insert into new data
        $newIsChild = new IsChild();

        //$newIsChild->;
        
        $this->newDBManager->persist($newIsChild);
        $this->newDBManager->flush();

        return $newIsChild->getId();
    }

    public function migrateIsGrandchild(){ //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!1
        //insert into new data
        $newIsGrandchild = new IsGrandchild();

        //$newIsGrandchild->;
        
        $this->newDBManager->persist($newIsGrandchild);
        $this->newDBManager->flush();

        return $newIsGrandchild->getId();
    }

    public function migrateIsGrandparent($grandchild, $grandparent, $paternal, $comment=null){   //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        //insert into new data
        $newIsGrandparent = new IsGrandparent();

        $newIsGrandparent->setGrandChildid($grandchild->getId());
        $newIsGrandparent->setGrandParentid($grandparent->getId());
        $newIsGrandparent->setRelationType($this->getRelationType($grandchild, $grandparent));
        $newIsGrandparent->setIsPaternal($paternal);
        $newIsGrandparent->setComment($comment);
        
        $this->newDBManager->persist($newIsGrandparent);
        $this->newDBManager->flush();

        return $newIsGrandparent->getId();
    }

    public function migrateIsParent(){   //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        //insert into new data
        $newIsParent = new IsParent();

        //$newIsParent->;
        
        $this->newDBManager->persist($newIsParent);
        $this->newDBManager->flush();

        return $newIsParent->getId();
    }

    public function migrateIsParentInLaw(){  //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        //insert into new data
        $newIsParentInLaw = new IsParentInLaw();

        //$newIsParentInLaw->;
        
        $this->newDBManager->persist($newIsParentInLaw);
        $this->newDBManager->flush();

        return $newIsParentInLaw->getId();
    }

    public function migrateIsSibling(){  //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!1
        //insert into new data
        $newIsSibling = new IsSibling();

        //$newIsSibling->;
        
        $this->newDBManager->persist($newIsSibling);
        $this->newDBManager->flush();

        return $newIsSibling->getId();
    }

    public function migrateJob($label, $comment=null){
        //insert into new data
        return $this->getJobId($label, $comment);
    }

    public function migrateLocation($name, $comment=null){
        //insert into new data
        return $this->getLocationId($name, $comment);
    }

    public function migrateNation($name, $comment=null){
        //insert into new data
        return $this->getNationId($name, $comment);
    }

    //add additional stuff?
    //born_in_marriage (from mother/ father?)
    //weddingID
    public function migratePerson($oid, $firstName, $patronym, $lastName, $foreName, $birthName, $gender, $jobClass, $comment=null){
        //insert into new data
        $newPerson = new Person();

        $newPerson->setOid($oid);
        $newPerson->setFirstName($firstName);
        $newPerson->setPatronym($patronym);
        $newPerson->setLastName($lastName);
        $newPerson->setForeName($foreName);
        $newPerson->setBirthName($birthName);
        $newPerson->setGender($this->getGender($gender));
        $newPerson->setJobClassId($this->getJobClassId($jobClass));
        $newPerson->setComment($comment);

        $this->newDBManager->persist($newPerson);
        $this->newDBManager->flush();

        return $newPerson;
    }

    public function migrateProperty($propertyOrder, $label, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $comment=null){
        //insert into new data
        $newProperty = new Property();

        $newProperty->setPropertyOrder($propertyOrder);
        $newProperty->setLabel($label);
        $newProperty->setCountryid($this->getCountryId($country));
        $newProperty->setTerritoryid($this->getTerritoryId($territory));
        $newProperty->setLocationid($this->getLocationId($location));
        $newProperty->setFromDateid($this->getDate($fromDate));
        $newProperty->setToDateid($this->getDate($toDate));
        $newProperty->setProvenDateid($this->getDate($provenDate));
        $newProperty->setComment($comment);
        
        $this->newDBManager->persist($newProperty);
        $this->newDBManager->flush();

        return $newProperty->getId();
    }

    public function migrateRank($rankOrder, $label, $class=null, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $comment=null){
        //insert into new data
        $newRank = new Rank();

        $newRank->setRankOrder($rankOrder);
        $newRank->setLabel($label);
        $newRank->setClass($class);
        $newRank->setCountryid($this->getCountryId($country));
        $newRank->setTerritoryid($this->getTerritoryId($territory));
        $newRank->setLocationid($this->getLocationId($location));
        $newRank->setFromDateid($this->getDate($fromDate));
        $newRank->setToDateid($this->getDate($toDate));
        $newRank->setProvenDateid($this->getDate($provenDate));
        $newRank->setComment($comment);
        
        $this->newDBManager->persist($newRank);
        $this->newDBManager->flush();

        return $newRank->getId();
    }

    public function migrateRelative($firstName, $patronym, $lastName, $gender, $nation=null, $comment=null){
        //insert into new data
        $newRelative = new Relative();

        $newRelative->setFirstName($firstName);
        $newRelative->setPatronym($patronym);
        $newRelative->setLastName($lastName);
        $newRelative->setGender($this->getGender($gender));
        $newRelative->setNationid($this->getNationId($nation));

        $newRelative->setComment($comment);

        $this->newDBManager->persist($newRelative);
        $this->newDBManager->flush();

        return $newRelative;
    }

    public function migrateReligion($name, $religionOrder, $change_of_religion=null, $provenDate=null, $fromDate=null, $comment=null){
        //insert into new data
        $newReligion = new Religion();

        $newReligion->setName($name);
        $newReligion->setReligionOrder($religionOrder);
        $newReligion->setChangeOfReligion($change_of_religion);
        $newReligion->setComment($comment);

        $newReligion->setProvenDateid($this->getDate($provenDate));
        $newReligion->setFromDateId($this->getDate($fromDate));
        
        $this->newDBManager->persist($newReligion);
        $this->newDBManager->flush();

        return $newReligion->getId();
    }

    public function migrateResidence($residenceOrder, $residenceCountry, $residenceTerritory, $residenceLocation){
        //insert into new data
        $newResidence = new Residence();

        $newResidence->setResidenceOrder($residenceOrder);
        $newResidence->setResidenceCountryid($this->getCountryId($residenceCountry));
        $newResidence->setResidenceTerritoryid($this->getTerritoryId($residenceTerritory));
        $newResidence->setResidenceLocationid($this->getLocationId($residenceLocation));
        

        $this->newDBManager->persist($newResidence);
        $this->newDBManager->flush();

        return $newResidence->getId();
    }

    public function migrateRoadOfLife($roadOfLifeOrder, $originCountry, $originTerritory, $job, $country, $territory, $location, $fromDate, $toDate, $provenDate, $comment=null){
        //insert into new data
        $newRoadOfLife = new RoadOfLife();

        $newRoadOfLife->setRoadOfLifeOrder($roadOfLifeOrder);
        $newRoadOfLife->setOriginCountryid($this->getCountryId($originCountry));
        $newRoadOfLife->setOriginTerritoryid($this->getTerritoryId($originTerritory));
        $newRoadOfLife->setJobid($this->getJobId($job));
        $newRoadOfLife->setCountryid($this->getCountryId($country));
        $newRoadOfLife->setTerritoryid($this->getTerritoryId($territory));
        $newRoadOfLife->setLocationid($this->getLocationId($location));
        $newRoadOfLife->setFromDateid($this->getDate($fromDate));
        $newRoadOfLife->setToDateid($this->getDate($toDate));
        $newRoadOfLife->setProvenDateid($this->getDate($provenDate));
        $newRoadOfLife->setComment($comment);
        
        $this->newDBManager->persist($newRoadOfLife);
        $this->newDBManager->flush();

        return $newRoadOfLife->getId();
    }

    public function migrateSource($sourceOrder, $label, $placeOfDiscovery=null, $remark=null, $comment=null){
        //insert into new data
        $newSource = new Source();

        $newSource->setSourceOrder($sourceOrder);
        $newSource->setLabel($label);
        $newSource->setPlaceOfDiscovery($placeOfDiscovery);
        $newSource->setRemark($remark);
        $newSource->setComment($comment);
        
        $this->newDBManager->persist($newSource);
        $this->newDBManager->flush();

        return $newSource->getId();
    }

    public function migrateStatus($statusOrder, $label, $country=null, $territory=null, $location=null, $fromDate=null, $toDate=null, $provenDate=null, $comment=null){
        //insert into new data
        $newStatus = new Status();

        $newStatus->setStatusOrder($statusOrder);
        $newStatus->setLabel($label);
        $newStatus->setCountryid($this->getCountryId($country));
        $newStatus->setTerritoryid($this->getTerritoryId($territory));
        $newStatus->setLocationid($this->getLocationId($location));
        $newStatus->setFromDateid($this->getDate($fromDate));
        $newStatus->setToDateid($this->getDate($toDate));
        $newStatus->setProvenDateid($this->getDate($provenDate));
        $newStatus->setComment($comment);
        
        $this->newDBManager->persist($newStatus);
        $this->newDBManager->flush();

        return $newStatus->getId();
    }

    public function migrateTerritory($name, $comment=null){
        //insert into new data
        return $this->getTerritoryId($name, $comment);
    }

    public function migrateWedding($weddingOrder, $husbandId, $wifeId, $relationType, $weddingDateid, $weddingLocationid, $weddingTerritoryid, $bannsDateid, $breakupReason, $breakupDateid, $marriageComment, $beforeAfter, $comment){
        //insert into new data
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
        
        $this->newDBManager->persist($newWedding);
        $this->newDBManager->flush();

        return $newWedding->getId();
    }

    public function migrateWork($label, $works_order, $country=null, $location=null, $fromDate=null, $toDate=null, $territory=null, $provenDate=null, $comment=null){
        //insert into new data
        $newWorks = new Works();

        $newWorks->setLabel($label);
        $newWorks->setWorksOrder($works_order);
        $newWorks->setCountryid($this->getCountryId($country));
        $newWorks->setLocationid($this->getLocationId($location));
        $newWorks->setFromDateid($this->getDate($fromDate));
        $newWorks->setToDateid($this->getDate($toDate));
        $newWorks->setTerritoryid($this->getTerritoryId($territory));
        $newWorks->setProvenDateid($this->getDate($provenDate));
        $newWorks->setComment($comment);
        
        $this->newDBManager->persist($newWorks);
        $this->newDBManager->flush();

        return $newWorks->getId();
    }

    public function savePerson($person){
        $this->newDBManager->persist($person);
        $this->newDBManager->flush();
    }

    public function getNewPersonForOid($OID){
        return $this->newDBManager->getRepository('NewBundle:Person')->findOneByOid($OID);
    }

    public function getRelationType($firstDataEntry, $secondDataEntry){

        $firstClass = get_class($firstDataEntry);

        $secondClass = get_class($secondDataEntry);

        $type = -1;

        switch($firstClass){
            case self::PERSON_CLASS:
                switch($secondClass){
                    case self::PERSON_CLASS:
                        $type = RelationTypes::PERSON_TO_PERSON;
                        break;
                    case self::RELATIVE_CLASS:
                        $type = RelationTypes::PERSON_TO_RELATIVE;
                        break;
                    case self::PARTNER_CLASS:
                        $type = RelationTypes::PERSON_TO_PARTNER;
                        break;
                }
                break;
            case self::RELATIVE_CLASS:
                switch($secondClass){
                    case self::PERSON_CLASS:
                        $type = RelationTypes::RELATIVE_TO_PERSON;
                        break;
                    case self::RELATIVE_CLASS:
                        $type = RelationTypes::RELATIVE_TO_RELATIVE;
                        break;
                    case self::PARTNER_CLASS:
                        $type = RelationTypes::RELATIVE_TO_PARTNER;
                        break;
                }
                break;
            case self::PARTNER_CLASS:
                switch($secondClass){
                    case self::PERSON_CLASS:
                        $type = RelationTypes::PARTNER_TO_PERSON;
                        break;
                    case self::RELATIVE_CLASS:
                        $type = RelationTypes::PARTNER_TO_RELATIVE;
                        break;  
                    case self::PARTNER_CLASS:
                        $type = RelationTypes::PARTNER_TO_PARTNER;
                        break;
                }
                break;
        }

        return $type;
    }

}
