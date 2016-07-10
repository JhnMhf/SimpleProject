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
    const DATE_REGEX = "/^(\D*)(0|0?[1-9]|[12][0-9]|3[01])[\.\-](0|0?[1-9]|1[012])[\.\-](\d{4})(.*)$/";
    const PERSON_CLASS = "UR\DB\NewBundle\Entity\Person";
    const RELATIVE_CLASS = "UR\DB\NewBundle\Entity\Relative";
    const PARTNER_CLASS = "UR\DB\NewBundle\Entity\Partner";

    private $LOGGER;
    private $container;
    private $newDBManager;
    private $normalizationService;
    private $locationToTerritoryService;

    //@TODO: Check if abbrevations are checked at all necessary places!
    public function __construct($container) {
        $this->container = $container;
        $this->newDBManager = $this->get('doctrine')->getManager('new');
        $this->LOGGER = $this->get('monolog.logger.migrateNew');
        $this->normalizationService = $this->get("normalization.service");
        $this->locationToTerritoryService = $this->get("locationToTerritory.service");
    }

    private function get($identifier) {
        return $this->container->get($identifier);
    }

    /* helper method */

    private function normalize($string) {
        return $this->normalizationService->writeOutAbbreviations($string);
    }

    public function getCountry($countryName, $comment = null) {

        if ($countryName == "" || $countryName == null) {
            return null;
        }

        $countryName = $this->normalize($countryName);

        // check if country exists
        if ($comment == null || $comment == "") {
            $result = $this->tryExtractingNameAndCommentFromString($countryName);
            $countryName = $result[0];
            $comment = $result[1];

            $country = $this->newDBManager->getRepository('NewBundle:Country')->findOneByName($countryName);

            if ($country != null) {
                return $country;
            }
        } else {
            $country = $this->newDBManager->getRepository('NewBundle:Country')->findOneBy(array('name' => $countryName, 'comment' => $comment));

            if ($country != null) {
                return $country;
            }
        }

        // if it does not exist, create it and return the new value
        $newCountry = new Country();

        $newCountry->setName($countryName);
        $newCountry->setComment($this->normalize($comment));

        $this->newDBManager->persist($newCountry);
        $this->newDBManager->flush();

        return $newCountry;
    }

    public function getTerritory($territoryName, $locationName = null, $comment = null) {

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

        $territoryName = $this->normalize($territoryName);

        // check if territory exists
        if ($comment == null || $comment == "") {
            $result = $this->tryExtractingNameAndCommentFromString($territoryName);
            $territoryName = $result[0];
            $comment = $result[1];

            $territory = $this->newDBManager->getRepository('NewBundle:Territory')->findOneByName($territoryName);

            if ($territory != null) {
                return $territory;
            }
        } else {
            $territory = $this->newDBManager->getRepository('NewBundle:Territory')->findOneBy(array('name' => $territoryName, 'comment' => $comment));

            if ($territory != null) {
                return $territory;
            }
        }

        // if it does not exist, create it and return the new value
        $newTerritory = new Territory();

        $newTerritory->setName($territoryName);
        $newTerritory->setComment($this->normalize($comment));

        $this->newDBManager->persist($newTerritory);
        $this->newDBManager->flush();

        return $newTerritory;
    }

    public function getLocation($locationName, $comment = null) {
        if ($locationName == "" || $locationName == null) {
            return null;
        }

        $locationName = $this->normalize($locationName);

        // check if location exists
        if ($comment == null || $comment == "") {
            $result = $this->tryExtractingNameAndCommentFromString($locationName);
            $locationName = $result[0];
            $comment = $result[1];

            $location = $this->newDBManager->getRepository('NewBundle:Location')->findOneByName($locationName);

            if ($location != null) {
                return $location;
            }
        } else {
            $location = $this->newDBManager->getRepository('NewBundle:Location')->findOneBy(array('name' => $locationName, 'comment' => $comment));

            if ($location != null) {
                return $location;
            }
        }

        // if it does not exist, create it and return the new value
        $newLocation = new Location();

        $newLocation->setName($locationName);
        $newLocation->setComment($this->normalize($comment));

        $this->newDBManager->persist($newLocation);
        $this->newDBManager->flush();

        return $newLocation;
    }

    public function getNation($nationName, $comment = null) {
        if ($nationName == "" || $nationName == null) {
            return null;
        }

        $nationName = $this->normalize($nationName);

        // check if location exists
        if ($comment == null || $comment == "") {
            $result = $this->tryExtractingNameAndCommentFromString($nationName);
            $nationName = $result[0];
            $comment = $result[1];

            $nation = $this->newDBManager->getRepository('NewBundle:Nation')->findOneByName($nationName);

            if ($nation != null) {
                return $nation;
            }
        } else {
            $nation = $this->newDBManager->getRepository('NewBundle:Nation')->findOneBy(array('name' => $nationName, 'comment' => $comment));

            if ($nation != null) {
                return $nation;
            }
        }

        // if it does not exist, create it and return the new value
        $newNation = new Nation();

        $newNation->setName($nationName);
        $newNation->setComment($this->normalize($comment));

        $this->newDBManager->persist($newNation);
        $this->newDBManager->flush();

        return $newNation;
    }

    //Perhaps it belongs to the jobclass and not the job?
    public function getJob($jobLabel, $comment = null) {
        if ($jobLabel == "" || $jobLabel == null) {
            return null;
        }

        $jobLabel = $this->normalize($jobLabel);

        // check if job exists
        if ($comment == null || $comment == "") {
            $result = $this->tryExtractingNameAndCommentFromString($jobLabel);
            $jobLabel = $result[0];
            $comment = $result[1];

            $job = $this->newDBManager->getRepository('NewBundle:Job')->findOneByLabel($jobLabel);

            if ($job != null) {
                return $job;
            }
        } else {


            $job = $this->newDBManager->getRepository('NewBundle:Job')->findOneBy(array('label' => $jobLabel, 'comment' => $comment));

            if ($job != null) {
                return $job;
            }
        }

        // if it does not exist, create it and return the new value
        $newJob = new Job();

        $newJob->setLabel($jobLabel);
        $newJob->setComment($this->normalize($comment));

        $this->newDBManager->persist($newJob);
        $this->newDBManager->flush();

        return $newJob;
    }

    // can get multiple jobclasses?
    public function getJobClass($jobClassLabel, $comment = null) {
        if ($jobClassLabel == "" || $jobClassLabel == null) {
            return null;
        }

        $jobClassLabel = $this->normalize($jobClassLabel);

        // check if jobClass exists
        if ($comment == null || $comment == "") {
            $result = $this->tryExtractingNameAndCommentFromString($jobClassLabel);
            $jobClassLabel = $result[0];
            $comment = $result[1];

            $jobClass = $this->newDBManager->getRepository('NewBundle:JobClass')->findOneByLabel($jobClassLabel);

            if ($jobClass != null) {
                return $jobClass;
            }
        } else {
            $jobClass = $this->newDBManager->getRepository('NewBundle:JobClass')->findOneBy(array('label' => $jobClassLabel, 'comment' => $comment));

            if ($jobClass != null) {
                return $jobClass;
            }
        }

        // if it does not exist, create it and return the new value
        $newJobClass = new JobClass();

        $newJobClass->setLabel($jobClassLabel);

        $this->newDBManager->persist($newJobClass);
        $this->newDBManager->flush();

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
            $this->LOGGER->debug("Found -Anmerkung: in" . $string);
            $result[0] = substr($string, 0, $containsAnmerkung);
            $result[1] = substr($string, $containsAnmerkung);
        } else if ($containsImOriginal !== false) {
            $this->LOGGER->debug("Found -im Original in" . $string);
            $result[0] = substr($string, 0, $containsImOriginal);
            $result[1] = substr($string, $containsImOriginal);
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
        $this->newDBManager->flush();

        return $newDatesArray;
    }

    private function createStringFromIdArray($idArray) {

        $uniqueArray = array_unique($idArray);

        return implode(",", $uniqueArray);
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
        if ($inner) {
            $this->LOGGER->debug("Probably searching for a date range.");
        }

        $dateString = trim($string);

        preg_match(self::DATE_REGEX, $dateString, $date);
        $newDate = new Date();
        if (count($date) > 0) {
            $secondDate = null;

            if (strpos($date[1], "- im Original")) {
                $newDate->setComment($date[0]);
                return $newDate;
            }

            //found date, do the right things...
            if ($date[2] != "0") {
                $newDate->setDay($date[2]);
            }

            if ($date[3] != "0") {
                $newDate->setMonth($date[3]);
            }

            if ($date[4] != "0") {
                $newDate->setYear($date[4]);
            }

            $commentString = "";

            if ($date[1] != "") {
                if ($date[1] == "-" && !$inner) {
                    $newDate->setBeforeDate(1);
                } else {
                    $commentString .= trim($date[1]);
                }
            }

            if ($date[5] != "") {
                if ($date[5] == "-" && !$inner) {
                    $newDate->setAfterDate(1);
                } else if (substr($date[5], 0, 1) == "-") {
                    //persist first date before searching for second date!
                    $this->newDBManager->persist($newDate);
                    //check for daterange
                    $this->LOGGER->debug("Check for a daterange");
                    $secondDate = $this->extractDateObjFromString($date[5], true);

                    if ($secondDate == null) {
                        if (!$inner) {
                            $newDate->setAfterDate(1);
                        }

                        $commentString .= trim(substr($date[5], 1));
                    }
                } else {
                    $commentString .= trim($date[5]);
                }
            }

            if ($commentString != "") {
                $newDate->setComment($this->normalize($commentString));
            }

            $this->newDBManager->persist($newDate);

            if ($secondDate != null) {
                $this->newDBManager->persist($secondDate);
                return new DateRange($newDate, $secondDate);
            }

            return $newDate;
        } else {
            $newDate->setComment("ERROR: " . $dateString);
            $this->newDBManager->persist($newDate);
            return $newDate;
        }
    }

    //@TODO: Handle other entries: 
    //SELECT geschlecht,count(*) FROM `kind` GROUP BY geschlecht ORDER BY count(*) DESC
    public function extractGenderAndGenderComment($genderString){
        $result = $this->tryExtractingNameAndCommentFromString($genderString);
        
        $result[0] = $this->getGender($result[0]);
        
        return $result;
    }
    
    public function getGender($gender) {
        //undefined = 0, male = 1, female = 2

        if (is_numeric($gender)) {
            if ($gender == 1) {
                return 1;
            } else if ($gender == 2) {
                return 2;
            }

            return 0;
        } else {
            if ($gender == "männlich") {
                return 1;
            } else if ($gender == "weiblich") {
                return 2;
            }

            return 0;
        }
    }

    public function getOppositeGender($gender) {
        //undefined = 0, male = 1, female = 2

        if (is_numeric($gender)) {
            if ($gender == 1) {
                return 2;
            } else if ($gender == 2) {
                return 1;
            }

            return 0;
        } else {
            if ($gender == "männlich") {
                return "weiblich";
            } else if ($gender == "weiblich") {
                return "männlich";
            }

            return "undefined";
        }
    }

    /* end helper method */

    public function migrateBirth($person, $originCountry, $originTerritory = null, $originLocation = null, $birthCountry = null, $birthLocation = null, $birthDate = null, $birthTerritory = null, $comment = null) {
        //insert into new data
        $newBirth = new Birth();

        $newBirth->setOriginCountry($this->getCountry($originCountry));
        $newBirth->setOriginTerritory($this->getTerritory($originTerritory, $originLocation));
        $newBirth->setOriginLocation($this->getLocation($originLocation));
        $newBirth->setBirthCountry($this->getCountry($birthCountry));
        $newBirth->setBirthLocation($this->getLocation($birthLocation));
        $newBirth->setBirthTerritory($this->getTerritory($birthTerritory, $birthLocation));
        $newBirth->setComment($this->normalize($comment));

        $newBirth->setBirthDate($this->getDate($birthDate));

        $this->newDBManager->persist($newBirth);

        $person->setBirth($newBirth);
        $this->newDBManager->flush();
    }

    public function migrateBaptism($person, $baptismDate, $baptismLocation = null) {
        //insert into new data
        $newBaptism = new Baptism();

        $newBaptism->setBaptismLocation($this->getLocation($baptismLocation));
        $newBaptism->setBaptismDate($this->getDate($baptismDate));

        $this->newDBManager->persist($newBaptism);
        $person->setBaptism($newBaptism);
        $this->newDBManager->flush();
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

        $this->newDBManager->persist($newDate);
        $this->newDBManager->flush();

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

        $this->newDBManager->persist($newDeath);
        $person->setDeath($newDeath);
        $this->newDBManager->flush();
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

        $this->newDBManager->persist($newEducation);
        $this->newDBManager->flush();
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

        $this->newDBManager->persist($newHonour);
        $this->newDBManager->flush();
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

            $this->newDBManager->persist($newIsGrandparent);
            $this->newDBManager->flush();
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

            $this->newDBManager->persist($newIsParent);
            $this->newDBManager->flush();
        }
    }

    public function migrateIsParentInLaw($childInLaw, $parentInLaw, $comment = null) {
        $this->LOGGER->info("Adding childParentInLawRelation with... Child: '" . $childInLaw . "' Parent: '" . $parentInLaw . "'");
        if (!$this->parentChildInLawRelationAlreadyExists($childInLaw, $parentInLaw)) {
            //insert into new data
            $newIsParentInLaw = new IsParentInLaw();

            $newIsParentInLaw->setChildInLawid($childInLaw->getId());
            $newIsParentInLaw->setParentInLawid($parentInLaw->getId());
            $newIsParentInLaw->setComment($this->normalize($comment));

            $this->newDBManager->persist($newIsParentInLaw);
            $this->newDBManager->flush();
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

        $this->newDBManager->persist($newIsSibling);
        $this->newDBManager->flush();

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
        //insert into new data
        $newPartner = new Partner();

        $newPartner->setFirstName($this->normalize($firstName));
        $newPartner->setPatronym($this->normalize($patronym));
        $newPartner->setLastName($this->normalize($lastName));
        $genderResult = $this->extractGenderAndGenderComment($gender);
        $newPartner->setGender($genderResult[0]);
        $newPartner->setGenderComment($genderResult[1]);
        $newPartner->setOriginalNation($this->getNation($nation));

        $newPartner->setComment($this->normalize($comment));
        $newPartner->setJob(null);
        $newPartner->setJobClass(null);

        $this->newDBManager->persist($newPartner);
        $this->newDBManager->flush();

        return $newPartner;
    }

    //add additional stuff?
    //born_in_marriage (from mother/ father?)
    //weddingID
    public function migratePerson($oid, $firstName, $patronym, $lastName, $foreName, $birthName, $gender, $jobClass, $comment = null) {
        //insert into new data
        $newPerson = new Person();

        $newPerson->setOid($oid);
        $newPerson->setFirstName($this->normalize($firstName));
        $newPerson->setPatronym($this->normalize($patronym));
        $newPerson->setLastName($this->normalize($lastName));
        $newPerson->setForeName($this->normalize($foreName));
        $newPerson->setBirthName($this->normalize($birthName));
        $genderResult = $this->extractGenderAndGenderComment($gender);
        $newPerson->setGender($genderResult[0]);
        $newPerson->setGenderComment($genderResult[1]);
        $newPerson->setJobClass($this->getJobClass($jobClass));
        $newPerson->setComment($this->normalize($comment));

        $this->newDBManager->persist($newPerson);
        $this->newDBManager->flush();

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

        $this->newDBManager->persist($newProperty);
        $this->newDBManager->flush();
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

        $this->newDBManager->persist($newRank);
        $this->newDBManager->flush();
    }

    public function migrateRelative($firstName, $patronym, $lastName, $gender, $nation = null, $comment = null) {
        //insert into new data
        $newRelative = new Relative();

        $newRelative->setFirstName($this->normalize($firstName));
        $newRelative->setPatronym($this->normalize($patronym));
        $newRelative->setLastName($this->normalize($lastName));
        $genderResult = $this->extractGenderAndGenderComment($gender);
        $newRelative->setGender($genderResult[0]);
        $newRelative->setGenderComment($genderResult[1]);
        $newRelative->setNation($this->getNation($nation));

        $newRelative->setComment($this->normalize($comment));

        $this->newDBManager->persist($newRelative);
        $this->newDBManager->flush();

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

        $this->newDBManager->persist($newReligion);
        $this->newDBManager->flush();
    }

    public function migrateResidence($person, $residenceOrder, $residenceCountry, $residenceTerritory = null, $residenceLocation = null) {
        //insert into new data
        $newResidence = new Residence();

        $newResidence->setPerson($person);
        $newResidence->setResidenceOrder($residenceOrder);
        $newResidence->setResidenceCountry($this->getCountry($residenceCountry));
        $newResidence->setResidenceTerritory($this->getTerritory($residenceTerritory, $residenceLocation));
        $newResidence->setResidenceLocation($this->getLocation($residenceLocation));


        $this->newDBManager->persist($newResidence);
        $this->newDBManager->flush();
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

        $this->newDBManager->persist($newRoadOfLife);
        $this->newDBManager->flush();
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

        $this->newDBManager->persist($newSource);
        $this->newDBManager->flush();
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

        $this->newDBManager->persist($newStatus);
        $this->newDBManager->flush();
    }

    public function migrateTerritory($name, $comment = null) {
        //insert into new data
        return $this->getTerritory($name, null, $comment);
    }

    public function migrateWedding($weddingOrder, $personOne, $personTwo, $weddingDate = null, $weddingLocation = null, $weddingTerritory = null, $bannsDate = null, $breakupReason = null, $breakupDate = null, $marriageComment = null, $beforeAfter = null, $comment = null) {
        //create new wedding obj
        $newWedding = new Wedding();
        $husband = $personOne;
        $wife = $personTwo;

        if (($personTwo != null && $personTwo->getGender() == 1) || ($personOne != null && $personOne->getGender() == 2)) {
            //personTwo is husband, since he is male or personOne is female
            $husband = $personTwo;
            $wife = $personOne;
        }

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

        $existingWedding = $this->checkIfWeddingAlreadyExists($weddingOrder, $personOne, $personTwo);

        if (is_null($existingWedding)) {
            //persist new wedding obj
            $this->newDBManager->persist($newWedding);
            $this->newDBManager->flush();
        } else {
            //merge with existing wedding
            $this->LOGGER->info("Merging new wedding with existing wedding.");

            $existingWedding = $this->get("person_merging.service")->createMergedWeddingObj($existingWedding, $newWedding);

            $this->newDBManager->persist($existingWedding);
            $this->newDBManager->flush();
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

        $wedding = $this->newDBManager->getRepository('NewBundle:Wedding')
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

        $this->newDBManager->persist($newWorks);
        $this->newDBManager->flush();
    }

    public function savePerson($person) {
        $this->LOGGER->info("persisting and flushing the person to the new db: " . $person);
        $this->newDBManager->persist($person);
        $this->newDBManager->flush();
    }

    public function getNewPersonForOid($OID) {
        return $this->newDBManager->getRepository('NewBundle:Person')->findOneByOid($OID);
    }

    /* Migration helper methods */

    public function parentChildRelationAlreadyExists($child, $parent) {
        $this->LOGGER->info("Searching for childParentRelation with... Child: '" . $child . "' Parent: '" . $parent);

        $relation = $this->newDBManager->getRepository('NewBundle:IsParent')
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

        $relation = $this->newDBManager->getRepository('NewBundle:IsParentInLaw')
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

        $relation = $this->newDBManager->getRepository('NewBundle:IsGrandparent')
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

        $queryBuilder = $this->newDBManager->getRepository('NewBundle:IsSibling')->createQueryBuilder('s');
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
        $wedding = $this->newDBManager->getRepository('NewBundle:Wedding')
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
        $wedding = $this->newDBManager->getRepository('NewBundle:Wedding')
                ->findOneBy(array('weddingOrder' => $weddingOrder,
            'husbandId' => $husband->getId()
        ));

        if (is_null($wedding)) {
            return null;
        }

        return $this->loadPerson($wedding->getWifeId());
    }

    private function loadPerson($id) {
        $person = $this->newDBManager->getRepository('NewBundle:Person')->findOneById($id);

        if (is_null($person)) {
            $person = $this->newDBManager->getRepository('NewBundle:Relative')->findOneById($id);
        }

        if (is_null($person)) {
            $person = $this->newDBManager->getRepository('NewBundle:Partner')->findOneById($id);
        }

        if (is_null($person)) {
            //throw exception
        }

        return $person;
    }

    public function clearProxyCache() {
        $this->newDBManager->clear();
    }

}
