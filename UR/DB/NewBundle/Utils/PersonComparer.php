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
    public function comparePersons($personOne, $personTwo, $allowLessInformation = false) {
        $this->LOGGER->info("Person 1: " . $personOne);
        $this->LOGGER->info("Person 2: " . $personTwo);
        
        if($personOne == null && $personTwo == null){
            return true;
        } else if($personOne == null){
            return $allowLessInformation;
        } else if($personTwo == null){
            return $allowLessInformation;
        }
        
        if($personOne->getId() == $personTwo->getId()){
            $this->LOGGER->info("They got the same ID, should be the same person or the database has an error");
            return true;
        }

        if ($personOne->getGender() != $personTwo->getGender() && $personOne->getGender() != self::GENDER_UNKNOWN && $personTwo->getGender() != self::GENDER_UNKNOWN) {
            return false;
        }

        $this->LOGGER->debug("Gender the same");

        if (!$this->compareStrings($personOne->getFirstName(), $personTwo->getFirstName(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareStrings($personOne->getPatronym(), $personTwo->getPatronym(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareStrings($personOne->getLastName(), $personTwo->getLastName(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareStrings($personOne->getForeName(), $personTwo->getForeName(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareStrings($personOne->getBirthName(), $personTwo->getBirthName(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareStrings($personOne->getBornInMarriage(), $personTwo->getBornInMarriage(), $allowLessInformation)) {
            return false;
        }

        $this->LOGGER->debug("Strings the same");

        if (!$this->compareNations($this->getNation($personOne), $this->getNation($personTwo), $allowLessInformation)) {
            return false;
        }

        $this->LOGGER->debug("Nation the same");
        
        if (!$this->compareJobs($personOne->getJob(), $personTwo->getJob(), $allowLessInformation)) {
            return false;
        }

        $this->LOGGER->debug("Jobs are the same");
        
        if (!$this->compareJobClasses($personOne->getJobClass(), $personTwo->getJobClass(), $allowLessInformation)) {
            return false;
        }

        $this->LOGGER->debug("JobClasses are the same");

        if (!$this->matchingBirth($personOne->getBirth(), $personTwo->getBirth(), $allowLessInformation)) {
            return false;
        }

        if (!$this->matchingBaptism($personOne->getBaptism(), $personTwo->getBaptism(), $allowLessInformation)) {
            return false;
        }

        if (!$this->matchingDeath($personOne->getDeath(), $personTwo->getDeath(), $allowLessInformation)) {
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
        $this->LOGGER->debug("Comparing '".$stringOne."' with '".$stringTwo."'");
        if (!$allowLessInformation) {
            return $stringOne == $stringTwo;
        } else {
            if ($stringOne != null && $stringTwo != null) {
                $lowerCaseStringOne = strtolower($stringOne);
                $lowerCaseStringTwo = strtolower($stringTwo);

                if (strpos($lowerCaseStringOne, $lowerCaseStringTwo) !== false) {
                    return true;
                } else if (strpos($lowerCaseStringOne, $lowerCaseStringTwo) !== false) {
                    return true;
                }

                return $lowerCaseStringOne == $lowerCaseStringTwo;
            } else {
                return true;
            }
        }
    }

    private function getNation($person) {
        return $person->getNation();
    }

    private function unmatchedArrays($arrayOne, $arrayTwo, $type, $allowLessInformation = false) {
        $this->LOGGER->info("Checking arrays of type '" . $type . "'.");
        if ($arrayOne == null && $arrayTwo == null) {
            $this->LOGGER->info("Given arrays for type '" . $type . "' are both null.");
            return false;
        }

        if (count($arrayOne) != count($arrayTwo) && !$allowLessInformation) {
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
                        $this->LOGGER->error("No comparison method found for type: " . $type);
                        $found = $elementOne == $elementTwo;
                }

                //found matching element
                if ($found) {
                    break;
                }
            }

            if (!$found) {
                $this->LOGGER->info("Arrays of type '" . $type . "' are not the same.");
                $this->LOGGER->debug("Did not find element: " . $elementOne->getId());
                if(!$allowLessInformation){
                    return true;
                } else {
                    $this->LOGGER->debug("Continuing since less informations are accepted");
                }
            }
        }

        $this->LOGGER->info("Arrays of type '" . $type . "' are the same.");

        return false;
    }

    public function matchingBirth($birthOne, $birthTwo, $allowLessInformation = false) {
        $this->LOGGER->debug("Comparing ".$birthOne. " with ".$birthTwo);
        $this->LOGGER->info("Checking if births are the same");
        
        if ($birthOne == null || $birthTwo == null) {
            if ($allowLessInformation) {
                return true;
            } else {
                return $birthOne == $birthTwo;
            }
        }
        if (!$this->compareCountries($birthOne->getOriginCountry(), $birthTwo->getOriginCountry(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareTerritories($birthOne->getOriginTerritory(), $birthTwo->getOriginTerritory(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareLocations($birthOne->getOriginLocation(), $birthTwo->getOriginLocation(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareCountries($birthOne->getBirthCountry(), $birthTwo->getBirthCountry(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareTerritories($birthOne->getBirthTerritory(), $birthTwo->getBirthTerritory(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareLocations($birthOne->getBirthLocation(), $birthTwo->getBirthLocation(), $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($birthOne->getBirthDate(), $birthTwo->getBirthDate(), "date", $allowLessInformation)) {
            return false;
        }
        
        if ($this->unmatchedArrays($birthOne->getProvenDate(), $birthTwo->getProvenDate(), "date", $allowLessInformation)) {
            return false;
        }

        return true;
    }

    public function matchingBaptism($baptismOne, $baptismTwo, $allowLessInformation = false) {
        $this->LOGGER->debug("Comparing ".$baptismOne. " with ".$baptismTwo);
        $this->LOGGER->info("Checking if baptisms are the same");
        
        if ($baptismOne == null || $baptismTwo == null) {
            if ($allowLessInformation) {
                return true;
            } else {
                return $baptismOne == $baptismTwo;
            }
        }

        if (!$this->compareLocations($baptismOne->getBaptismLocation(), $baptismTwo->getBaptismLocation(), $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($baptismOne->getBaptismDate(), $baptismTwo->getBaptismDate(), "date", $allowLessInformation)) {
            return false;
        }
        
        return true;
    }

    public function matchingDeath($deathOne, $deathTwo, $allowLessInformation = false) {
        $this->LOGGER->debug("Comparing ".$deathOne. " with ".$deathTwo);
        
        $this->LOGGER->info("Checking if deaths are the same.");
        
        if ($deathOne == null || $deathTwo == null) {
            if ($allowLessInformation) { 
                return true;
            } else {
                return $deathOne == $deathTwo;
            }
        }

        if (!$this->compareLocations($deathOne->getDeathLocation(), $deathTwo->getDeathLocation(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareCountries($deathOne->getDeathCountry(), $deathTwo->getDeathCountry(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareStrings($deathOne->getCauseOfDeath(), $deathTwo->getCauseOfDeath(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareTerritories($deathOne->getTerritoryOfDeath(), $deathTwo->getTerritoryOfDeath(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareStrings($deathOne->getGraveyard(), $deathTwo->getGraveyard(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareLocations($deathOne->getFuneralLocation(), $deathTwo->getFuneralLocation(), $allowLessInformation)) {
            return false;
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
        $this->LOGGER->debug("Comparing ".$educationOne. " with ".$educationTwo);
        if (!$this->compareStrings($educationOne->getLabel(), $educationTwo->getLabel(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareCountries($educationOne->getCountry(), $educationTwo->getCountry(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareTerritories($educationOne->getTerritory(), $educationTwo->getTerritory(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareLocations($educationOne->getLocation(), $educationTwo->getLocation(), $allowLessInformation)) {
            return false;
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

        if (!$this->compareStrings($educationOne->getGraduationLabel(), $educationTwo->getGraduationLabel(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareLocations($educationOne->getGraduationLocation(), $educationTwo->getGraduationLocation(), $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($educationOne->getGraduationDate(), $educationTwo->getGraduationDate(), "date")) {
            if (!$allowLessInformation || $this->checkIfOneValueContainsMoreInformation($educationOne->getGraduationDate(), $educationTwo->getGraduationDate())) {
                return false;
            }
        }

        return true;
    }

    public function matchingHonour(\UR\DB\NewBundle\Entity\Honour $honourOne, \UR\DB\NewBundle\Entity\Honour $honourTwo, $allowLessInformation = false) {
        $this->LOGGER->debug("Comparing ".$honourOne. " with ".$honourTwo);
        if (!$this->compareStrings($honourOne->getLabel(), $honourTwo->getLabel(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareCountries($honourOne->getCountry(), $honourTwo->getCountry(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareTerritories($honourOne->getTerritory(), $honourTwo->getTerritory(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareLocations($honourOne->getLocation(), $honourTwo->getLocation(), $allowLessInformation)) {
            return false;
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
        $this->LOGGER->debug("Comparing ".$propertyOne. " with ".$propertyTwo);
        if (!$this->compareStrings($propertyOne->getLabel(), $propertyTwo->getLabel(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareCountries($propertyOne->getCountry(), $propertyTwo->getCountry(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareTerritories($propertyOne->getTerritory(), $propertyTwo->getTerritory(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareLocations($propertyOne->getLocation(), $propertyTwo->getLocation(), $allowLessInformation)) {
            return false;
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
        $this->LOGGER->debug("Comparing ".$rankOne. " with ".$rankTwo);
        if (!$this->compareStrings($rankOne->getLabel(), $rankTwo->getLabel(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareStrings($rankOne->getClass(), $rankTwo->getClass(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareCountries($rankOne->getCountry(), $rankTwo->getCountry(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareTerritories($rankOne->getTerritory(), $rankTwo->getTerritory(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareLocations($rankOne->getLocation(), $rankTwo->getLocation(), $allowLessInformation)) {
            return false;
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
        $this->LOGGER->debug("Comparing ".$religionOne. " with ".$religionTwo);
        if (!$this->compareStrings($religionOne->getName(), $religionTwo->getName(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareStrings($religionOne->getChangeOfReligion(), $religionTwo->getChangeOfReligion(), $allowLessInformation)) {
            return false;
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
        $this->LOGGER->debug("Comparing ".$residenceOne. " with ".$residenceTwo);
        if (!$this->compareCountries($residenceOne->getResidenceCountry(), $residenceTwo->getResidenceCountry(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareTerritories($residenceOne->getResidenceTerritory(), $residenceTwo->getResidenceTerritory(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareLocations($residenceOne->getResidenceLocation(), $residenceTwo->getResidenceLocation(), $allowLessInformation)) {
            return false;
        }

        return true;
    }

    public function matchingRoadOfLife(\UR\DB\NewBundle\Entity\RoadOfLife $roadOfLifeOne, \UR\DB\NewBundle\Entity\RoadOfLife $roadOfLifeTwo, $allowLessInformation = false) {
        $this->LOGGER->debug("Comparing ".$roadOfLifeOne. " with ".$roadOfLifeTwo);
        if (!$this->compareStrings($roadOfLifeOne->getJob(), $roadOfLifeTwo->getJob(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareCountries($roadOfLifeOne->getOriginCountry(), $roadOfLifeTwo->getOriginCountry(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareTerritories($roadOfLifeOne->getOriginTerritory(), $roadOfLifeTwo->getOriginTerritory(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareCountries($roadOfLifeOne->getCountry(), $roadOfLifeTwo->getCountry(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareTerritories($roadOfLifeOne->getTerritory(), $roadOfLifeTwo->getTerritory(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareLocations($roadOfLifeOne->getLocation(), $roadOfLifeTwo->getLocation(), $allowLessInformation)) {
            return false;
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
        $this->LOGGER->debug("Comparing ".$statusOne. " with ".$statusTwo);
        if (!$this->compareStrings($statusOne->getLabel(), $statusTwo->getLabel(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareCountries($statusOne->getCountry(), $statusTwo->getCountry(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareTerritories($statusOne->getTerritory(), $statusTwo->getTerritory(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareLocations($statusOne->getLocation(), $statusTwo->getLocation(), $allowLessInformation)) {
            return false;
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
        $this->LOGGER->debug("Comparing ".$workOne. " with ".$workTwo);
        if (!$this->compareStrings($workOne->getLabel(), $workTwo->getLabel(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareCountries($workOne->getCountry(), $workTwo->getCountry(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareTerritories($workOne->getTerritory(), $workTwo->getTerritory(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareLocations($workOne->getLocation(), $workTwo->getLocation(), $allowLessInformation)) {
            return false;
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
    
    public function matchingWedding(\UR\DB\NewBundle\Entity\Wedding $weddingOne, \UR\DB\NewBundle\Entity\Wedding $weddingTwo, $allowLessInformation = false){
        $this->LOGGER->debug("Comparing ".$weddingOne. " with ".$weddingTwo);
        
        if ($this->unmatchedArrays($weddingOne->getWeddingDate(), $weddingTwo->getWeddingDate(), "date", $allowLessInformation)) {
            return false;
        }
        
        if (!$this->compareTerritories($weddingOne->getWeddingTerritory(), $weddingTwo->getWeddingTerritory(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareLocations($weddingOne->getWeddingLocation(), $weddingTwo->getWeddingLocation(), $allowLessInformation)) {
            return false;
        }
       
        if ($this->unmatchedArrays($weddingOne->getBannsDate(), $weddingTwo->getBannsDate(), "date", $allowLessInformation)) {
            return false;
        }
        
        if (!$this->compareStrings($weddingOne->getBreakupReason(), $weddingTwo->getBreakupReason(), $allowLessInformation)) {
            return false;
        }

        if ($this->unmatchedArrays($weddingOne->getBreakupDate(), $weddingTwo->getBreakupDate(), "date", $allowLessInformation)) {
            return false;
        }
        
        if ($this->unmatchedArrays($weddingOne->getProvenDate(), $weddingTwo->getProvenDate(), "date", $allowLessInformation)) {
            return false;
        }
        
        if (!$this->compareStrings($weddingOne->getBeforeAfter(), $weddingTwo->getBeforeAfter(), $allowLessInformation)) {
            return false;
        }

        //marriage comment?
        return true;
    }

    public function matchingSource(\UR\DB\NewBundle\Entity\Source $sourceOne, \UR\DB\NewBundle\Entity\Source $sourceTwo, $allowLessInformation = false) {
        $this->LOGGER->debug("Comparing ".$sourceOne. " with ".$sourceTwo);
        
        if (!$this->compareStrings($sourceOne->getLabel(), $sourceTwo->getLabel(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareStrings($sourceOne->getPlaceOfDiscovery(), $sourceTwo->getPlaceOfDiscovery(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareStrings($sourceOne->getRemark(), $sourceTwo->getRemark(), $allowLessInformation)) {
            return false;
        }

        return true;
    }

    /* TestId: 69955
     * Catchable Fatal Error: Argument 2 passed to UR\DB\NewBundle\Utils\PersonComparer::matchingDates() must 
     * be an instance of UR\DB\NewBundle\Entity\Date, instance of UR\DB\NewBundle\Utils\DateRange given, called 
     *  in /home/johanna/Masterarbeit/Symfony/amburger/src/UR/DB/NewBundle/Utils/PersonMerger.php on line 335 and defined 
     */
    public function matchingDates($dateElementOne, $dateElementTwo, $allowLessInformation = false){
        if($dateElementOne instanceof \UR\DB\NewBundle\Entity\Date && $dateElementTwo instanceof \UR\DB\NewBundle\Entity\Date){
            return $this->matchingDateObj($dateElementOne, $dateElementTwo, $allowLessInformation);
        }
        
        if($dateElementOne instanceof \UR\DB\NewBundle\Utils\DateRange && $dateElementTwo instanceof \UR\DB\NewBundle\Utils\DateRange){
            return $this->matchingDateRanges($dateElementOne, $dateElementTwo, $allowLessInformation);
        }
        
        return false;
    }
    
    public function matchingDateRanges(\UR\DB\NewBundle\Utils\DateRange $dateRangeOne, \UR\DB\NewBundle\Utils\DateRange $dateRangeTwo, $allowLessInformation = false) {
        $this->LOGGER->debug("Comparing '".$dateRangeOne."' with '".$dateRangeTwo."'");
        
        if($this->matchingDates($dateRangeOne->getFrom(), $dateRangeTwo->getFrom(), $allowLessInformation)){
            return false;
        }
                
        if($this->matchingDates($dateRangeOne->getTo(), $dateRangeTwo->getTo(), $allowLessInformation)){
            return false;
        }
        
        $this->LOGGER->debug("DateRanges are matching");
      
        return true;
    }
    
    public function matchingDateObj(\UR\DB\NewBundle\Entity\Date $dateOne, \UR\DB\NewBundle\Entity\Date $dateTwo, $allowLessInformation = false) {
        $this->LOGGER->debug("Comparing '".$dateOne."' with '".$dateTwo."'");
        
        if(!$allowLessInformation){
            if ($dateOne->getDay() != $dateTwo->getDay()) {
                return false;
            }

            if ($dateOne->getMonth() != $dateTwo->getMonth()) {
                return false;
            }

            if($dateOne->getYear() != $dateTwo->getYear()) {
                return false;
            }
        }else {
            if ($dateOne->getDay() == null || $dateTwo->getDay() == null) {
                return true;
            } else if ($dateOne->getDay() != $dateTwo->getDay()) {
                return false;
            }
            
            if ($dateOne->getMonth() == null || $dateTwo->getMonth() == null) {
                return true;
            } else if ($dateOne->getMonth() != $dateTwo->getMonth()) {
                return false;
            }

            if ($dateOne->getYear() == null || $dateTwo->getYear() == null) {
                return true;
            } else if ($dateOne->getYear() != $dateTwo->getYear()) {
                return false;
            }
        }
        

        if (!$this->compareStrings($dateOne->getWeekday(), $dateTwo->getWeekday(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareStrings($dateOne->getBeforeDate(), $dateTwo->getBeforeDate(), $allowLessInformation)) {
            return false;
        }

        if (!$this->compareStrings($dateOne->getAfterDate(), $dateTwo->getAfterDate(), $allowLessInformation)) {
            return false;
        }
        
        $this->LOGGER->debug("Dates are matching");

        return true;
    }

    private function compareNations($nationOne, $nationTwo, $allowLessInformation = false) {
        $this->LOGGER->debug("Comparing ".$nationOne. " with ".$nationTwo);
        if ($nationOne == null || $nationTwo == null) {
            if ($nationOne == null && $nationTwo != null) {
                return $allowLessInformation;
            } else if ($nationOne != null && $nationTwo == null) {
                return $allowLessInformation;
            }
            return true;
        } else {
            return $this->compareStrings($nationOne->getName(), $nationTwo->getName(), $allowLessInformation);
        }
    }

    private function compareCountries($countryOne, $countryTwo, $allowLessInformation = false) {
        $this->LOGGER->debug("Comparing ".$countryOne. " with ".$countryTwo);
        if ($countryOne == null || $countryTwo == null) {
            if ($countryOne == null && $countryTwo != null) {
                return $allowLessInformation;
            } else if ($countryOne != null && $countryTwo == null) {
                return $allowLessInformation;
            }
            return true;
        } else {
            return $this->compareStrings($countryOne->getName(), $countryTwo->getName(), $allowLessInformation);
        }
    }

    private function compareTerritories($territoryOne, $territoryTwo, $allowLessInformation = false) {
        $this->LOGGER->debug("Comparing ".$territoryOne. " with ".$territoryTwo);
        if ($territoryOne == null || $territoryTwo == null) {
            if ($territoryOne == null && $territoryTwo != null) {
                return $allowLessInformation;
            } else if ($territoryOne != null && $territoryTwo == null) {
                return $allowLessInformation;
            }
            return true;
        }else {
            return $this->compareStrings($territoryOne->getName(), $territoryTwo->getName(), $allowLessInformation);
        }
    }

    private function compareLocations($locationOne, $locationTwo, $allowLessInformation = false) {
        $this->LOGGER->debug("Comparing ".$locationOne. " with ".$locationTwo);
        if ($locationOne == null || $locationTwo == null) {
            if ($locationOne == null && $locationTwo != null) {
                return $allowLessInformation;
            } else if ($locationOne != null && $locationTwo == null) {
                return $allowLessInformation;
            }
            return true;
        } else {
            return $this->compareStrings($locationOne->getName(), $locationTwo->getName(), $allowLessInformation);
        }
    }

    private function compareJobs($jobOne, $jobTwo, $allowLessInformation = false) {
        $this->LOGGER->debug("Comparing ".$jobOne. " with ".$jobTwo);
        if ($jobOne == null || $jobTwo == null) {
            if ($jobOne == null && $jobTwo != null) {
                return $allowLessInformation;
            } else if ($jobOne != null && $jobTwo == null) {
                return $allowLessInformation;
            }
            return true;
        }else {
            return $this->compareStrings($jobOne->getLabel(), $jobTwo->getLabel(), $allowLessInformation);
        }
    }

    private function compareJobClasses($jobClassOne, $jobClassTwo, $allowLessInformation = false) {
        $this->LOGGER->debug("Comparing ".$jobClassOne. " with ".$jobClassTwo);
        if ($jobClassOne == null || $jobClassTwo == null) {
            if ($jobClassOne == null && $jobClassTwo != null) {
                return $allowLessInformation;
            } else if ($jobClassOne != null && $jobClassTwo == null) {
                return $allowLessInformation;
            }
            return true;
        }else {
            return $this->compareStrings($jobClassOne->getLabel(), $jobClassTwo->getLabel(), $allowLessInformation);
        }
    }

}
