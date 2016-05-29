<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UR\DB\NewBundle\Utils;

/**
 * Description of PersonComparer
 *
 * @author johanna
 */
class PersonComparer {

    const GENDER_UNKNOWN = 0;
    const GENDER_MAN = 1;
    const GENDER_WOMAN = 2;
    const PERSON_CLASS = "UR\DB\NewBundle\Entity\Person";
    const RELATIVE_CLASS = "UR\DB\NewBundle\Entity\Relative";
    const PARTNER_CLASS = "UR\DB\NewBundle\Entity\Partner";

    private $LOGGER;
    private $container;
    private $newDBManager;

    public function __construct($container) {
        $this->container = $container;
        $this->newDBManager = $this->get('doctrine')->getManager('new');
        $this->LOGGER = $this->get('monolog.logger.personComparison');
    }

    private function get($identifier) {
        return $this->container->get($identifier);
    }

    //ignores comments and source!!!!!!!
    public function comparePersons(\UR\DB\NewBundle\Entity\BasePerson $personOne, \UR\DB\NewBundle\Entity\BasePerson $personTwo, $allowLessInformation = false) {
        $this->LOGGER->info("Person 1: " . $personOne);
        $this->LOGGER->info("Person 2: " . $personTwo);

        if ($personOne->getGender() != $personTwo->getGender() && $personOne->getGender() != self::GENDER_UNKNOWN && $personTwo != self::GENDER_UNKNOWN) {
            return false;
        }

        $this->LOGGER->debug("Gender the same");

        if ($this->compareStrings($personOne->getFirstName(), $personTwo->getFirstName(), $allowLessInformation)) {
            return false;
        }

        if ($this->compareStrings($personOne->getPatronym(), $personTwo->getPatronym(), $allowLessInformation)) {
            return false;
        }

        if ($this->compareStrings($personOne->getLastName(), $personTwo->getLastName(), $allowLessInformation)) {
            return false;
        }

        if ($this->compareStrings($personOne->getForeName(), $personTwo->getForeName(), $allowLessInformation)) {
            return false;
        }

        if ($this->compareStrings($personOne->getBirthName(), $personTwo->getBirthName(), $allowLessInformation)) {
            return false;
        }

        if ($this->compareStrings($personOne->getBornInMarriage(), $personTwo->getBornInMarriage(), $allowLessInformation)) {
            return false;
        }

        $this->LOGGER->debug("Strings the same");

        if ($this->getNation($personOne) != $this->getNation($personTwo)) {
            return false;
        }

        $this->LOGGER->debug("Nation the same");

        if ($this->unmatchedArrays($personOne->getBirth(), $personTwo->getBirth(), "birth", $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($personOne->getBaptism(), $personTwo->getBaptism(), "baptism", $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($personOne->getDeath(), $personTwo->getDeath(), "death", $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($personOne->getEducations(), $personTwo->getEducations(), "education", $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($personOne->getHonours(), $personTwo->getHonours(), "honour", $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($personOne->getProperties(), $personTwo->getProperties(), "property", $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($personOne->getRanks(), $personTwo->getRanks(), "rank", $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($personOne->getReligions(), $personTwo->getReligions(), "religion", $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($personOne->getResidences(), $personTwo->getResidences(), "residence", $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($personOne->getRoadOfLife(), $personTwo->getRoadOfLife(), "roadOfLife", $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($personOne->getStati(), $personTwo->getStati(), "status", $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($personOne->getWorks(), $personTwo->getWorks(), "work", $allowLessInformation)) {
            return false;
        }

        $this->LOGGER->debug("Everything is the same.");

        return true;
    }

    private function compareStrings($stringOne, $stringTwo, $allowLessInformation = false) {
        if(!$allowLessInformation){
            return $stringOne != $stringTwo;
        } else {
            if($stringOne != null && $stringTwo != null){
                $lowerCaseStringOne = strtolower($stringOne);
                $lowerCaseStringTwo = strtolower($stringTwo);
            
                if(strpos($lowerCaseStringOne, $lowerCaseStringTwo) !== false){
                    return true;
                } else if(strpos($lowerCaseStringOne,$lowerCaseStringTwo) !== false){
                    return true;
                } 
                
                return $lowerCaseStringOne != $lowerCaseStringTwo;
            } else {
                return true;
            }
        }
        
    }

    private function getNation($person) {
        if (get_class($person) == self::RELATIVE_CLASS) {
            return $person->getNation();
        }

        return $person->getOriginalNation();
    }

    //@TODO: Improve matching of location, territory, etc. (own methods)
    //@TODO: Move allowLessInformation to the compareStrings method... and change it so that es is used everywhere

    private function unmatchedArrays($arrayOne, $arrayTwo, $type, $allowLessInformation = false) {
        if ($arrayOne == null && $arrayTwo == null) {
            $this->LOGGER->info("Given arrays for type '" . $type . "' are both null.");
            return false;
        }

        if (count($arrayOne) != count($arrayTwo)) {
            $this->LOGGER->info("Given arrays have different size.");
            return true;
        }


        for ($i = 0; $i < count($arrayOne); $i++) {
            $elementOne = $arrayOne[$i];
            $found = false;

            for ($j = 0; $j < count($arrayTwo); $j++) {
                $elementTwo = $arrayTwo[$j];

                switch ($type) {
                    case "date":
                        $found = $this->matchingDates($elementOne, $elementTwo, $allowLessInformation);
                        break;
                    case "birth":
                        $found = $this->matchingBirth($elementOne, $elementTwo, $allowLessInformation);
                        break;
                    case "baptism":
                        $found = $this->matchingBaptism($elementOne, $elementTwo, $allowLessInformation);
                        break;
                    case "death":
                        $found = $this->matchingDeath($elementOne, $elementTwo, $allowLessInformation);
                        break;
                    case "education":
                        $found = $this->matchingEducation($elementOne, $elementTwo, $allowLessInformation);
                        break;
                    case "honour":
                        $found = $this->matchingHonour($elementOne, $elementTwo, $allowLessInformation);
                        break;
                    case "property":
                        $found = $this->matchingProperty($elementOne, $elementTwo, $allowLessInformation);
                        break;
                    case "rank":
                        $found = $this->matchingRank($elementOne, $elementTwo, $allowLessInformation);
                        break;
                    case "religion":
                        $found = $this->matchingReligion($elementOne, $elementTwo, $allowLessInformation);
                        break;
                    case "residence":
                        $found = $this->matchingResidence($elementOne, $elementTwo, $allowLessInformation);
                        break;
                    case "roadOfLife":
                        $found = $this->matchingRoadOfLife($elementOne, $elementTwo, $allowLessInformation);
                        break;
                    case "status":
                        $found = $this->matchingStatus($elementOne, $elementTwo, $allowLessInformation);
                        break;
                    case "work":
                        $found = $this->matchingWork($elementOne, $elementTwo, $allowLessInformation);
                        break;
                    default:
                        $this->LOGGER->warn("No comparison method found for type: " . $type);
                        $found = $elementOne == $elementTwo;
                }

                //found matching element
                if ($found) {
                    continue;
                }
            }

            if (!$found) {
                $this->LOGGER->info("Arrays of type '" . $type . "' are not the same.");
                $this->LOGGER->debug("Did not find element: " . $elementOne->getId());
                return true;
            }
        }

        $this->LOGGER->info("Arrays of type '" . $type . "' are the same.");

        return false;
    }

    public function matchingBirth(\UR\DB\NewBundle\Entity\Birth $birthOne, \UR\DB\NewBundle\Entity\Birth $birthTwo, $allowLessInformation = false) {
        if ($birthOne->getOriginCountry() != $birthTwo->getOriginCountry()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($birthOne->getOriginCountry(), $birthTwo->getOriginCountry())) {
                return false;
            }
        }

        if ($birthOne->getOriginTerritory() != $birthTwo->getOriginTerritory()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($birthOne->getOriginTerritory(), $birthTwo->getOriginTerritory())) {
                return false;
            }
        }

        if ($birthOne->getOriginLocation() != $birthTwo->getOriginLocation()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($birthOne->getOriginLocation(), $birthTwo->getOriginLocation())) {
                return false;
            }
        }

        if ($birthOne->getBirthCountry() != $birthTwo->getBirthCountry()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($birthOne->getBirthCountry(), $birthTwo->getBirthCountry())) {
                return false;
            }
        }

        if ($birthOne->getBirthTerritory() != $birthTwo->getBirthTerritory()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($birthOne->getBirthTerritory(), $birthTwo->getBirthTerritory())) {
                return false;
            }
        }

        if ($birthOne->getBirthLocation() != $birthTwo->getBirthLocation()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($birthOne->getBirthLocation(), $birthTwo->getBirthLocation())) {
                return false;
            }
        }

        if ($this->unmatchedArrays($birthOne->getBirthDate(), $birthTwo->getBirthDate(), "date", $allowLessInformation)) {
            return false;
        }

        return true;
    }

    public function matchingBaptism(\UR\DB\NewBundle\Entity\Baptism $baptismOne, \UR\DB\NewBundle\Entity\Baptism $baptismTwo, $allowLessInformation = false) {
        if ($baptismOne->getBaptismLocation() != $baptismTwo->getBaptismLocation()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($baptismOne->getBaptismLocation(), $baptismTwo->getBaptismLocation())) {
                return false;
            }
        }

        if ($this->unmatchedArrays($baptismOne->getBaptismDate(), $baptismTwo->getBaptismDate(), "date", $allowLessInformation)) {
            return false;
        }

        return true;
    }

    public function matchingDeath(\UR\DB\NewBundle\Entity\Death $deathOne, \UR\DB\NewBundle\Entity\Death $deathTwo, $allowLessInformation = false) {
        if ($deathOne->getDeathLocation() != $deathTwo->getDeathLocation()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($deathOne->getDeathLocation(), $deathTwo->getDeathLocation())) {
                return false;
            }
        }

        if ($deathOne->getDeathCountry() != $deathTwo->getDeathCountry()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($deathOne->getDeathCountry(), $deathTwo->getDeathCountry())) {
                return false;
            }
        }

        if ($deathOne->getCauseOfDeath() != $deathTwo->getCauseOfDeath()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($deathOne->getCauseOfDeath(), $deathTwo->getCauseOfDeath())) {
                return false;
            }
        }

        if ($deathOne->getTerritoryOfDeath() != $deathTwo->getTerritoryOfDeath()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($deathOne->getTerritoryOfDeath(), $deathTwo->getTerritoryOfDeath())) {
                return false;
            }
        }

        if ($deathOne->getGraveyard() != $deathTwo->getGraveyard()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($deathOne->getGraveyard(), $deathTwo->getGraveyard())) {
                return false;
            }
        }

        if ($deathOne->getFuneralLocation() != $deathTwo->getFuneralLocation()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($deathOne->getFuneralLocation(), $deathTwo->getFuneralLocation())) {
                return false;
            }
        }

        if ($this->unmatchedArrays($deathOne->getDeathDate(), $deathTwo->getDeathDate(), "date", $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($deathOne->getFuneralDate(), $deathTwo->getFuneralDate(), "date", $allowLessInformation)) {
            return false;
        }

        return true;
    }

    public function matchingEducation(\UR\DB\NewBundle\Entity\Education $educationOne, \UR\DB\NewBundle\Entity\Education $educationTwo, $allowLessInformation = false) {
        if ($educationOne->getLabel() != $educationTwo->getLabel()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($educationOne->getLabel(), $educationTwo->getLabel())) {
                return false;
            }
        }

        if ($educationOne->getCountry() != $educationTwo->getCountry()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($educationOne->getCountry(), $educationTwo->getCountry())) {
                return false;
            }
        }

        if ($educationOne->getTerritory() != $educationTwo->getTerritory()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($educationOne->getTerritory(), $educationTwo->getTerritory())) {
                return false;
            }
        }

        if ($educationOne->getLocation() != $educationTwo->getLocation()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($educationOne->getLocation(), $educationTwo->getLocation())) {
                return false;
            }
        }

        if ($this->unmatchedArrays($educationOne->getFromDate(), $educationTwo->getFromDate(), "date", $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($educationOne->getToDate(), $educationTwo->getToDate(), "date", $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($educationOne->getProvenDate(), $educationTwo->getProvenDate(), "date", $allowLessInformation)) {
            return false;
        }

        if ($educationOne->getGraduationLabel() != $educationTwo->getGraduationLabel()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($educationOne->getGraduationLabel(), $educationTwo->getGraduationLabel())) {
                return false;
            }
        }

        if ($educationOne->getGraduationLocation() != $educationTwo->getGraduationLocation()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($educationOne->getGraduationLocation(), $educationTwo->getGraduationLocation())) {
                return false;
            }
        }

        if ($this->unmatchedArrays($educationOne->getGraduationDate(), $educationTwo->getGraduationDate(), "date")) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($educationOne->getGraduationDate(), $educationTwo->getGraduationDate())) {
                return false;
            }
        }

        return true;
    }

    public function matchingHonour(\UR\DB\NewBundle\Entity\Honour $honourOne, \UR\DB\NewBundle\Entity\Honour $honourTwo, $allowLessInformation = false) {
        if ($honourOne->getLabel() != $honourTwo->getLabel()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($honourOne->getLabel(), $honourTwo->getLabel())) {
                return false;
            }
        }

        if ($honourOne->getCountry() != $honourTwo->getCountry()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($honourOne->getCountry(), $honourTwo->getCountry())) {
                return false;
            }
        }

        if ($honourOne->getTerritory() != $honourTwo->getTerritory()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($honourOne->getTerritory(), $honourTwo->getTerritory())) {
                return false;
            }
        }

        if ($honourOne->getLocation() != $honourTwo->getLocation()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($honourOne->getLocation(), $honourTwo->getLocation())) {
                return false;
            }
        }

        if ($this->unmatchedArrays($honourOne->getFromDate(), $honourTwo->getFromDate(), "date", $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($honourOne->getToDate(), $honourTwo->getToDate(), "date", $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($honourOne->getProvenDate(), $honourTwo->getProvenDate(), "date", $allowLessInformation)) {
            return false;
        }

        return true;
    }

    public function matchingProperty(\UR\DB\NewBundle\Entity\Property $propertyOne, \UR\DB\NewBundle\Entity\Property $propertyTwo, $allowLessInformation = false) {
        if ($propertyOne->getLabel() != $propertyTwo->getLabel()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($propertyOne->getLabel(), $propertyTwo->getLabel())) {
                return false;
            }
        }

        if ($propertyOne->getCountry() != $propertyTwo->getCountry()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($propertyOne->getCountry(), $propertyTwo->getCountry())) {
                return false;
            }
        }

        if ($propertyOne->getTerritory() != $propertyTwo->getTerritory()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($propertyOne->getTerritory(), $propertyTwo->getTerritory())) {
                return false;
            }
        }

        if ($propertyOne->getLocation() != $propertyTwo->getLocation()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($propertyOne->getLocation(), $propertyTwo->getLocation())) {
                return false;
            }
        }

        if ($this->unmatchedArrays($propertyOne->getFromDate(), $propertyTwo->getFromDate(), "date", $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($propertyOne->getToDate(), $propertyTwo->getToDate(), "date", $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($propertyOne->getProvenDate(), $propertyTwo->getProvenDate(), "date", $allowLessInformation)) {
            return false;
        }

        return true;
    }

    public function matchingRank(\UR\DB\NewBundle\Entity\Rank $rankOne, \UR\DB\NewBundle\Entity\Rank $rankTwo, $allowLessInformation = false) {
        if ($rankOne->getLabel() != $rankTwo->getLabel()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($rankOne->getLabel(), $rankTwo->getLabel())) {
                return false;
            }
        }

        if ($rankOne->getClass() != $rankTwo->getClass()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($rankOne->getClass(), $rankTwo->getClass())) {
                return false;
            }
        }

        if ($rankOne->getCountry() != $rankTwo->getCountry()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($rankOne->getCountry(), $rankTwo->getCountry())) {
                return false;
            }
        }

        if ($rankOne->getTerritory() != $rankTwo->getTerritory()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($rankOne->getTerritory(), $rankTwo->getTerritory())) {
                return false;
            }
        }

        if ($rankOne->getLocation() != $rankTwo->getLocation()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($rankOne->getLocation(), $rankTwo->getLocation())) {
                return false;
            }
        }

        if ($this->unmatchedArrays($rankOne->getFromDate(), $rankTwo->getFromDate(), "date", $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($rankOne->getToDate(), $rankTwo->getToDate(), "date", $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($rankOne->getProvenDate(), $rankTwo->getProvenDate(), "date", $allowLessInformation)) {
            return false;
        }

        return true;
    }

    public function matchingReligion(\UR\DB\NewBundle\Entity\Religion $religionOne, \UR\DB\NewBundle\Entity\Religion $religionTwo, $allowLessInformation = false) {
        if ($religionOne->getName() != $religionTwo->getName()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($religionOne->getName(), $religionTwo->getName())) {
                return false;
            }
        }

        if ($religionOne->getChangeOfReligion() != $religionTwo->getChangeOfReligion()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($religionOne->getChangeOfReligion(), $religionTwo->getChangeOfReligion())) {
                return false;
            }
        }

        if ($this->unmatchedArrays($religionOne->getFromDate(), $religionTwo->getFromDate(), "date", $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($religionOne->getProvenDate(), $religionTwo->getProvenDate(), "date", $allowLessInformation)) {
            return false;
        }

        return true;
    }

    public function matchingResidence(\UR\DB\NewBundle\Entity\Residence $residenceOne, \UR\DB\NewBundle\Entity\Residence $residenceTwo, $allowLessInformation = false) {
        if ($residenceOne->getResidenceCountry() != $residenceTwo->getResidenceCountry()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($residenceOne->getResidenceCountry(), $residenceTwo->getResidenceCountry())) {
                return false;
            }
        }

        if ($residenceOne->getResidenceTerritory() != $residenceTwo->getResidenceTerritory()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($residenceOne->getResidenceTerritory(), $residenceTwo->getResidenceTerritory())) {
                return false;
            }
        }

        if ($residenceOne->getResidenceLocation() != $residenceTwo->getResidenceLocation()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($residenceOne->getResidenceLocation(), $residenceTwo->getResidenceLocation())) {
                return false;
            }
        }

        return true;
    }

    public function matchingRoadOfLife(\UR\DB\NewBundle\Entity\RoadOfLife $roadOfLifeOne, \UR\DB\NewBundle\Entity\RoadOfLife $roadOfLifeTwo, $allowLessInformation = false) {
        if ($roadOfLifeOne->getJob() != $roadOfLifeTwo->getJob()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($roadOfLifeOne->getJob(), $roadOfLifeOne->getJob())) {
                return false;
            }
        }

        if ($roadOfLifeOne->getOriginCountry() != $roadOfLifeTwo->getOriginCountry()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($roadOfLifeOne->getOriginCountry(), $roadOfLifeOne->getOriginCountry())) {
                return false;
            }
        }

        if ($roadOfLifeOne->getOriginTerritory() != $roadOfLifeTwo->getOriginTerritory()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($roadOfLifeOne->getOriginTerritory(), $roadOfLifeOne->getOriginTerritory())) {
                return false;
            }
        }

        if ($roadOfLifeOne->getCountry() != $roadOfLifeTwo->getCountry()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($roadOfLifeOne->getCountry(), $roadOfLifeOne->getCountry())) {
                return false;
            }
        }

        if ($roadOfLifeOne->getTerritory() != $roadOfLifeTwo->getTerritory()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($roadOfLifeOne->getTerritory(), $roadOfLifeOne->getTerritory())) {
                return false;
            }
        }

        if ($roadOfLifeOne->getLocation() != $roadOfLifeTwo->getLocation()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($roadOfLifeOne->getLocation(), $roadOfLifeOne->getLocation())) {
                return false;
            }
        }

        if ($this->unmatchedArrays($roadOfLifeOne->getFromDate(), $roadOfLifeTwo->getFromDate(), "date", $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($roadOfLifeOne->getToDate(), $roadOfLifeTwo->getToDate(), "date", $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($roadOfLifeOne->getProvenDate(), $roadOfLifeTwo->getProvenDate(), "date", $allowLessInformation)) {
            return false;
        }

        return true;
    }

    public function matchingStatus(\UR\DB\NewBundle\Entity\Status $statusOne, \UR\DB\NewBundle\Entity\Status $statusTwo, $allowLessInformation = false) {
        if ($statusOne->getLabel() != $statusTwo->getLabel()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($statusOne->getLabel(), $statusTwo->getLabel())) {
                return false;
            }
        }

        if ($statusOne->getCountry() != $statusTwo->getCountry()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($statusOne->getCountry(), $statusTwo->getCountry())) {
                return false;
            }
        }

        if ($statusOne->getTerritory() != $statusTwo->getTerritory()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($statusOne->getTerritory(), $statusTwo->getTerritory())) {
                return false;
            }
        }

        if ($statusOne->getLocation() != $statusTwo->getLocation()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($statusOne->getLocation(), $statusTwo->getLocation())) {
                return false;
            }
        }

        if ($this->unmatchedArrays($statusOne->getFromDate(), $statusTwo->getFromDate(), "date", $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($statusOne->getToDate(), $statusTwo->getToDate(), "date", $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($statusOne->getProvenDate(), $statusTwo->getProvenDate(), "date", $allowLessInformation)) {
            return false;
        }

        return true;
    }

    public function matchingWork(\UR\DB\NewBundle\Entity\Works $workOne, \UR\DB\NewBundle\Entity\Works $workTwo, $allowLessInformation = false) {
        if ($workOne->getLabel() != $workTwo->getLabel()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($workOne->getLabel(), $workTwo->getLabel())) {
                return false;
            }
        }

        if ($workOne->getCountry() != $workTwo->getCountry()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($workOne->getCountry(), $workTwo->getCountry())) {
                return false;
            }
        }

        if ($workOne->getTerritory() != $workTwo->getTerritory()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($workOne->getTerritory(), $workTwo->getTerritory())) {
                return false;
            }
        }

        if ($workOne->getLocation() != $workTwo->getLocation()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($workOne->getLocation(), $workTwo->getLocation())) {
                return false;
            }
        }

        if ($this->unmatchedArrays($workOne->getFromDate(), $workTwo->getFromDate(), "date", $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($workOne->getToDate(), $workTwo->getToDate(), "date", $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($workOne->getProvenDate(), $workTwo->getProvenDate(), "date", $allowLessInformation)) {
            return false;
        }

        return true;
    }

    public function matchingSource(\UR\DB\NewBundle\Entity\Source $sourceOne, \UR\DB\NewBundle\Entity\Source $sourceTwo, $allowLessInformation = false) {
        if ($sourceOne->getLabel() != $sourceTwo->getLabel()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($sourceOne->getLabel(), $sourceTwo->getLabel())) {
                return false;
            }
        }

        if ($sourceOne->getPlaceOfDiscovery() != $sourceTwo->getPlaceOfDiscovery()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($sourceOne->getPlaceOfDiscovery(), $sourceTwo->getPlaceOfDiscovery())) {
                return false;
            }
        }

        if ($sourceOne->getRemark() != $sourceTwo->getRemark()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($sourceOne->getRemark(), $sourceTwo->getRemark())) {
                return false;
            }
        }

        return true;
    }

    public function matchingDates(\UR\DB\NewBundle\Entity\Date $dateOne, \UR\DB\NewBundle\Entity\Date $dateTwo, $allowLessInformation = false) {

        if ($dateOne->getDay() != $dateTwo->getDay()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($dateOne->getDay(), $dateTwo->getDay())) {
                return false;
            }
        }

        if ($dateOne->getMonth() != $dateTwo->getMonth()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($dateOne->getMonth(), $dateTwo->getMonth())) {
                return false;
            }
        }

        if ($dateOne->getYear() != $dateTwo->getYear()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($dateOne->getYear(), $dateTwo->getYear())) {
                return false;
            }
        }

        if ($dateOne->getWeekday() != $dateTwo->getWeekday()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($dateOne->getWeekday(), $dateTwo->getWeekday())) {
                return false;
            }
        }

        if ($dateOne->getBeforeDate() != $dateTwo->getBeforeDate()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($dateOne->getBeforeDate(), $dateTwo->getBeforeDate())) {
                return false;
            }
        }

        if ($dateOne->getAfterDate() != $dateTwo->getAfterDate()) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($dateOne->getAfterDate(), $dateTwo->getAfterDate())) {
                return false;
            }
        }

        return true;
    }

    private function checkIfOneValueContainsMoreInformation($valueOne, $valueTwo) {
        return $valueOne != null && $valueTwo != null;
    }

}
