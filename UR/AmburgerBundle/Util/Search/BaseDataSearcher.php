<?php

namespace UR\AmburgerBundle\Util\Search;

/*
 * Should contain all logic necessary in the children.
 * The children should only implemented the search process itself
 */
abstract class BaseDataSearcher {
    
    protected $LOGGER;
    protected $finalDBManager;
    
    public function __construct($LOGGER, $finalDBManager)
    {
        $this->LOGGER = $LOGGER;
        $this->finalDBManager = $finalDBManager;
    } 
    
    public abstract function search($queryString, $onlyMainPersons, $lastName, $firstName, $patronym, $location, $territory, $country, $date, $fromDate, $toDate);

    protected function extractIdArray($results){
        $idArray = [];
        
        for($i = 0; $i < count($results); $i++){
            $idArray[] = $results[$i]['id'];
        }
        
        return $idArray;
    }
    
    protected function findLocation($queryString, $location){
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

        return $this->extractIdArray($stmt->fetchAll());
    }
        
    protected function findTerritory($queryString, $territory){
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

        return $this->extractIdArray($stmt->fetchAll());
    }
    
    protected function findCountry($queryString, $country){
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

        return $this->extractIdArray($stmt->fetchAll());
    }
     
    protected function findNation($queryString){
        $finalDBManager = $this->getFinalDBManager();

        $sql = "SELECT id FROM nation WHERE name LIKE '%:nation%'";
        $stmt = $finalDBManager->getConnection()->prepare($sql);
        
        $stmt->bindValue('nation', $queryString);

        $stmt->execute();

        return $this->extractIdArray($stmt->fetchAll());
    }
     
    protected function findJob($queryString){
        $finalDBManager = $this->getFinalDBManager();

        $sql = "SELECT id FROM job WHERE label LIKE '%:job%'";
        $stmt = $finalDBManager->getConnection()->prepare($sql);
        
        $stmt->bindValue('job', $queryString);

        $stmt->execute();

        return $this->extractIdArray($stmt->fetchAll());
    }
     
    protected function findJobClass($queryString){
        $finalDBManager = $this->getFinalDBManager();

        $sql = "SELECT id FROM job_class WHERE label LIKE '%:jobClass%'";
        $stmt = $finalDBManager->getConnection()->prepare($sql);
        
        $stmt->bindValue('jobClass', $queryString);

        $stmt->execute();

        return $this->extractIdArray($stmt->fetchAll());
    }
    
    protected function findDates($queryString, $date, $fromDate, $toDate){
        
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

    protected function checkAllPersonsByNames($onlyMainPersons, $lastName, $firstName, $patronym){
        if($onlyMainPersons){
            return $this->checkPersonByName($lastName, $firstName, $patronym);
        } else {
            $searchIds = $this->checkPersonByName($lastName, $firstName, $patronym);
            
            $searchIds = array_merge($searchIds, $this->checkRelativeByName($lastName, $firstName, $patronym));
            
            $searchIds = array_merge($searchIds, $this->checkPartnerByName($lastName, $firstName, $patronym));
            
            sort($searchIds, SORT_NUMERIC);

            return $searchIds;
        }
    }
    
    protected function checkPersonByName($lastName, $firstName, $patronym) {
        return $this->checkBasePersonByName("SELECT id FROM person WHERE ", $lastName, $firstName, $patronym);
    }
    
    protected function checkRelativeByName($lastName, $firstName, $patronym) {
        return $this->checkBasePersonByName("SELECT id FROM relative WHERE ", $lastName, $firstName, $patronym);
    }
    
    protected function checkPartnerByName($lastName, $firstName, $patronym) {
        return $this->checkBasePersonByName("SELECT id FROM partner WHERE ", $lastName, $firstName, $patronym);
    }
    
    protected function checkBasePersonByName($baseQuery, $lastName, $firstName, $patronym) {
        $finalDBManager = $this->finalDBManager;
        
        $sql = $baseQuery;
        
        $foundOne = false;
        
        if(!empty($lastName)){
            $sql .= "last_name LIKE :lastname";
            $foundOne = true;
        }

        if(!empty($firstName)){
            if($foundOne){
                $sql .= " AND ";
            }
            $sql .= "first_name LIKE :firstname";
            $foundOne = true;
        }
        
        if(!empty($patronym)){
            if($foundOne){
                $sql .= " AND ";
            }
            $sql .= "patronym LIKE :patronym";
        }
        
        $this->LOGGER->debug("Using query: ".$sql);
        
        $stmt = $finalDBManager->getConnection()->prepare($sql);
        
        if(!empty($lastName)){
            $stmt->bindValue('lastname', '%'.$lastName.'%');
        }

        if(!empty($firstName)){
            $stmt->bindValue('firstname', '%'.$firstName.'%');
        }
        
        if(!empty($patronym)){
            $stmt->bindValue('patronym', '%'.$patronym.'%');
        }
        
        $stmt->execute();

        return $this->extractIdArray($stmt->fetchAll());
    }

}

