<?php

namespace UR\AmburgerBundle\Util;

class SearchUtil {
    
    private $LOGGER;
    private $container;
    private $finalDBManager;
    
    public function __construct($container)
    {
        $this->container = $container;
    } 
    
    private function get($identifier){
        return $this->container->get($identifier);
    }
    
    private function getLogger(){
        if(is_null($this->LOGGER)){
            $this->LOGGER = $this->get('monolog.logger.default');
        }
        
        return $this->LOGGER;
    }
    
    private function getFinalDBManager(){
        if(is_null($this->finalDBManager)){
            $this->finalDBManager = $this->get('doctrine')->getManager('final');
        }
        
        return $this->finalDBManager;
    }

    //boolean logic:
    // example: A AND (B OR C)
    // first search all entries matching A, 
    // then all entries matching B, 
    // then all entries matching C
    // build intersection of AB and AC
    // then add to one list and return
    
    //example 2: A AND (B XOR C)
    // first search all entries matching A, 
    // then all entries matching B, 
    // then all entries matching C
    // remove from matching entries of B all entries which are also in C, and vice-versa at the same time!!!
    // build intersection of AB and AC
    // then add to one list and return
    
    //Consequences: We have to first check if the queryString is boolean logic
    //and afterwards we have to do the search
    //and in the case of boolean logic we have to have a rule based system to
    //connect the results again
    
    //remember queryString can be a boolean logic query AND it should be checked
    //for almost anything...
    
    //1. if location, territory or country is set, load the matching id from db
    //1.1 if for any of this fields a value is set, but no matching id is found return empty list?
    //2. use queryString to find matching jobs, jobclass, nations and reuse them later while searching
    //3. search through educations etc. (how to handle death, birth and baptism?)
    //3.1 if year/fromYear&toYear is set, first search in date and use this in the next query
    //3.2. if no year/fromYear&toYear is set ignore date table and just use data from location, territory, country
    //4. search through birth, baptism and death and use the ids in the person
    //5. combine the found person ids from all relations to a big list
    //6. search person while using the person ids and lastName, firstName, patronym
    //6.1 if only main persons is true, check only the person table
    //6.2 if it is false check all tables
    //7. return complete list of matching ids
    
    public function search($queryString, $onlyMainPersons, $lastName, $firstName, $patronym, $location, $territory, $country, $date, $fromDate, $toDate){
        
        $onlyQueryStringUsedForReferences = empty($location) && empty($territory) 
                && empty($country) && empty($date) 
                && empty($fromDate) && empty($toDate);
        
        $locationReferenceId = null;
        $territoryReferenceId = null;
        $countryReferenceId = null;
        $possibleDateReferenceIds = array();
        $possibleJobIds = array();
        $possibleJobClassIds = array();
        $possibleNationIds = array();
        $matchingBaptismIds = array();
        $matchingBirthIds = array();
        $matchingDeathIds = array();
        
        if(!empty($location) || !empty($queryString)){
            $locationReferenceId = $this->findLocation($queryString, $location);
        }
        
        if(!empty($territory) || !empty($queryString)){
            $territoryReferenceId = $this->findTerritory($queryString, $territory);
        }
        
        if(!empty($country) || !empty($queryString)){
            $countryReferenceId = $this->findCountry($queryString, $country);
        }
        
        if(!empty($country) || !empty($queryString)){
            $countryReferenceId = $this->findCountry($queryString, $country);
        }
        
        if(!empty($date) || (!empty($fromDate) && !empty($toDate)) || !empty($queryString)){
            $possibleDateReferenceIds = $this->findDates($queryString, $date, $fromDate, $toDate);
        }
        
        $educationIds = $this->searchInEducations($onlyQueryStringUsedForReferences, $queryString, 
                $locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds);
        
        
        $ids = $educationIds;
        
        if($onlyQueryStringUsedForReferences){
            //combine results from references with found persons from checkallpersons
            
            //get person ids for birth, baptism, death etc in an extra query
            
            //if only main person, the persons from references must be check if they are a main person
        } else {
            //build intersection of persons which are in references as well as in checkallpersons
        }
        
        $personIds = $this->checkAllPersons($queryString, $onlyMainPersons, $lastName, $firstName, $patronym,$possibleJobIds, $possibleJobClassIds, $possibleNationIds, $matchingBaptismIds, $matchingBirthIds, $matchingDeathIds);
        
        return $personIds;
    }
    
    
    private function checkAllPersons($queryString, $onlyMainPersons, $lastName, $firstName, $patronym,$possibleJobIds, $possibleJobClassIds, $possibleNationIds, $matchingBaptismIds, $matchingBirthIds, $matchingDeathIds){
        
        return array();
    }
    
    private function checkPerson($queryString, $lastName, $firstName, $patronym, $ids) {
        $finalDBManager = $this->getFinalDBManager();
        
        $sql = "SELECT ID FROM PERSON WHERE ";
        $stmt = $finalDBManager->getConnection()->prepare($sql);
        
        if(!empty($lastName) && !empty($firstName) && !empty($patronym)){
            $sql .= "last_name LIKE '%:lastname%' AND first_name LIKE '%:firstname%' AND patronym LIKE '%:patronym%' ";
        } else if(!empty($lastName) && !empty($firstName)){
            $sql .= "(last_name LIKE '%:lastname%' AND first_name LIKE '%:firstname%') ";
        } else if(!empty($lastName) && !empty($patronym)){
            $sql .= "(last_name LIKE '%:lastname%' AND patronym LIKE '%:patronym%') ";
        } else if(!empty($firstName) && !empty($patronym)){
            $sql .= "(first_name LIKE '%:firstname%' AND patronym LIKE '%:patronym%') ";
        } else {
            
        }

        $stmt->execute();

        return $stmt->fetchAll();
        
        return array();
    }
    
    private function checkRelatives($queryString, $lastName, $firstName, $patronym, $ids) {
        
        return array();
    }
    
    private function checkPartners($queryString, $lastName, $firstName, $patronym, $ids) {
        
        return array();
    }
    
    private function findLocation($queryString, $location){
        $finalDBManager = $this->getFinalDBManager();
        
        $sql = "SELECT ID FROM LOCATION WHERE name LIKE '%:location%'";
        $stmt = $finalDBManager->getConnection()->prepare($sql);
        
        if(empty($location)){
             $stmt->bindValue('location', $queryString);
           
        } else {
            //if location is filled ignore querystring
             $stmt->bindValue('location', $location);
        }

        $stmt->execute();

        return $stmt->fetchAll();
    }
        
    private function findTerritory($queryString, $territory){
        $finalDBManager = $this->getFinalDBManager();
        
        
        $sql = "SELECT ID FROM TERRITORY WHERE name LIKE '%:territory%'";
        $stmt = $finalDBManager->getConnection()->prepare($sql);
        
        if(empty($territory)){
             $stmt->bindValue('territory', $queryString);
           
        } else {
            //if location is filled ignore querystring
             $stmt->bindValue('territory', $territory);
        }

        $stmt->execute();

        return $stmt->fetchAll();
    }
    
    private function findCountry($queryString, $country){
        $finalDBManager = $this->getFinalDBManager();
        
        
        $sql = "SELECT ID FROM LOCATION WHERE name LIKE '%:country%'";
        $stmt = $finalDBManager->getConnection()->prepare($sql);
        
        if(empty($country)){
             $stmt->bindValue('country', $queryString);
           
        } else {
            //if location is filled ignore querystring
             $stmt->bindValue('country', $country);
        }

        $stmt->execute();

        return $stmt->fetchAll();
    }
    
    private function findDates($queryString, $date, $fromDate, $toDate){
        
        if(empty($date) && empty($fromDate) && empty($toDate)){
            //use queryString
            
        } else if(!empty($fromDate) && !empty($fromDate)){
            //use fromDate and fromDate
        } else if(!empty($date)){
            //use date
        } else {
            return array();
        }
        
    }

    
    private function searchInEducations($onlyQueryStringUsedForReferences, $queryString, 
            $locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds){
        
    }
}

