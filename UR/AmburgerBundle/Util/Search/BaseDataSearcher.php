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
    
    public abstract function isApplicable($queryString, $onlyMainPersons, $lastName, $firstName, $patronym, $location, $territory, $country, $date, $fromDate, $toDate);
    
    public abstract function search($queryString, $onlyMainPersons, $lastName, $firstName, $patronym, $location, $territory, $country, $date, $fromDate, $toDate);

    protected function geographicalMarkersAreSet($location, $territory, $country){
        return !empty($location) || !empty($territory) || !empty($country);
    }
    
    protected function dateMarkersAreSet($date, $fromDate, $toDate){
        return !empty($date) || !empty($fromDate) || !empty($toDate);
    }
    
    protected function personDataMarkersAreSet($lastName, $firstName, $patronym, $onlyMainPersons = false){
        return $onlyMainPersons || !empty($lastName) || !empty($firstName) || !empty($patronym);
    }
    
    protected function extractIdArray($results, $fieldName = 'id'){
        $idArray = [];
        
        for($i = 0; $i < count($results); $i++){
            $idArray[] = $results[$i][$fieldName];
        }
        
        return $idArray;
    }
    
    protected function findLocation($location){
        $finalDBManager = $this->finalDBManager;
        
        $sql = "SELECT id FROM location WHERE name LIKE :location";
        $stmt = $finalDBManager->getConnection()->prepare($sql);
        
        $stmt->bindValue('location', '%'.$location.'%');
        
        $stmt->execute();

        return $this->extractIdArray($stmt->fetchAll());
    }
        
    protected function findTerritory($territory){
        $finalDBManager = $this->finalDBManager;
        
        
        $sql = "SELECT id FROM territory WHERE name LIKE :territory";
        $stmt = $finalDBManager->getConnection()->prepare($sql);
        
        $stmt->bindValue('territory', '%'.$territory.'%');

        $stmt->execute();

        return $this->extractIdArray($stmt->fetchAll());
    }
    
    protected function findCountry($country){
        $finalDBManager = $this->finalDBManager;
        
        
        $sql = "SELECT id FROM country WHERE name LIKE :country";
        $stmt = $finalDBManager->getConnection()->prepare($sql);

        $stmt->bindValue('country', '%'.$country.'%');

        $stmt->execute();

        return $this->extractIdArray($stmt->fetchAll());
    }
     
    protected function findNation($queryString){
        $finalDBManager = $this->finalDBManager;

        $sql = "SELECT id FROM nation WHERE name LIKE :nation";
        $stmt = $finalDBManager->getConnection()->prepare($sql);
        
        $stmt->bindValue('nation', '%'.$queryString.'%');

        $stmt->execute();

        return $this->extractIdArray($stmt->fetchAll());
    }
     
    protected function findJob($queryString){
        $finalDBManager = $this->finalDBManager;

        $sql = "SELECT id FROM job WHERE label LIKE :job";
        $stmt = $finalDBManager->getConnection()->prepare($sql);
        
        $stmt->bindValue('job', '%'.$queryString.'%');

        $stmt->execute();

        return $this->extractIdArray($stmt->fetchAll());
    }
     
    protected function findJobClass($queryString){
        $finalDBManager = $this->finalDBManager;

        $sql = "SELECT id FROM job_class WHERE label LIKE :jobClass";
        $stmt = $finalDBManager->getConnection()->prepare($sql);
        
        $stmt->bindValue('jobClass', '%'.$queryString.'%');

        $stmt->execute();

        return $this->extractIdArray($stmt->fetchAll());
    }
    
    protected function findDatesBasedOnDate($date){
        
    }
    
    protected function findDatesBasedOnDateRange($fromDate, $toDate){
        
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

    protected function searchInBaptism($onlyMainPerson, $isAndCondition,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds, $personReferenceIds = array()) {
        if(count($locationReferenceId) == 0 && count($possibleDateReferenceIds) == 0){
            return array();
        }
        
        $innerQuery = "SELECT id FROM baptism WHERE ";
        $personFieldName = "baptism_id";
        
        $personIds = $this->baseSearchInPersonWithInnerQuery('person', $personFieldName, $innerQuery, $isAndCondition,
                array('baptism_locationid'), $locationReferenceId, 
                array(), $territoryReferenceId, 
                array(), $countryReferenceId, 
                array('baptism_dateid'), $possibleDateReferenceIds,
                array('person_id'), $personReferenceIds);
        
        if(!$onlyMainPerson) {
            $relativeIds = $this->baseSearchInPersonWithInnerQuery('relative', $personFieldName, $innerQuery, $isAndCondition,
                array('baptism_locationid'), $locationReferenceId, 
                array(), $territoryReferenceId, 
                array(), $countryReferenceId, 
                array('baptism_dateid'), $possibleDateReferenceIds,
                array('person_id'), $personReferenceIds);
        
            $personIds = array_merge($personIds, $relativeIds);

            $partnerIds = $this->baseSearchInPersonWithInnerQuery('partner', $personFieldName, $innerQuery, $isAndCondition,
                array('baptism_locationid'), $locationReferenceId, 
                array(), $territoryReferenceId, 
                array(), $countryReferenceId, 
                array('baptism_dateid'), $possibleDateReferenceIds,
                array('person_id'), $personReferenceIds);

            $personIds = array_merge($personIds, $partnerIds);
        }

        return $personIds;
    }
    
    protected function searchInBirth($onlyMainPerson,$isAndCondition,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds, $personReferenceIds = array()) {
                
        $innerQuery = "SELECT id FROM birth WHERE ";
        $personFieldName = "birth_id";
        
        $personIds = $this->baseSearchInPersonWithInnerQuery('person', $personFieldName, $innerQuery, $isAndCondition,
                array('birth_locationid', 'origin_locationid'), $locationReferenceId, 
                array('birth_territoryid','origin_territoryid'), $territoryReferenceId, 
                array('birth_countryid','origin_countryid'), $countryReferenceId, 
                array('birth_dateid'), $possibleDateReferenceIds,
                array('person_id'), $personReferenceIds);
        
        if(!$onlyMainPerson) {
            $relativeIds = $this->baseSearchInPersonWithInnerQuery('relative', $personFieldName, $innerQuery, $isAndCondition,
                array('birth_locationid', 'origin_locationid'), $locationReferenceId, 
                array('birth_territoryid','origin_territoryid'), $territoryReferenceId, 
                array('birth_countryid','origin_countryid'), $countryReferenceId, 
                array('birth_dateid'), $possibleDateReferenceIds,
                array('person_id'), $personReferenceIds);
        
            $personIds = array_merge($personIds, $relativeIds);

            $partnerIds = $this->baseSearchInPersonWithInnerQuery('partner', $personFieldName, $innerQuery, $isAndCondition,
                array('birth_locationid', 'origin_locationid'), $locationReferenceId, 
                array('birth_territoryid','origin_territoryid'), $territoryReferenceId, 
                array('birth_countryid','origin_countryid'), $countryReferenceId, 
                array('birth_dateid'), $possibleDateReferenceIds,
                array('person_id'), $personReferenceIds);

            $personIds = array_merge($personIds, $partnerIds);
        }

        return $personIds;
    }
    
    protected function searchInDeath($onlyMainPerson,$isAndCondition,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds, $personReferenceIds = array()) {
                
        $innerQuery = "SELECT id FROM death WHERE ";
        $personFieldName = "death_id";
        
        $personIds = $this->baseSearchInPersonWithInnerQuery('person', $personFieldName, $innerQuery,  $isAndCondition,
                array('funeral_locationid', 'death_locationid'), $locationReferenceId, 
                array('territory_of_deathid'), $territoryReferenceId, 
                array('death_countryid'), $countryReferenceId, 
                array('funeral_dateid', 'death_dateid'), $possibleDateReferenceIds,
                array('person_id'), $personReferenceIds);
        
        if(!$onlyMainPerson) {
            $relativeIds = $this->baseSearchInPersonWithInnerQuery('relative', $personFieldName, $innerQuery,  $isAndCondition,
                array('funeral_locationid', 'death_locationid'), $locationReferenceId, 
                array('territory_of_deathid'), $territoryReferenceId, 
                array('death_countryid'), $countryReferenceId, 
                array('funeral_dateid', 'death_dateid'), $possibleDateReferenceIds,
                array('person_id'), $personReferenceIds);
        
            $personIds = array_merge($personIds, $relativeIds);

            $partnerIds = $this->baseSearchInPersonWithInnerQuery('partner', $personFieldName, $innerQuery,  $isAndCondition,
                array('funeral_locationid', 'death_locationid'), $locationReferenceId, 
                array('territory_of_deathid'), $territoryReferenceId, 
                array('death_countryid'), $countryReferenceId, 
                array('funeral_dateid', 'death_dateid'), $possibleDateReferenceIds,
                array('person_id'), $personReferenceIds);

            $personIds = array_merge($personIds, $partnerIds);
        }

        return $personIds;
    }
    
    protected function searchInEducations($isAndCondition,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds, $personReferenceIds = array()) {
        $sql = "SELECT DISTINCT person_id FROM education WHERE ";
        
        return $this->baseSearchForPerson($sql, 'person_id', $isAndCondition,
                array('locationid', 'graduation_locationid'), $locationReferenceId, 
                array('territoryid'), $territoryReferenceId, 
                array('countryid'), $countryReferenceId, 
                array('from_dateid', 'to_dateid', 'proven_dateid', 'graduation_dateid'), $possibleDateReferenceIds,
                array('person_id'), $personReferenceIds);
    }
    
    protected function searchInHonours($isAndCondition,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds, $personReferenceIds = array()) {
        $sql = "SELECT DISTINCT person_id FROM honour WHERE ";
        
        return $this->baseSearchForPerson($sql, 'person_id', $isAndCondition,
                array('locationid'), $locationReferenceId, 
                array('territoryid'), $territoryReferenceId, 
                array('countryid'), $countryReferenceId, 
                array('from_dateid', 'to_dateid', 'proven_dateid'), $possibleDateReferenceIds,
                array('person_id'), $personReferenceIds);
    }

    protected function searchInProperties($isAndCondition,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds, $personReferenceIds = array()) {
        $sql = "SELECT DISTINCT person_id FROM property WHERE ";
        
        return $this->baseSearchForPerson($sql, 'person_id', $isAndCondition,
                array('locationid'), $locationReferenceId, 
                array('territoryid'), $territoryReferenceId, 
                array('countryid'), $countryReferenceId, 
                array('from_dateid', 'to_dateid', 'proven_dateid'), $possibleDateReferenceIds,
                array('person_id'), $personReferenceIds);
    }
    
    protected function searchInRanks($isAndCondition,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds, $personReferenceIds = array()) {
        $sql = "SELECT DISTINCT person_id FROM rank WHERE ";
        
        return $this->baseSearchForPerson($sql, 'person_id',$isAndCondition, 
                array('locationid'), $locationReferenceId, 
                array('territoryid'), $territoryReferenceId, 
                array('countryid'), $countryReferenceId, 
                array('from_dateid', 'to_dateid', 'proven_dateid'), $possibleDateReferenceIds,
                array('person_id'), $personReferenceIds);
    }
    
    protected function searchInReligions($isAndCondition,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds, $personReferenceIds = array()) {
        if(count($possibleDateReferenceIds) == 0){
            return array();
        }
        
        $sql = "SELECT DISTINCT person_id FROM religion WHERE ";
        
        return $this->baseSearchForPerson($sql, 'person_id', $isAndCondition,
                array(), $locationReferenceId, 
                array(), $territoryReferenceId, 
                array(), $countryReferenceId, 
                array('from_dateid', 'to_dateid'), $possibleDateReferenceIds,
                array('person_id'), $personReferenceIds);
    }
    
    
    protected function searchInResidence($isAndCondition,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds, $personReferenceIds = array()) {
        $sql = "SELECT DISTINCT person_id FROM residence WHERE ";
        
        return $this->baseSearchForPerson($sql, 'person_id', $isAndCondition,
                array('residence_locationid'), $locationReferenceId, 
                array('residence_territoryid'), $territoryReferenceId, 
                array('residence_countryid'), $countryReferenceId, 
                array(), $possibleDateReferenceIds,
                array('person_id'), $personReferenceIds);
    }
    
    protected function searchInRoadOfLife($isAndCondition,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds, $personReferenceIds = array()) {
        $sql = "SELECT DISTINCT person_id FROM road_of_life WHERE ";
        
        return $this->baseSearchForPerson($sql, 'person_id', $isAndCondition,
                array('locationid'), $locationReferenceId, 
                array('origin_territoryid', 'territoryid'), $territoryReferenceId, 
                array('origin_countryid','countryid'), $countryReferenceId, 
                array('from_dateid', 'to_dateid', 'proven_dateid'), $possibleDateReferenceIds,
                array('person_id'), $personReferenceIds);
    }
    
    protected function searchInStatus($isAndCondition,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds, $personReferenceIds = array()) {
        $sql = "SELECT DISTINCT person_id FROM status WHERE ";
        
        return $this->baseSearchForPerson($sql, 'person_id', $isAndCondition,
                array('locationid'), $locationReferenceId, 
                array('territoryid'), $territoryReferenceId, 
                array('countryid'), $countryReferenceId, 
                array('from_dateid', 'to_dateid', 'proven_dateid'), $possibleDateReferenceIds,
                array('person_id'), $personReferenceIds);
    }
    
    protected function searchInWorks($isAndCondition,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds, $personReferenceIds = array()) {
        $sql = "SELECT DISTINCT person_id FROM works WHERE ";
        
        return $this->baseSearchForPerson($sql, 'person_id', $isAndCondition,
                array('locationid'), $locationReferenceId, 
                array('territoryid'), $territoryReferenceId, 
                array('countryid'), $countryReferenceId, 
                array('from_dateid', 'to_dateid', 'proven_dateid'), $possibleDateReferenceIds,
                array('person_id'), $personReferenceIds);
    }
    
    private function baseSearchForPerson($baseQuery,$extractFieldName,$isAndCondition, $locationIdentifier, $locationReferenceId, $territoriyIdentifier, $territoryReferenceId, $countryIdentifier, $countryReferenceId,$dateIdentifier, $possibleDateReferenceIds, $personIdentifier, $personReferenceIds){
        $finalDBManager = $this->finalDBManager;

        $sql = $baseQuery;

        $foundOne = false;
        $executeArray = array();
        $typeArray = array();

        if (count($locationReferenceId) > 0 && count($locationIdentifier) > 0) {
            $sql .= $this->buildQueryForIdentifier($locationIdentifier);
            $foundOne = true;
            
            for($i = 0; $i < count($locationIdentifier); $i++){
                $executeArray[] = $locationReferenceId;
                $typeArray[] = \Doctrine\DBAL\Connection::PARAM_INT_ARRAY;
            }
        }

        if (count($territoryReferenceId) > 0 && count($territoriyIdentifier) > 0) {
            if ($foundOne) {
                $sql .= $isAndCondition ? " AND " : " OR ";
            }
            $sql .= $this->buildQueryForIdentifier($territoriyIdentifier);
            $foundOne = true;
            
            for($i = 0; $i < count($territoriyIdentifier); $i++){
                $executeArray[] = $territoryReferenceId;
                $typeArray[] = \Doctrine\DBAL\Connection::PARAM_INT_ARRAY;
            }
        }

        if (count($countryReferenceId) > 0  && count($countryIdentifier) > 0) {
            if ($foundOne) {
                $sql .= $isAndCondition ? " AND " : " OR ";
            }
            $sql .= $this->buildQueryForIdentifier($countryIdentifier);
            $foundOne = true;
            
            for($i = 0; $i < count($countryIdentifier); $i++){
                $executeArray[] = $countryReferenceId;
                $typeArray[] = \Doctrine\DBAL\Connection::PARAM_INT_ARRAY;
            }
        }

        if (count($possibleDateReferenceIds) > 0 && count($dateIdentifier) > 0) {
            if ($foundOne) {
                $sql .= $isAndCondition ? " AND " : " OR ";
            }
            $sql .= $this->buildQueryForIdentifier($dateIdentifier);
            $foundOne = true;
            
            for($i = 0; $i < count($dateIdentifier); $i++){
                $executeArray[] = $possibleDateReferenceIds;
                $typeArray[] = \Doctrine\DBAL\Connection::PARAM_INT_ARRAY;
            }
        }
        
        if (count($personReferenceIds) > 0 && count($personIdentifier) > 0) {
            if ($foundOne) {
                $sql .= " AND ";
            }
            $sql .= $this->buildQueryForIdentifier($personIdentifier);
            $foundOne = true;
            
            for($i = 0; $i < count($personIdentifier); $i++){
                $executeArray[] = $personReferenceIds;
                $typeArray[] = \Doctrine\DBAL\Connection::PARAM_INT_ARRAY;
            }
        }

        $this->LOGGER->debug("Using query: " . $sql);
        
        $stmt = $finalDBManager->getConnection()->executeQuery($sql, $executeArray, $typeArray);

        return $this->extractIdArray($stmt->fetchAll(), $extractFieldName);
    }
    
    private function baseSearchInPersonWithInnerQuery($tableName, $fieldName, $baseInnerQuery,$isAndCondition, $locationIdentifier, $locationReferenceId, $territoriyIdentifier, $territoryReferenceId, $countryIdentifier, $countryReferenceId,$dateIdentifier, $possibleDateReferenceIds, $personIdentifier, $personReferenceIds){
        $finalDBManager = $this->finalDBManager;

        $sql = "SELECT DISTINCT id FROM ".$tableName." WHERE ".$fieldName." IN (".$baseInnerQuery;

        $foundOne = false;
        $executeArray = array();
        $typeArray = array();

        if (count($locationReferenceId) > 0 && count($locationIdentifier) > 0) {
            $sql .= $this->buildQueryForIdentifier($locationIdentifier);
            $foundOne = true;
            
            for($i = 0; $i < count($locationIdentifier); $i++){
                $executeArray[] = $locationReferenceId;
                $typeArray[] = \Doctrine\DBAL\Connection::PARAM_INT_ARRAY;
            }
        }

        if (count($territoryReferenceId) > 0 && count($territoriyIdentifier) > 0) {
            if ($foundOne) {
                $sql .= $isAndCondition ? " AND " : " OR ";
            }
            $sql .= $this->buildQueryForIdentifier($territoriyIdentifier);
            $foundOne = true;
            
            for($i = 0; $i < count($territoriyIdentifier); $i++){
                $executeArray[] = $territoryReferenceId;
                $typeArray[] = \Doctrine\DBAL\Connection::PARAM_INT_ARRAY;
            }
        }

        if (count($countryReferenceId) > 0  && count($countryIdentifier) > 0) {
            if ($foundOne) {
                $sql .= $isAndCondition ? " AND " : " OR ";
            }
            $sql .= $this->buildQueryForIdentifier($countryIdentifier);
            $foundOne = true;
            
            for($i = 0; $i < count($countryIdentifier); $i++){
                $executeArray[] = $countryReferenceId;
                $typeArray[] = \Doctrine\DBAL\Connection::PARAM_INT_ARRAY;
            }
        }

        if (count($possibleDateReferenceIds) > 0 && count($dateIdentifier) > 0) {
            if ($foundOne) {
                $sql .= $isAndCondition ? " AND " : " OR ";
            }
            $sql .= $this->buildQueryForIdentifier($dateIdentifier);
            $foundOne = true;
            
            for($i = 0; $i < count($dateIdentifier); $i++){
                $executeArray[] = $possibleDateReferenceIds;
                $typeArray[] = \Doctrine\DBAL\Connection::PARAM_INT_ARRAY;
            }
        }
        
        //close inner query at this point
        $sql .=")";
        
        if (count($personReferenceIds) > 0 && count($personIdentifier) > 0) {
            if ($foundOne) {
                $sql .= " AND ";
            }
            $sql .= $this->buildQueryForIdentifier($personIdentifier);
            $foundOne = true;
            
            for($i = 0; $i < count($personIdentifier); $i++){
                $executeArray[] = $personReferenceIds;
                $typeArray[] = \Doctrine\DBAL\Connection::PARAM_INT_ARRAY;
            }
        }

        $this->LOGGER->debug("Using query: " . $sql);
        
        $stmt = $finalDBManager->getConnection()->executeQuery($sql, $executeArray, $typeArray);

        return $this->extractIdArray($stmt->fetchAll());
    }
    
    private function buildQueryForIdentifier($identifierArray){
        if(count($identifierArray) == 1){
            return $identifierArray[0]." IN (?)";
        } else {
            $sql = "(";
            
            for($i = 0; $i < count($identifierArray); $i++){
                if($i > 0){
                    $sql .= " OR ";
                }
                $sql .= $identifierArray[$i]." IN (?)";
            }
            
            $sql .= ")";
            
            return $sql;
        }
    }
    
}

