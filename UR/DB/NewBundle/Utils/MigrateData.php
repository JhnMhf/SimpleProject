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
use UR\DB\NewBundle\Entity\IsParent;
use UR\DB\NewBundle\Entity\IsParentInLaw;
use UR\DB\NewBundle\Entity\IsSibling;
use UR\DB\NewBundle\Entity\Job;
use UR\DB\NewBundle\Entity\JobClass;
use UR\DB\NewBundle\Entity\Location;
use UR\DB\NewBundle\Entity\Nation;
use UR\DB\NewBundle\Entity\Partner;
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
use UR\DB\NewBundle\Entity\Wedding;
use UR\DB\NewBundle\Entity\Works;
use UR\DB\NewBundle\Utils\DateRange;

abstract class RelationTypes {

    const PERSON_TO_PERSON = 0;
    const PERSON_TO_RELATIVE = 1;
    const PERSON_TO_PARTNER = 2;
    const RELATIVE_TO_PERSON = 3;
    const RELATIVE_TO_RELATIVE = 4;
    const RELATIVE_TO_PARTNER = 5;
    const PARTNER_TO_PERSON = 6;
    const PARTNER_TO_RELATIVE = 7;
    const PARTNER_TO_PARTNER = 8;

}

class MigrateData {

    //http://stackoverflow.com/questions/15491894/regex-to-validate-date-format-dd-mm-yyyy/26972181#26972181
    //private $DATE_REGEX = "/^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]|(?:Jan|Mar|May|Jul|Aug|Oct|Dec)))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2]|(?:Jan|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec))\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)(?:0?2|(?:Feb))\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9]|(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep))|(?:1[0-2]|(?:Oct|Nov|Dec)))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/";
    const DATE_REGEX = "/^(\D*)(0|00|0?[1-9]|[12][0-9]|3[01])?( ?)[\.\-\ \:]( ?)(0|00|0?[1-9]|1[012])?( ?)[\.\-\ \:]( ?)(00|0|\d{4})(.*)$/";
    const YEAR_REGEX = "/^(\-)?(\d{4})(\-)?(.*)$/";
    const PERSON_CLASS = "UR\DB\NewBundle\Entity\Person";
    const RELATIVE_CLASS = "UR\DB\NewBundle\Entity\Relative";
    const PARTNER_CLASS = "UR\DB\NewBundle\Entity\Partner";

    private $LOGGER;
    private $container;
    private $newDBManager;
    private $normalizationService;
    private $locationToTerritoryService;
    private $locationGeodataService;

    public function __construct($container) {
        $this->container = $container;
        $this->LOGGER = $this->get('monolog.logger.migrateNew');
        $this->normalizationService = $this->get("normalization.service");
        $this->locationToTerritoryService = $this->get("locationToTerritory.service");
        $this->locationGeodataService = $this->get('locationGeodata.service');
    }
    
    private function getDBManager(){
        if(is_null($this->newDBManager) || !$this->newDBManager->isOpen()){
            $this->newDBManager = $this->get('doctrine')->getManager('new');
        }
        
        return $this->newDBManager;
    }

    private function get($identifier) {
        return $this->container->get($identifier);
    }

    /* helper method */

    private function normalize($string) {
        return $this->normalizationService->writeOutAbbreviations($string);
    }
    
    private function normalizeName($string) {
        return $this->normalizationService->writeOutNameAbbreviations($string);
    }
    
    private function normalizePatronym($string) {
        return $this->normalizationService->writeOutPatronymAbbreviations($string);
    }
    
    private function normalizeFirstname($string) {
        return $this->normalizationService->writeOutFirstnameAbbreviations($string);
    }

    public function getCountry($countryName, $comment = null) {
        $countryName = $this->normalize($countryName);
        
        if ($countryName == "" || $countryName == null) {
            return null;
        }

        // check if country exists
        if ($comment == null || $comment == "") {
            $result = $this->tryExtractingNameAndCommentFromString($countryName);
            $countryName = $result[0];
            $comment = $result[1];

            $country = $this->getDBManager()->getRepository('NewBundle:Country')->findOneByName($countryName);

            if ($country != null) {
                return $country;
            }
        } else {
            $country = $this->getDBManager()->getRepository('NewBundle:Country')->findOneBy(array('name' => $countryName, 'comment' => $comment));

            if ($country != null) {
                return $country;
            }
        }

        // if it does not exist, create it and return the new value
        $newCountry = new Country();

        $newCountry->setName($countryName);
        $newCountry->setComment($this->normalize($comment));

        $this->getDBManager()->persist($newCountry);
        $this->getDBManager()->flush();

        return $newCountry;
    }

    public function getTerritory($territoryName, $locationName = null, $comment = null) {
        $territoryName = $this->normalize($territoryName);
        
        if ($territoryName == "" || $territoryName == null) {
            if ($locationName == "" || $locationName == null) {
                return null;
            }

            //normalize locationName a second time...
            $locationName = $this->normalize($locationName);

            //returns null if no information are in the database
            $territoryName = $this->locationToTerritoryService->getTerritoryForLocation($locationName);
            
            if ($territoryName == null) {
                return null;
            }
        }
        
        // check if territory exists
        if ($comment == null || $comment == "") {
            $result = $this->tryExtractingNameAndCommentFromString($territoryName);
            $territoryName = $result[0];
            $comment = $result[1];

            $territory = $this->getDBManager()->getRepository('NewBundle:Territory')->findOneByName($territoryName);

            if ($territory != null) {
                return $territory;
            }
        } else {
            $territory = $this->getDBManager()->getRepository('NewBundle:Territory')->findOneBy(array('name' => $territoryName, 'comment' => $comment));

            if ($territory != null) {
                return $territory;
            }
        }

        // if it does not exist, create it and return the new value
        $newTerritory = new Territory();

        $newTerritory->setName($territoryName);
        $newTerritory->setComment($this->normalize($comment));

        $this->getDBManager()->persist($newTerritory);
        $this->getDBManager()->flush();

        return $newTerritory;
    }

    public function getLocation($locationName, $comment = null) {
        $locationName = $this->normalize($locationName);
        
        if ($locationName == "" || $locationName == null) {
            return null;
        }

        // check if location exists
        if ($comment == null || $comment == "") {
            $result = $this->tryExtractingNameAndCommentFromString($locationName);
            $locationName = $result[0];
            $comment = $result[1];

            $location = $this->getDBManager()->getRepository('NewBundle:Location')->findOneByName($locationName);

            if ($location != null) {
                return $location;
            }
        } else {
            $location = $this->getDBManager()->getRepository('NewBundle:Location')->findOneBy(array('name' => $locationName, 'comment' => $comment));

            if ($location != null) {
                return $location;
            }
        }

        // if it does not exist, create it and return the new value
        $newLocation = new Location();

        $newLocation->setName($locationName);
        $newLocation->setComment($this->normalize($comment));
        
        //try to get geodata
        $geodata = $this->locationGeodataService->getGeodataForLocation($locationName);
        
        if(!is_null($geodata)){
            $newLocation->setLatitude($geodata[0]);
            $newLocation->setLongitude($geodata[1]);
        }

        $this->getDBManager()->persist($newLocation);
        $this->getDBManager()->flush();

        return $newLocation;
    }

    //@TODO: Move check if existing location,territory,nation etc. exists into db eventhandler for new and final.
    //This should be done to prevent someone from changing it in the person correction for all persons
    //Thus it is necessary to ignore the ids for this fields during serialization
    public function getNation($nationName, $comment = null) {
        $nationName = $this->normalize($nationName);
        
        if ($nationName == "" || $nationName == null) {
            return null;
        }

        // check if location exists
        if ($comment == null || $comment == "") {
            $result = $this->tryExtractingNameAndCommentFromString($nationName);
            $nationName = $result[0];
            $comment = $result[1];

            $nation = $this->getDBManager()->getRepository('NewBundle:Nation')->findOneByName($nationName);

            if ($nation != null) {
                return $nation;
            }
        } else {
            $nation = $this->getDBManager()->getRepository('NewBundle:Nation')->findOneBy(array('name' => $nationName, 'comment' => $comment));

            if ($nation != null) {
                return $nation;
            }
        }

        // if it does not exist, create it and return the new value
        $newNation = new Nation();

        $newNation->setName($nationName);
        $newNation->setComment($this->normalize($comment));

        $this->getDBManager()->persist($newNation);
        $this->getDBManager()->flush();

        return $newNation;
    }

    //Perhaps it belongs to the jobclass and not the job?
    public function getJob($jobLabel, $comment = null) {
        $jobLabel = $this->normalize($jobLabel);
        
        $this->LOGGER->debug("Getting job: ".$jobLabel);
        if ($jobLabel == "" || $jobLabel == null) {
            return null;
        }

        // check if job exists
        if ($comment == null || $comment == "") {
            $result = $this->tryExtractingNameAndCommentFromString($jobLabel);
            $jobLabel = $result[0];
            $comment = $result[1];

            $job = $this->getDBManager()->getRepository('NewBundle:Job')->findOneByLabel($jobLabel);

            if ($job != null) {
                return $job;
            }
        } else {


            $job = $this->getDBManager()->getRepository('NewBundle:Job')->findOneBy(array('label' => $jobLabel, 'comment' => $comment));

            if ($job != null) {
                return $job;
            }
        }

        // if it does not exist, create it and return the new value
        $newJob = new Job();

        $newJob->setLabel($jobLabel);
        $newJob->setComment($this->normalize($comment));

        $this->getDBManager()->persist($newJob);
        $this->getDBManager()->flush();

        return $newJob;
    }

    // can get multiple jobclasses?
    public function getJobClass($jobClassLabel, $comment = null) {
        $jobClassLabel = $this->normalize($jobClassLabel);
        
        if ($jobClassLabel == "" || $jobClassLabel == null) {
            return null;
        }

        // check if jobClass exists
        if ($comment == null || $comment == "") {
            $result = $this->tryExtractingNameAndCommentFromString($jobClassLabel);
            $jobClassLabel = $result[0];
            $comment = $result[1];

            $jobClass = $this->getDBManager()->getRepository('NewBundle:JobClass')->findOneByLabel($jobClassLabel);

            if ($jobClass != null) {
                return $jobClass;
            }
        } else {
            $jobClass = $this->getDBManager()->getRepository('NewBundle:JobClass')->findOneBy(array('label' => $jobClassLabel, 'comment' => $comment));

            if ($jobClass != null) {
                return $jobClass;
            }
        }

        // if it does not exist, create it and return the new value
        $newJobClass = new JobClass();

        $newJobClass->setLabel($jobClassLabel);
        $newJobClass->setComment($comment);

        $this->getDBManager()->persist($newJobClass);
        $this->getDBManager()->flush();

        return $newJobClass;
    }

    //@TODO: add more possible comments to extract
    //@TODO: Check if this method should be used for other things (like rank etc.)
    //and how the extracted comment can be combined with existing comments
    private function tryExtractingNameAndCommentFromString($string) {
        $this->LOGGER->debug("Try extracting name and comment from string: " . $string);
        $lowerCaseString = strtolower($string);

        $result = [$string, null];
        
        $containsAnmerkung = strpos($lowerCaseString, strtolower("- Anmerkung:"));
        $containsImOriginal = strpos($lowerCaseString, strtolower("- im Original"));
        if ($containsAnmerkung !== false) {
            $this->LOGGER->debug("Found -Anmerkung: in " . $string);
            $result[0] = substr($string, 0, $containsAnmerkung);
            $result[1] = substr($string, $containsAnmerkung);
        } else if ($containsImOriginal !== false) {
            $this->LOGGER->debug("Found -im Original in " . $string);
            $result[0] = substr($string, 0, $containsImOriginal);
            $result[1] = substr($string, $containsImOriginal);
        } else if (substr($lowerCaseString,-1) == "?"){
            $this->LOGGER->debug("Found an '?' at the end of " . $string);
            $result[0] = substr($string, 0, strlen($string) -1);
            $result[1] = "?";
        }


        $this->LOGGER->debug("Extracted Name '" . $result[0] . "' and comment '" . $result[1] . "'");
        return $result;
    }

    //returns 0-many date objects
    public function getDate($dateString, $comment = null) {
        $newDatesArray = [];

        if ($dateString == "" || $dateString == null) {
            return null;
        }

        // check if date exists
        $datesArray = $this->extractDatesArray($dateString);

        for ($i = 0; $i < count($datesArray); $i++) {
            $currDateString = $datesArray[$i];

            $newDate = $this->extractDateObjFromString($currDateString);
            $newDatesArray[] = $newDate;
        }

        //first flush to get ids later
        $this->getDBManager()->flush();

        return $newDatesArray;
    }
    
    private function extractDatesArray($dateString) {
        $datesArray = [];

        //add more special cases!
        if (strpos($dateString, ";")) {
            $datesArray = explode(";", $dateString);
        } else {
            //when only one exists?
            $datesArray[] = $dateString;
        }

        return $datesArray;
    }

    // for things like this return array with dates?? but how to persist between?
    //OLD DB ID => 204
    //Merged death date of 69955. Position switched? and before set?
    private function extractDateObjFromString($string, $inner = false) {
        $this->LOGGER->debug("Extracting DateObjsFromString: ".$string);
        if ($inner) {
            $this->LOGGER->debug("Probably searching for a date range.");
        }

        $dateString = trim($string);
        
        //try separating comments from dates
        $separatedResult = $this->tryExtractingNameAndCommentFromString($dateString);
        
        $dateString = $separatedResult[0];
        $comment = $this->normalize($separatedResult[1]);
        
        if(is_null($dateString) || count($dateString) == 0){
            $newDate = new Date();
            $newDate->setComment($comment);
            
            return $newDate;
        }
        
        $this->LOGGER->debug("Extracted datestring: ".$dateString);

        preg_match(self::DATE_REGEX, $dateString, $date);
        $newDate = new Date();
        if (count($date) > 0) {
            $secondDate = null;
            $beforeOrComment = $date[1];
            $day = $date[2];
            $month = $date[5];
            $year = $date[8];
            $afterOrComment = $date[9];

            if (strpos($beforeOrComment, "- im Original")) {
                $this->LOGGER->error("Should not happen anymore? ".$date[0]);
                $newDate->setComment($date[0]);
                return $newDate;
            }

            //found date, do the right things...
            if ($day != "0" && $day != "00") {
                $newDate->setDay(intval($day));
            }

            if ($month != "0"  && $month != "00") {
                $newDate->setMonth(intval($month));
            }

            if ($year != "0"  && $year != "00") {
                $newDate->setYear(intval($year));
            }

            $commentString = $comment;

            if ($beforeOrComment != "") {
                if ($beforeOrComment == "-" && !$inner) {
                    $newDate->setBeforeDate(1);
                } else if($beforeOrComment == "-" && $inner){
                    $this->LOGGER->debug("Not setting - as before date and instead ignoring it, because we are in a daterange.");
                } else {
                    $commentString .= trim($beforeOrComment);
                }
            }

            if ($afterOrComment != "") {
                if ($afterOrComment == "-" && !$inner) {
                    $newDate->setAfterDate(1);
                } else if (substr($afterOrComment, 0, 1) == "-") {
                    //persist first date before searching for second date!
                    $this->getDBManager()->persist($newDate);
                    //check for daterange
                    $this->LOGGER->debug("Check for a daterange: ".$afterOrComment);
                    $secondDate = $this->extractDateObjFromString($afterOrComment, true);

                    if ($secondDate == null) {
                        if (!$inner) {
                            $newDate->setAfterDate(1);
                        }

                        $commentString .= trim(substr($afterOrComment, 1));
                    }
                } else if($afterOrComment == "-" && $inner){
                    $this->LOGGER->debug("Not setting - as after date and instead ignoring it, because we are in a daterange.");
                } else {
                    $commentString .= trim($afterOrComment);
                }
            }

            if ($commentString != "") {
                $newDate->setComment($this->normalize($commentString));
            }

            $this->getDBManager()->persist($newDate);

            if ($secondDate != null) {
                $this->getDBManager()->persist($secondDate);
                $dateRange = new DateRange($newDate, $secondDate);
                
                $this->LOGGER->debug("Returning daterange: ".$dateRange);
                
                return $dateRange;
            }
            
            $this->LOGGER->debug("Returning date: ".$newDate);
            return $newDate;
        } else {
            //check if it is perhaps only a year
            
            $secondDate = null;
            preg_match(self::YEAR_REGEX, $dateString, $secondDate);
            
            if(count($secondDate) > 0){
                $beforeYear = $secondDate[1];
                $year = $secondDate[2];
                $afterYear = $secondDate[3];
                $comment = $comment.$secondDate[4];
                
                $newDate->setYear($year);
                $newDate->setComment($comment);
                
                if ($beforeYear != "") {
                    $newDate->setBeforeDate(true);
                }
                
                if ($afterYear != "") {
                    $newDate->setAfterDate(true);
                }
                
                $this->getDBManager()->persist($newDate);
                
                $this->LOGGER->debug("Returning date: ".$newDate);
                return $newDate;
            } else {
                $this->LOGGER->error("ERROR: " . $string);
                $newDate->setComment("ERROR: " . $string);
                $this->getDBManager()->persist($newDate);
                $this->LOGGER->debug("Returning date: ".$newDate);
                return $newDate;
            }
        }
    }

    public function extractGenderAndGenderComment($genderString){
        if(trim($genderString) == ""){
            $this->LOGGER->debug("Empty genderstring.");
            return [Gender::UNKNOWN, null];
        }
        
        $result = $this->tryExtractingNameAndCommentFromString($genderString);
        
        if(trim($result[0]) != ""){
            $guessedGender = $this->getGender($result[0]);
        
            // if the gender is unknown
            if($guessedGender == Gender::UNKNOWN){

                // and the name is not null or empty
                if(!is_null($result[0]) || trim($result[0]) != ""){

                    if(is_null($result[1]) || trim($result[1]) == ""){
                        // if the comment is empty, fill the comment with the name
                        $result[1] = $result[0];
                    } else{
                        // if the comment is already filled, use the original string as comment
                        $result[1] = $genderString;
                    }
                }
            } 
            
            $result[0] = $guessedGender;
        } else{
            $result[0] = Gender::UNKNOWN;
        }

        return $result;
    }
    
    public function getGender($gender) {
        //undefined = 0, male = 1, female = 2
        
        $gender = strtolower(trim($gender));

        if (is_numeric($gender)) {
            if ($gender == Gender::MALE) {
                return Gender::MALE;
            } else if ($gender == Gender::FEMALE) {
                return Gender::FEMALE;
            }

            return Gender::UNKNOWN;
        } else {
            if ($gender == "männlich" || $gender == "m" || $gender == "mm") {
                return Gender::MALE;
            } else if ($gender == "weiblich" || $gender == "f") {
                return Gender::FEMALE;
            }
            
            $this->LOGGER->debug("Could not identify gender: ".$gender);

            return Gender::UNKNOWN;
        }
    }

    public function getOppositeGender($gender) {
        //undefined = 0, male = 1, female = 2

        if (is_numeric($gender)) {
            if ($gender == Gender::MALE) {
                return Gender::FEMALE;
            } else if ($gender == Gender::FEMALE) {
                return Gender::MALE;
            }

            return Gender::UNKNOWN;
        } else {
            if ($gender == "männlich" || $gender == "m" || $gender == "mm") {
                return "weiblich";
            } else if ($gender == "weiblich" || $gender == "f") {
                return "männlich";
            }
            
            $this->LOGGER->debug("Could not identify opposite gender: ".$gender);

            return "undefined";
        }
    }

    /* end helper method */

    public function migrateBirth($person, $originCountry, $originTerritory = null, $originLocation = null, $birthCountry = null, $birthLocation = null, $birthDate = null, $birthTerritory = null, $comment = null, $provenDate = null) {
        //insert into new data
        $newBirth = new Birth();

        $newBirth->setOriginCountry($this->getCountry($originCountry));
        $newBirth->setOriginTerritory($this->getTerritory($originTerritory, $originLocation));
        $newBirth->setOriginLocation($this->getLocation($originLocation));
        $newBirth->setBirthCountry($this->getCountry($birthCountry));
        $newBirth->setBirthLocation($this->getLocation($birthLocation));
        $newBirth->setBirthTerritory($this->getTerritory($birthTerritory, $birthLocation));
        $newBirth->setComment($this->normalize($comment));
        $newBirth->setProvenDate($this->getDate($provenDate));

        $newBirth->setBirthDate($this->getDate($birthDate));

        $this->getDBManager()->persist($newBirth);
        $person->setBirth($newBirth);
        $this->getDBManager()->flush();
    }

    public function migrateBaptism($person, $baptismDate, $baptismLocation = null) {
        //insert into new data
        $newBaptism = new Baptism();

        $newBaptism->setBaptismLocation($this->getLocation($baptismLocation));
        $newBaptism->setBaptismDate($this->getDate($baptismDate));

        $this->getDBManager()->persist($newBaptism);
        $person->setBaptism($newBaptism);
        $this->getDBManager()->flush();
    }

    public function migrateCountry($name, $comment = null) {
        //insert into new data
        return $this->getCountry($name, $comment);
    }

    public function migrateDate($day, $month, $year, $weekday, $comment = null) {
        //insert into new data
        $newDate = new Date();

        $newDate->setDay($day);
        $newDate->setMonth($month);
        $newDate->setYear($year);
        $newDate->setWeekday($weekday);
        $newDate->setComment($this->normalize($comment));

        $this->getDBManager()->persist($newDate);
        $this->getDBManager()->flush();

        return $newDate->getId();
    }

    public function migrateDeath($person, $deathLocation, $deathDate, $deathCountry = null, $causeOfDeath = null, $territoryOfDeath = null, $graveyard = null, $funeralLocation = null, $funeralDate = null, $comment = null) {
        //insert into new data
        $newDeath = new Death();

        $newDeath->setDeathLocation($this->getLocation($deathLocation));
        $newDeath->setDeathCountry($this->getCountry($deathCountry));
        $newDeath->setCauseOfDeath($this->normalize($causeOfDeath));
        $newDeath->setTerritoryOfDeath($this->getTerritory($territoryOfDeath, $deathLocation));
        $newDeath->setGraveyard($this->normalize($graveyard));
        $newDeath->setFuneralLocation($this->getLocation($funeralLocation));
        $newDeath->setComment($this->normalize($comment));
        $newDeath->setDeathDate($this->getDate($deathDate));
        $newDeath->setFuneralDate($this->getDate($funeralDate));

        $this->getDBManager()->persist($newDeath);
        $person->setDeath($newDeath);
        $this->getDBManager()->flush();
    }

    public function migrateEducation($person, $educationOrder, $label, $country = null, $territory = null, $location = null, $fromDate = null, $toDate = null, $provenDate = null, $graduationLabel = null, $graduationDate = null, $graduationLocation = null, $comment = null) {
        //insert into new data
        $newEducation = new Education();

        $newEducation->setPerson($person);
        $newEducation->setEducationOrder($educationOrder);
        $newEducation->setLabel($this->normalize($label));
        $newEducation->setCountry($this->getCountry($country));
        $newEducation->setTerritory($this->getTerritory($territory, $location));
        $newEducation->setLocation($this->getLocation($location));
        $newEducation->setFromDate($this->getDate($fromDate));
        $newEducation->setToDate($this->getDate($toDate));
        $newEducation->setProvenDate($this->getDate($provenDate));
        $newEducation->setGraduationLabel($this->normalize($graduationLabel));
        $newEducation->setGraduationDate($this->getDate($graduationDate));
        $newEducation->setGraduationLocation($this->getLocation($graduationLocation));
        $newEducation->setComment($this->normalize($comment));

        $this->getDBManager()->persist($newEducation);
        $this->getDBManager()->flush();
    }

    public function migrateHonour($person, $honourOrder, $label, $country = null, $territory = null, $location = null, $fromDate = null, $toDate = null, $provenDate = null, $comment = null) {
        //insert into new data
        $newHonour = new Honour();

        $newHonour->setPerson($person);
        $newHonour->setHonourOrder($honourOrder);
        $newHonour->setLabel($this->normalize($label));
        $newHonour->setCountry($this->getCountry($country));
        $newHonour->setTerritory($this->getTerritory($territory, $location));
        $newHonour->setLocation($this->getLocation($location));
        $newHonour->setFromDate($this->getDate($fromDate));
        $newHonour->setToDate($this->getDate($toDate));
        $newHonour->setProvenDate($this->getDate($provenDate));
        $newHonour->setComment($this->normalize($comment));

        $this->getDBManager()->persist($newHonour);
        $this->getDBManager()->flush();
    }

    public function migrateIsGrandparent($grandchild, $grandparent, $paternal, $comment = null) {
        $this->LOGGER->info("Adding grandchildgrandParentRelation with... grandChild: '" . $grandchild . "' grandParent: '" . $grandparent . "'");
        if (!$this->grandparentChildRelationAlreadyExists($grandchild, $grandparent)) {

            //insert into new data
            $newIsGrandparent = new IsGrandparent();

            $newIsGrandparent->setGrandChildID($grandchild->getId());
            $newIsGrandparent->setGrandParentID($grandparent->getId());
            $newIsGrandparent->setIsPaternal($paternal);
            $newIsGrandparent->setComment($this->normalize($comment));

            $this->getDBManager()->persist($newIsGrandparent);
            $this->getDBManager()->flush();
        }
    }

    public function migrateIsParent($child, $parent, $comment = null) {
        $this->LOGGER->info("Adding childParentRelation with... Child: '" . $child . "' Parent: '" . $parent . "'");
        if (!$this->parentChildRelationAlreadyExists($child, $parent)) {
            //insert into new data
            $newIsParent = new IsParent();

            $newIsParent->setChildID($child->getId());
            $newIsParent->setParentID($parent->getId());
            $newIsParent->setComment($this->normalize($comment));

            $this->getDBManager()->persist($newIsParent);
            $this->getDBManager()->flush();
        }
        
         $this->LOGGER->info("Finished adding childParentRelation with... Child: '" . $child . "' Parent: '" . $parent . "'");
    }

    public function migrateIsParentInLaw($childInLaw, $parentInLaw, $comment = null) {
        $this->LOGGER->info("Adding childParentInLawRelation with... Child: '" . $childInLaw . "' Parent: '" . $parentInLaw . "'");
        if (!$this->parentChildInLawRelationAlreadyExists($childInLaw, $parentInLaw)) {
            //insert into new data
            $newIsParentInLaw = new IsParentInLaw();

            $newIsParentInLaw->setChildInLawid($childInLaw->getId());
            $newIsParentInLaw->setParentInLawid($parentInLaw->getId());
            $newIsParentInLaw->setComment($this->normalize($comment));

            $this->getDBManager()->persist($newIsParentInLaw);
            $this->getDBManager()->flush();
        }
    }

    public function migrateIsSibling($siblingOne, $siblingTwo, $comment = null) {
        if ($this->isSiblingRelationAlreadyExists($siblingOne, $siblingTwo)) {
            $this->LOGGER->debug("Sibling relation already exists");
            return;
        }

        //insert into new data
        $newIsSibling = new IsSibling();


        $newIsSibling->setSiblingOneid($siblingOne->getId());
        $newIsSibling->setSiblingTwoid($siblingTwo->getId());
        $newIsSibling->setComment($this->normalize($comment));

        //$newIsSibling->;

        $this->getDBManager()->persist($newIsSibling);
        $this->getDBManager()->flush();

        return $newIsSibling->getId();
    }

    public function migrateJob($label, $comment = null) {
        //insert into new data
        return $this->getJob($label, $comment);
    }

    public function migrateLocation($name, $comment = null) {
        //insert into new data
        return $this->getLocation($name, $comment);
    }

    public function migrateNation($name, $comment = null) {
        //insert into new data
        return $this->getNation($name, $comment);
    }

    public function migratePartner($firstName, $patronym, $lastName, $gender, $nation = null, $comment = null) {
        $this->LOGGER->debug("Migrating Partner: ".$firstName." ".$lastName);
        //insert into new data
        $newPartner = new Partner();

        $newPartner->setFirstName($this->normalizeFirstname($firstName));
        $newPartner->setPatronym($this->normalizePatronym($patronym));
        $newPartner->setLastName($this->normalizeName($lastName));
        $genderResult = $this->extractGenderAndGenderComment($gender);
        $newPartner->setGender($genderResult[0]);
        $newPartner->setGenderComment($genderResult[1]);
        $newPartner->setNation($this->getNation($nation));

        $newPartner->setComment($this->normalize($comment));
        $newPartner->setJob(null);
        $newPartner->setJobClass(null);

        $this->getDBManager()->persist($newPartner);
        $this->getDBManager()->flush();
        
        $this->LOGGER->debug("Finished migrating Partner: ".$newPartner);
        return $newPartner;
    }

    public function migratePerson($oid, $firstName, $patronym, $lastName, $foreName, $birthName, $gender, $jobClass, $comment = null) {
        $this->LOGGER->debug("Migrating Person with oid '".$oid."' : ".$firstName." ".$lastName);
        //insert into new data
        $newPerson = new Person();

        $newPerson->setOid($oid);
        $newPerson->setFirstName($this->normalizeFirstname($firstName));
        $newPerson->setPatronym($this->normalizePatronym($patronym));
        $newPerson->setLastName($this->normalizeName($lastName));
        $newPerson->setForeName($this->normalizeName($foreName));
        $newPerson->setBirthName($this->normalizeName($birthName));
        $genderResult = $this->extractGenderAndGenderComment($gender);
        $newPerson->setGender($genderResult[0]);
        $newPerson->setGenderComment($genderResult[1]);
        $newPerson->setJobClass($this->getJobClass($jobClass));
        $newPerson->setComment($this->normalize($comment));

        $this->getDBManager()->persist($newPerson);
        $this->getDBManager()->flush();
        
        $this->LOGGER->debug("Finished migrating Person: ".$newPerson);
        return $newPerson;
    }

    public function migrateProperty($person, $propertyOrder, $label, $country = null, $territory = null, $location = null, $fromDate = null, $toDate = null, $provenDate = null, $comment = null) {
        //insert into new data
        $newProperty = new Property();

        $newProperty->setPerson($person);
        $newProperty->setPropertyOrder($propertyOrder);
        $newProperty->setLabel($this->normalize($label));
        $newProperty->setCountry($this->getCountry($country));
        $newProperty->setTerritory($this->getTerritory($territory, $location));
        $newProperty->setLocation($this->getLocation($location));
        $newProperty->setFromDate($this->getDate($fromDate));
        $newProperty->setToDate($this->getDate($toDate));
        $newProperty->setProvenDate($this->getDate($provenDate));
        $newProperty->setComment($this->normalize($comment));

        $this->getDBManager()->persist($newProperty);
        $this->getDBManager()->flush();
    }

    public function migrateRank($person, $rankOrder, $label, $class = null, $country = null, $territory = null, $location = null, $fromDate = null, $toDate = null, $provenDate = null, $comment = null) {
        //insert into new data
        $newRank = new Rank();

        $newRank->setPerson($person);
        $newRank->setRankOrder($rankOrder);
        $newRank->setLabel($this->normalize($label));
        $newRank->setClass($this->normalize($class));
        $newRank->setCountry($this->getCountry($country));
        $newRank->setTerritory($this->getTerritory($territory, $location));
        $newRank->setLocation($this->getLocation($location));
        $newRank->setFromDate($this->getDate($fromDate));
        $newRank->setToDate($this->getDate($toDate));
        $newRank->setProvenDate($this->getDate($provenDate));
        $newRank->setComment($this->normalize($comment));

        $this->getDBManager()->persist($newRank);
        $this->getDBManager()->flush();
    }

    public function migrateRelative($firstName, $patronym, $lastName, $gender, $nation = null, $comment = null) {
        $this->LOGGER->debug("Migrating Relative: ".$firstName." ".$lastName);
        //insert into new data
        $newRelative = new Relative();

        $newRelative->setFirstName($this->normalizeFirstname($firstName));
        $newRelative->setPatronym($this->normalizePatronym($patronym));
        $newRelative->setLastName($this->normalizeName($lastName));
        $genderResult = $this->extractGenderAndGenderComment($gender);
        $newRelative->setGender($genderResult[0]);
        $newRelative->setGenderComment($genderResult[1]);
        $newRelative->setNation($this->getNation($nation));
        
        
        $newRelative->setComment($this->normalize($comment));

        $this->getDBManager()->persist($newRelative);
        $this->getDBManager()->flush();
        
        $this->LOGGER->debug("Finished migrating Relative: ".$newRelative);

        return $newRelative;
    }

    public function migrateReligion($person, $name, $religionOrder, $change_of_religion = null, $provenDate = null, $fromDate = null, $comment = null) {
        //insert into new data
        $newReligion = new Religion();

        $newReligion->setPerson($person);
        $newReligion->setName($this->normalize($name));
        $newReligion->setReligionOrder($religionOrder);
        $newReligion->setChangeOfReligion($this->normalize($change_of_religion));
        $newReligion->setComment($this->normalize($comment));

        $newReligion->setProvenDate($this->getDate($provenDate));
        $newReligion->setFromDate($this->getDate($fromDate));

        $this->getDBManager()->persist($newReligion);
        $this->getDBManager()->flush();
    }

    public function migrateResidence($person, $residenceOrder, $residenceCountry, $residenceTerritory = null, $residenceLocation = null) {
        //insert into new data
        $newResidence = new Residence();

        $newResidence->setPerson($person);
        $newResidence->setResidenceOrder($residenceOrder);
        $newResidence->setResidenceCountry($this->getCountry($residenceCountry));
        $newResidence->setResidenceTerritory($this->getTerritory($residenceTerritory, $residenceLocation));
        $newResidence->setResidenceLocation($this->getLocation($residenceLocation));


        $this->getDBManager()->persist($newResidence);
        $this->getDBManager()->flush();
    }

    public function migrateRoadOfLife($person, $roadOfLifeOrder, $originCountry = null, $originTerritory = null, $job = null, $country = null, $territory = null, $location = null, $fromDate = null, $toDate = null, $provenDate = null, $comment = null) {
        //insert into new data
        $newRoadOfLife = new RoadOfLife();

        $newRoadOfLife->setPerson($person);
        $newRoadOfLife->setRoadOfLifeOrder($roadOfLifeOrder);
        $newRoadOfLife->setOriginCountry($this->getCountry($originCountry));
        $newRoadOfLife->setOriginTerritory($this->getTerritory($originTerritory));
        $newRoadOfLife->setJob($this->getJob($job));
        $newRoadOfLife->setCountry($this->getCountry($country));
        $newRoadOfLife->setTerritory($this->getTerritory($territory, $location));
        $newRoadOfLife->setLocation($this->getLocation($location));
        $newRoadOfLife->setFromDate($this->getDate($fromDate));
        $newRoadOfLife->setToDate($this->getDate($toDate));
        $newRoadOfLife->setProvenDate($this->getDate($provenDate));
        $newRoadOfLife->setComment($this->normalize($comment));

        $this->getDBManager()->persist($newRoadOfLife);
        $this->getDBManager()->flush();
    }

    public function migrateSource($person, $sourceOrder, $label, $placeOfDiscovery = null, $remark = null, $comment = null) {
        //insert into new data
        $newSource = new Source();

        $newSource->setPerson($person);
        $newSource->setSourceOrder($sourceOrder);
        $newSource->setLabel($this->normalize($label));
        $newSource->setPlaceOfDiscovery($this->normalize($placeOfDiscovery));
        $newSource->setRemark($this->normalize($remark));
        $newSource->setComment($this->normalize($comment));

        $this->getDBManager()->persist($newSource);
        $this->getDBManager()->flush();
    }

    public function migrateStatus($person, $statusOrder, $label, $country = null, $territory = null, $location = null, $fromDate = null, $toDate = null, $provenDate = null, $comment = null) {
        //insert into new data
        $newStatus = new Status();

        $newStatus->setPerson($person);
        $newStatus->setStatusOrder($statusOrder);
        $newStatus->setLabel($this->normalize($label));
        $newStatus->setCountry($this->getCountry($country));
        $newStatus->setTerritory($this->getTerritory($territory, $location));
        $newStatus->setLocation($this->getLocation($location));
        $newStatus->setFromDate($this->getDate($fromDate));
        $newStatus->setToDate($this->getDate($toDate));
        $newStatus->setProvenDate($this->getDate($provenDate));
        $newStatus->setComment($this->normalize($comment));

        $this->getDBManager()->persist($newStatus);
        $this->getDBManager()->flush();
    }

    public function migrateTerritory($name, $comment = null) {
        //insert into new data
        return $this->getTerritory($name, null, $comment);
    }

    public function migrateWedding($weddingOrder, $personOne, $personTwo, $weddingDate = null, $weddingLocation = null, $weddingTerritory = null, $bannsDate = null, $breakupReason = null, $breakupDate = null, $marriageComment = null, $beforeAfter = null, $comment = null, $provenDate = null) {
        //create new wedding obj
        $newWedding = new Wedding();
        $husband = $personOne;
        $wife = $personTwo;

        if (($personTwo != null && $personTwo->getGender() == 1) || ($personOne != null && $personOne->getGender() == 2)) {
            //personTwo is husband, since he is male or personOne is female
            $husband = $personTwo;
            $wife = $personOne;
        }
        
        $this->LOGGER->info("Adding wedding between Husband: ". $husband. " and Wife: ".$wife. " at order: ".$weddingOrder);

        $newWedding->setHusbandId($husband != null ? $husband->getId() : null);
        $newWedding->setWifeId($wife != null ? $wife->getId() : null);

        $newWedding->setWeddingOrder($weddingOrder);
        $newWedding->setWeddingDate($this->getDate($weddingDate));
        $newWedding->setWeddingLocation($this->getLocation($weddingLocation));
        $newWedding->setWeddingTerritory($this->getTerritory($weddingTerritory, $weddingLocation));
        $newWedding->setBannsDate($this->getDate($bannsDate));
        $newWedding->setBreakupReason($this->normalize($breakupReason));
        $newWedding->setBreakupDate($this->getDate($breakupDate));
        $newWedding->setMarriageComment($this->normalize($marriageComment));
        $newWedding->setBeforeAfter($beforeAfter);
        $newWedding->setComment($this->normalize($comment));
        $newWedding->setProvenDate($this->getDate($provenDate));

        $existingWedding = $this->checkIfWeddingAlreadyExists($weddingOrder, $personOne, $personTwo);

        if (is_null($existingWedding)) {
            //persist new wedding obj
            $this->getDBManager()->persist($newWedding);
            $this->getDBManager()->flush();
        } else {
            //merge with existing wedding
            $this->LOGGER->info("Merging new wedding with existing wedding.");

            $existingWedding = $this->get("person_merging.service")->createMergedWeddingObj($existingWedding, $newWedding);

            $this->getDBManager()->persist($existingWedding);
            $this->getDBManager()->flush();
        }
    }

    public function checkIfWeddingAlreadyExists($weddingOrder, $personOne, $personTwo) {
        $husband = $personOne;
        $wife = $personTwo;

        if (($personTwo != null && $personTwo->getGender() == 1) || ($personOne != null && $personOne->getGender() == 2)) {
            //personTwo is husband, since he is male or personOne is female
            $husband = $personTwo;
            $wife = $personOne;
        }

        $this->LOGGER->info("Searching for wedding with... Husband: '" . $husband . "' Wife: '" . $wife . " and WeddingOrder: " . $weddingOrder);

        $wedding = $this->getDBManager()->getRepository('NewBundle:Wedding')
                ->findOneBy(array('weddingOrder' => $weddingOrder,
            'husbandId' => $husband != null ? $husband->getId() : null,
            'wifeId' => $wife != null ? $wife->getId() : null
        ));

        if (is_null($wedding)) {
            $this->LOGGER->debug("Didn't find existing wedding.");
        } else {
            $this->LOGGER->debug("Found existing wedding.");
        }
        return $wedding;
    }

    public function migrateWork($person, $label, $works_order, $country = null, $location = null, $fromDate = null, $toDate = null, $territory = null, $provenDate = null, $comment = null) {
        //insert into new data
        $newWorks = new Works();

        $newWorks->setPerson($person);
        $newWorks->setLabel($this->normalize($label));
        $newWorks->setWorksOrder($works_order);
        $newWorks->setCountry($this->getCountry($country));
        $newWorks->setLocation($this->getLocation($location));
        $newWorks->setFromDate($this->getDate($fromDate));
        $newWorks->setToDate($this->getDate($toDate));
        $newWorks->setTerritory($this->getTerritory($territory, $location));
        $newWorks->setProvenDate($this->getDate($provenDate));
        $newWorks->setComment($this->normalize($comment));

        $this->getDBManager()->persist($newWorks);
        $this->getDBManager()->flush();
    }

    public function saveObject($object) {
        $this->LOGGER->info("persisting and flushing the person to the new db: " . $object);
        $this->getDBManager()->merge($object);
        $this->getDBManager()->flush();
    }

    public function getNewPersonForOid($OID) {
        $this->LOGGER->debug("Trying to load new Person with OID: ".$OID);
        return $this->getDBManager()->getRepository('NewBundle:Person')->findOneByOid($OID);
    }

    /* Migration helper methods */

    public function parentChildRelationAlreadyExists($child, $parent) {
        $this->LOGGER->info("Searching for childParentRelation with... Child: '" . $child . "' Parent: '" . $parent);

        $relation = $this->getDBManager()->getRepository('NewBundle:IsParent')
                ->findOneBy(array('childID' => $child->getId(),
            'parentID' => $parent->getId()
        ));

        if (is_null($relation)) {
            $this->LOGGER->debug("Didn't find existing child <-> parent Relation");
            return false;
        } else {
            $this->LOGGER->debug("Found existing child <-> parent Relation");
            return true;
        }
    }

    public function parentChildInLawRelationAlreadyExists($childInLaw, $parentInLaw) {
        $this->LOGGER->info("Searching for childParentInLawRelation with... Child: '" . $childInLaw . "' Parent: '" . $parentInLaw);

        $relation = $this->getDBManager()->getRepository('NewBundle:IsParentInLaw')
                ->findOneBy(array('childInLawid' => $childInLaw->getId(),
            'parentInLawid' => $parentInLaw->getId()
        ));

        if (is_null($relation)) {
            $this->LOGGER->debug("Didn't find existing child <-> parent Relation");
            return false;
        } else {
            $this->LOGGER->debug("Found existing child <-> parent Relation");
            return true;
        }
    }

    public function grandparentChildRelationAlreadyExists($grandchild, $grandparent) {
        $this->LOGGER->info("Searching for grandchildGrandParentRelation with... GrandChild: '" . $grandchild . "' GrandParent: '" . $grandparent);

        $relation = $this->getDBManager()->getRepository('NewBundle:IsGrandparent')
                ->findOneBy(array('grandChildID' => $grandchild->getId(),
            'grandParentID' => $grandparent->getId()
        ));

        if (is_null($relation)) {
            $this->LOGGER->debug("Didn't find existing grandchild <-> grandparent Relation");
            return false;
        } else {
            $this->LOGGER->debug("Found existing grandchild <-> grandparent Relation");
            return true;
        }
    }

    public function isSiblingRelationAlreadyExists($siblingOne, $siblingTwo) {
        $this->LOGGER->info("Checking if SiblingRelationShip already exists between " . $siblingOne . " and " . $siblingTwo);

        $queryBuilder = $this->getDBManager()->getRepository('NewBundle:IsSibling')->createQueryBuilder('s');
        $siblingEntries = $queryBuilder
                ->where('(s.siblingOneid = :idOne AND s.siblingTwoid = :idTwo) '
                        . 'OR (s.siblingOneid = :idTwo AND s.siblingTwoid = :idOne)')
                ->setParameter('idOne', $siblingOne->getId())
                ->setParameter('idTwo', $siblingTwo->getId())
                ->getQuery()
                ->getResult();

        return count($siblingEntries) != 0;
    }

    // to check if unknown returns wrong marriage partners...
    public function getMarriagePartner($weddingOrder, $person) {
        if ($person->getGender() == 2) {
            //given person is female
            return $this->findHusband($weddingOrder, $person);
        } else if ($person->getGender() == 1) {
            //given person is male
            return $this->findWife($weddingOrder, $person);
        } else {
            //gender unknown, higher propability that he is a man (since there are more mens in the database)
            $partner = $this->findWife($weddingOrder, $person);

            if (!is_null($partner)) {
                return $partner;
            }

            return $this->findHusband($weddingOrder, $person);
        }
    }

    private function findHusband($weddingOrder, $wife) {
        //given person is female
        $wedding = $this->getDBManager()->getRepository('NewBundle:Wedding')
                ->findOneBy(array('weddingOrder' => $weddingOrder,
            'wifeId' => $wife->getId()
        ));

        if (is_null($wedding)) {
            return null;
        }

        return $this->loadPerson($wedding->getHusbandId());
    }

    private function findWife($weddingOrder, $husband) {
        //given person is male
        $wedding = $this->getDBManager()->getRepository('NewBundle:Wedding')
                ->findOneBy(array('weddingOrder' => $weddingOrder,
            'husbandId' => $husband->getId()
        ));

        if (is_null($wedding)) {
            return null;
        }

        return $this->loadPerson($wedding->getWifeId());
    }

    private function loadPerson($id) {
        $person = $this->getDBManager()->getRepository('NewBundle:Person')->findOneById($id);

        if (is_null($person)) {
            $person = $this->getDBManager()->getRepository('NewBundle:Relative')->findOneById($id);
        }

        if (is_null($person)) {
            $person = $this->getDBManager()->getRepository('NewBundle:Partner')->findOneById($id);
        }

        if (is_null($person)) {
            //throw exception
        }

        return $person;
    }

    public function clearProxyCache() {
        $this->getDBManager()->clear();
    }

    public function flush(){
        $this->LOGGER->info("Explizit flush called!");
        $this->getDBManager()->flush();
    }
    
    public function remove($obj){
        $this->LOGGER->info("Explizit remove called: ".$obj);
        $this->getDBManager()->remove($obj);
    }
    
    public function detach($obj){
        $this->LOGGER->info("Explizit detach called: ".$obj);
        if($this->getDBManager()->contains($obj)){
            $this->LOGGER->debug("Object IS managed by this entitymanager");
            $this->getDBManager()->detach($obj);
        } else {
            $this->LOGGER->debug("Object IS NOT managed by this entitymanager");
        }
    }
}
