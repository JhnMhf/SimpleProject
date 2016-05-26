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
    
    public function __construct($container)
    {
        $this->container = $container;
        $this->newDBManager = $this->get('doctrine')->getManager('new');
        $this->LOGGER = $this->get('monolog.logger.personComparison');
    }
    
    private function get($identifier){
        return $this->container->get($identifier);
    }
    
    //ignores comments and source!!!!!!!
    public function comparePersons($personOne, $personTwo){
        $this->LOGGER->info("Person 1: ".$personOne);
        $this->LOGGER->info("Person 2: ".$personTwo);
        
        if($personOne->getGender() != $personTwo->getGender()
                && $personOne->getGender() != self::GENDER_UNKNOWN && $personTwo != self::GENDER_UNKNOWN){
            return false;
        }
        
        $this->LOGGER->debug("Gender the same");
        
        if($this->compareStrings($personOne->getFirstName(),$personTwo->getFirstName())){
            return false;
        }
        
        if($this->compareStrings($personOne->getPatronym(),$personTwo->getPatronym())){
            return false;
        }
        
        if($this->compareStrings($personOne->getLastName(),$personTwo->getLastName())){
            return false;
        }
        
        if($this->compareStrings($personOne->getForeName(),$personTwo->getForeName())){
            return false;
        }
        
        if($this->compareStrings($personOne->getBirthName(),$personTwo->getBirthName())){
            return false;
        }
        
        if($this->compareStrings($personOne->getBornInMarriage(),$personTwo->getBornInMarriage())){
            return false;
        }

        $this->LOGGER->debug("Strings the same");
        
        if($this->getNation($personOne) != $this->getNation($personTwo)){
            return false;
        }
        
        $this->LOGGER->debug("Nation the same");
        
        if($this->unmatchedArrays($personOne->getBirth(), $personTwo->getBirth(), "birth")){
            return false;
        }
        
        if($this->unmatchedArrays($personOne->getBaptism(), $personTwo->getBaptism(), "baptism")){
            return false;
        }
        
        if($this->unmatchedArrays($personOne->getDeath(), $personTwo->getDeath(), "death")){
            return false;
        }
        
        if($this->unmatchedArrays($personOne->getEducations(), $personTwo->getEducations(), "education")){
            return false;
        }
        
        if($this->unmatchedArrays($personOne->getHonours(), $personTwo->getHonours(), "honour")){
            return false;
        }
        
        if($this->unmatchedArrays($personOne->getProperties(), $personTwo->getProperties(), "property")){
            return false;
        }
        
        if($this->unmatchedArrays($personOne->getRanks(), $personTwo->getRanks(), "rank")){
            return false;
        }
        
        if($this->unmatchedArrays($personOne->getReligions(), $personTwo->getReligions(), "religion")){
            return false;
        }
        
        if($this->unmatchedArrays($personOne->getResidences(), $personTwo->getResidences(), "residence")){
            return false;
        }
        
        if($this->unmatchedArrays($personOne->getRoadOfLife(), $personTwo->getRoadOfLife(), "roadOfLife")){
            return false;
        }
        
        if($this->unmatchedArrays($personOne->getStati(), $personTwo->getStati(), "status")){
            return false;
        }
        
        if($this->unmatchedArrays($personOne->getWorks(), $personTwo->getWorks(), "work")){
            return false;
        }
        
        $this->LOGGER->debug("Everything is the same.");
 
        return true;
    }
    
    private function compareStrings($stringOne, $stringTwo){
        return $stringOne != $stringTwo;
    }
    
    private function getNation($person){
        if(get_class($person) == self::RELATIVE_CLASS){
            return $person->getNation();
        }
        
        return $person->getOriginalNation();
    }

    
    private function unmatchedArrays($arrayOne, $arrayTwo, $type){
        if($arrayOne == null && $arrayTwo == null){
            $this->LOGGER->info("Given arrays for type '".$type."' are both null.");
            return false;
        }
        
        if(count($arrayOne) != count($arrayTwo)){
            $this->LOGGER->info("Given arrays have different size.");
            return true;
        }
        
        
        for($i = 0; $i < count($arrayOne); $i++){
            $elementOne = $arrayOne[$i];
            $found = false;
            
            for($j = 0; $j < count($arrayTwo); $j++){
                $elementTwo = $arrayTwo[$j];
                
                switch($type){
                    case "date":
                        $found = $this->matchingDates($elementOne, $elementTwo);
                        break;
                    case "birth":
                        $found = $this->matchingBirth($elementOne, $elementTwo);
                        break;
                    case "baptism":
                        $found = $this->matchingBaptism($elementOne, $elementTwo);
                        break;
                    case "death":
                        $found = $this->matchingDeath($elementOne, $elementTwo);
                        break;
                    case "education":
                        $found = $this->matchingEducation($elementOne, $elementTwo);
                        break;
                    case "honour":
                        $found = $this->matchingHonour($elementOne, $elementTwo);
                        break;
                    case "property":
                        $found = $this->matchingProperty($elementOne, $elementTwo);
                        break;
                    case "rank":
                        $found = $this->matchingRank($elementOne, $elementTwo);
                        break;
                    case "religion":
                        $found = $this->matchingReligion($elementOne, $elementTwo);
                        break;
                    case "residence":
                        $found = $this->matchingResidence($elementOne, $elementTwo);
                        break;
                    case "roadOfLife":
                        $found = $this->matchingRoadOfLife($elementOne, $elementTwo);
                        break;
                    case "status":
                        $found = $this->matchingStatus($elementOne, $elementTwo);
                        break;
                    case "work":
                        $found = $this->matchingWork($elementOne, $elementTwo);
                        break;
                    default:
                        $this->LOGGER->warn("No comparison method found for type: ".$type);
                        $found = $elementOne == $elementTwo;
                }
                
                //found matching element
                if($found){
                    continue;
                }
                
            }
            
            if(!$found){
                $this->LOGGER->info("Arrays of type '".$type."' are not the same.");
                $this->LOGGER->debug("Did not find element: ".$elementOne->getId());
                return true;
            }
        }
        
        $this->LOGGER->info("Arrays of type '".$type."' are the same.");
        
        return false;
    }
    
    public function matchingBirth($birthOne, $birthTwo){
        if($birthOne->getOriginCountry() != $birthTwo->getOriginCountry()){
            return false;
        }
        
        if($birthOne->getOriginTerritory() != $birthTwo->getOriginTerritory()){
            return false;
        }
        
        if($birthOne->getOriginLocation() != $birthTwo->getOriginLocation()){
            return false;
        }
        
        if($birthOne->getBirthCountry() != $birthTwo->getBirthCountry()){
            return false;
        }
        
        if($birthOne->getBirthTerritory() != $birthTwo->getBirthTerritory()){
            return false;
        }
        
        if($birthOne->getBirthLocation() != $birthTwo->getBirthLocation()){
            return false;
        }
        
        if($this->unmatchedArrays($birthOne->getBirthDate(),$birthTwo->getBirthDate(), "date")){
            return false;
        }
        
        return true;
    }
    
    public function matchingBaptism($baptismOne, $baptismTwo){
        if($baptismOne->getBaptismLocation() != $baptismTwo->getBaptismLocation()){
            return false;
        }
        
        if($this->unmatchedArrays($baptismOne->getBaptismDate(),$baptismTwo->getBaptismDate(), "date")){
            return false;
        }
        
        return true;
    }
    
    public function matchingDeath($deathOne, $deathTwo){
        if($deathOne->getDeathLocation() != $deathTwo->getDeathLocation()){
            return false;
        }
        
        if($deathOne->getDeathCountry() != $deathTwo->getDeathCountry()){
            return false;
        }
      
        if($deathOne->getCauseOfDeath() != $deathTwo->getCauseOfDeath()){
            return false;
        }
        
        if($deathOne->getTerritoryOfDeath() != $deathTwo->getTerritoryOfDeath()){
            return false;
        }
        
        if($deathOne->getGraveyard() != $deathTwo->getGraveyard()){
            return false;
        }
        
        if($deathOne->getFuneralLocation() != $deathTwo->getFuneralLocation()){
            return false;
        }
        
        if($this->unmatchedArrays($deathOne->getDeathDate(),$deathTwo->getDeathDate(), "date")){
            return false;
        }
        
        if($this->unmatchedArrays($deathOne->getFuneralDate(),$deathTwo->getFuneralDate(), "date")){
            return false;
        }
        
        return true;
    }
    
    public function matchingEducation($educationOne, $educationTwo){
        if($educationOne->getLabel() != $educationTwo->getLabel()){
            return false;
        }
        
        if($educationOne->getCountry() != $educationTwo->getCountry()){
            return false;
        }
        
        if($educationOne->getTerritory() != $educationTwo->getTerritory()){
            return false;
        }
        
        if($educationOne->getLocation() != $educationTwo->getLocation()){
            return false;
        }
        
        if($this->unmatchedArrays($educationOne->getFromDate(),$educationTwo->getFromDate(), "date")){
            return false;
        }
        
        if($this->unmatchedArrays($educationOne->getToDate(),$educationTwo->getToDate(), "date")){
            return false;
        }
        
        if($this->unmatchedArrays($educationOne->getProvenDate(),$educationTwo->getProvenDate(), "date")){
            return false;
        }
        
        if($educationOne->getGraduationLabel() != $educationTwo->getGraduationLabel()){
            return false;
        }
        
        if($educationOne->getGraduationLocation() != $educationTwo->getGraduationLocation()){
            return false;
        }
        
        if($this->unmatchedArrays($educationOne->getGraduationDate(),$educationTwo->getGraduationDate(), "date")){
            return false;
        }
        
        return true;
    }
    
    public function matchingHonour($honourOne, $honourTwo){
        if($honourOne->getLabel() != $honourTwo->getLabel()){
            return false;
        }
        
        if($honourOne->getCountry() != $honourTwo->getCountry()){
            return false;
        }
        
        if($honourOne->getTerritory() != $honourTwo->getTerritory()){
            return false;
        }
        
        if($honourOne->getLocation() != $honourTwo->getLocation()){
            return false;
        }
        
        if($this->unmatchedArrays($honourOne->getFromDate(),$honourTwo->getFromDate(), "date")){
            return false;
        }
        
        if($this->unmatchedArrays($honourOne->getToDate(),$honourTwo->getToDate(), "date")){
            return false;
        }
        
        if($this->unmatchedArrays($honourOne->getProvenDate(),$honourTwo->getProvenDate(), "date")){
            return false;
        }
        
        return true;
    }
    
    public function matchingProperty($propertyOne, $propertyTwo){
        if($propertyOne->getLabel() != $propertyTwo->getLabel()){
            return false;
        }
        
        if($propertyOne->getCountry() != $propertyTwo->getCountry()){
            return false;
        }
        
        if($propertyOne->getTerritory() != $propertyTwo->getTerritory()){
            return false;
        }
        
        if($propertyOne->getLocation() != $propertyTwo->getLocation()){
            return false;
        }
        
        if($this->unmatchedArrays($propertyOne->getFromDate(),$propertyTwo->getFromDate(), "date")){
            return false;
        }
        
        if($this->unmatchedArrays($propertyOne->getToDate(),$propertyTwo->getToDate(), "date")){
            return false;
        }
        
        if($this->unmatchedArrays($propertyOne->getProvenDate(),$propertyTwo->getProvenDate(), "date")){
            return false;
        }
        
        return true;
    }
    
    public function matchingRank($rankOne, $rankTwo){
        if($rankOne->getLabel() != $rankTwo->getLabel()){
            return false;
        }
        
        if($rankOne->getClass() != $rankTwo->getClass()){
            return false;
        }
        
        if($rankOne->getCountry() != $rankTwo->getCountry()){
            return false;
        }
        
        if($rankOne->getTerritory() != $rankTwo->getTerritory()){
            return false;
        }
        
        if($rankOne->getLocation() != $rankTwo->getLocation()){
            return false;
        }
        
        if($this->unmatchedArrays($rankOne->getFromDate(),$rankTwo->getFromDate(), "date")){
            return false;
        }
        
        if($this->unmatchedArrays($rankOne->getToDate(),$rankTwo->getToDate(), "date")){
            return false;
        }
        
        if($this->unmatchedArrays($rankOne->getProvenDate(),$rankTwo->getProvenDate(), "date")){
            return false;
        }
        
        return true;
    }
    
    public function matchingReligion($religionOne, $religionTwo){
        if($religionOne->getName() != $religionTwo->getName()){
            return false;
        }
        
        if($religionOne->getChangeOfReligion() != $religionTwo->getChangeOfReligion()){
            return false;
        }
        
        if($this->unmatchedArrays($religionOne->getFromDate(),$religionTwo->getFromDate(), "date")){
            return false;
        }
        
        if($this->unmatchedArrays($religionOne->getProvenDate(),$religionTwo->getProvenDate(), "date")){
            return false;
        }
        
        return true;
    }
          
    public function matchingResidence($residenceOne, $residenceTwo){
        if($residenceOne->getResidenceCountry() != $residenceTwo->getResidenceCountry()){
            return false;
        }
        
        if($residenceOne->getResidenceTerritory() != $residenceTwo->getResidenceTerritory()){
            return false;
        }
        
        if($residenceOne->getResidenceLocation() != $residenceTwo->getResidenceLocation()){
            return false;
        }
        
        return true;
    }
    
    public function matchingRoadOfLife($roadOfLifeOne, $roadOfLifeTwo){
        if($roadOfLifeOne->getJob() != $roadOfLifeTwo->getJob()){
            return false;
        }
        
        if($roadOfLifeOne->getOriginCountry() != $roadOfLifeTwo->getOriginCountry()){
            return false;
        }
        
        if($roadOfLifeOne->getOriginTerritory() != $roadOfLifeTwo->getOriginTerritory()){
            return false;
        }
        
        if($roadOfLifeOne->getCountry() != $roadOfLifeTwo->getCountry()){
            return false;
        }
        
        if($roadOfLifeOne->getTerritory() != $roadOfLifeTwo->getTerritory()){
            return false;
        }
        
        if($roadOfLifeOne->getLocation() != $roadOfLifeTwo->getLocation()){
            return false;
        }
        
        if($this->unmatchedArrays($roadOfLifeOne->getFromDate(),$roadOfLifeTwo->getFromDate(), "date")){
            return false;
        }
        
        if($this->unmatchedArrays($roadOfLifeOne->getToDate(),$roadOfLifeTwo->getToDate(), "date")){
            return false;
        }
        
        if($this->unmatchedArrays($roadOfLifeOne->getProvenDate(),$roadOfLifeTwo->getProvenDate(), "date")){
            return false;
        }
        
        return true;
    }
    
    public function matchingStatus($statusOne, $statusTwo){
        if($statusOne->getLabel() != $statusTwo->getLabel()){
            return false;
        }
        
        if($statusOne->getCountry() != $statusTwo->getCountry()){
            return false;
        }
        
        if($statusOne->getTerritory() != $statusTwo->getTerritory()){
            return false;
        }
        
        if($statusOne->getLocation() != $statusTwo->getLocation()){
            return false;
        }
        
        if($this->unmatchedArrays($statusOne->getFromDate(),$statusTwo->getFromDate(), "date")){
            return false;
        }
        
        if($this->unmatchedArrays($statusOne->getToDate(),$statusTwo->getToDate(), "date")){
            return false;
        }
        
        if($this->unmatchedArrays($statusOne->getProvenDate(),$statusTwo->getProvenDate(), "date")){
            return false;
        }
        
        return true;
    }
    
    public function matchingWork($workOne, $workTwo){
        if($workOne->getLabel() != $workTwo->getLabel()){
            return false;
        }
        
        if($workOne->getCountry() != $workTwo->getCountry()){
            return false;
        }
        
        if($workOne->getTerritory() != $workTwo->getTerritory()){
            return false;
        }
        
        if($workOne->getLocation() != $workTwo->getLocation()){
            return false;
        }
        
        if($this->unmatchedArrays($workOne->getFromDate(),$workTwo->getFromDate(), "date")){
            return false;
        }
        
        if($this->unmatchedArrays($workOne->getToDate(),$workTwo->getToDate(), "date")){
            return false;
        }
        
        if($this->unmatchedArrays($workOne->getProvenDate(),$workTwo->getProvenDate(), "date")){
            return false;
        }
        
        return true;
    }

    
    public function matchingDates($dateOne, $dateTwo){

        if($dateOne->getDay() != $dateTwo->getDay()){
            return false;
        }
        
        if($dateOne->getMonth() != $dateTwo->getMonth()){
            return false;
        }
        
        if($dateOne->getYear() != $dateTwo->getYear()){
            return false;
        }

        if($dateOne->getWeekday() != $dateTwo->getWeekday()){
            return false;
        }
        
        if($dateOne->getBeforeDate() != $dateTwo->getBeforeDate()){
            return false;
        }
        
        if($dateOne->getAfterDate() != $dateTwo->getAfterDate()){
            return false;
        }

        return true;
    }
}
