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

    private $LOGGER;

    private $container;
    private $newDBManager;
    
    public function __construct($container)
    {
        $this->container = $container;
        $this->newDBManager = $this->get('doctrine')->getManager('new');
        $this->LOGGER = $this->get('monolog.logger.new');
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

        if($this->compareObjects($this->getNation($personOne), $this->getNation($personTwo))){
            return false;
        }
        
        if($this->compareObjects($personOne->getBirths(), $personTwo->getBirths())){
            return false;
        }
        
        if($this->compareObjects($personOne->getBaptism(), $personTwo->getBaptism())){
            return false;
        }
        
        if($this->compareObjects($personOne->getDeaths(), $personTwo->getDeaths())){
            return false;
        }
        
        if($this->compareObjects($personOne->getEducations(), $personTwo->getEducations())){
            return false;
        }
        
        if($this->compareObjects($personOne->getHonours(), $personTwo->getHonours())){
            return false;
        }
        
        if($this->compareObjects($personOne->getProperties(), $personTwo->getProperties())){
            return false;
        }
        
        if($this->compareObjects($personOne->getRanks(), $personTwo->getRanks())){
            return false;
        }
        
        if($this->compareObjects($personOne->getReligions(), $personTwo->getReligions())){
            return false;
        }
        
        if($this->compareObjects($personOne->getResidences(), $personTwo->getResidences())){
            return false;
        }
        
        if($this->compareObjects($personOne->getRoadOfLife(), $personTwo->getRoadOfLife())){
            return false;
        }
        
        if($this->compareObjects($personOne->getStati(), $personTwo->getStati())){
            return false;
        }
        
        if($this->compareObjects($personOne->getWorks(), $personTwo->getWorks())){
            return false;
        }
 
        return true;
    }
    
    private function compareStrings($stringOne, $stringTwo){
        return $stringOne != $stringTwo;
    }
    
    private function getNation($person){
        if(get_class($personOne) == self::RELATIVE_CLASS){
            return $person->getNation();
        }
        
        return $person->getOriginalNation();
    }
    
    private function compareObjects($objectOne, $objectTwo){
        return $objectOne != $objectTwo;
    }
}
