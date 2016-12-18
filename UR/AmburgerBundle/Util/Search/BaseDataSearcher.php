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
        
        $sql = "SELECT id FROM location WHERE location_name LIKE :location";
        $stmt = $finalDBManager->getConnection()->prepare($sql);
        
        $stmt->bindValue('location', '%'.$location.'%');
        
        $stmt->execute();

        return $this->extractIdArray($stmt->fetchAll());
    }
        
    protected function findTerritory($territory){
        $finalDBManager = $this->finalDBManager;
        
        
        $sql = "SELECT id FROM territory WHERE territory_name LIKE :territory";
        $stmt = $finalDBManager->getConnection()->prepare($sql);
        
        $stmt->bindValue('territory', '%'.$territory.'%');

        $stmt->execute();

        return $this->extractIdArray($stmt->fetchAll());
    }
    
    protected function findCountry($country){
        $finalDBManager = $this->finalDBManager;
        
        
        $sql = "SELECT id FROM country WHERE country_name LIKE :country";
        $stmt = $finalDBManager->getConnection()->prepare($sql);

        $stmt->bindValue('country', '%'.$country.'%');

        $stmt->execute();

        return $this->extractIdArray($stmt->fetchAll());
    }
     
    protected function findNation($queryString){
        $finalDBManager = $this->finalDBManager;

        $sql = "SELECT id FROM nation WHERE nation_name LIKE :nation";
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
        $parts = explode(".", $date);
        
        if(count($parts) != 3){
            return array();
        }
        
        $day = !empty($parts[0]) ? $parts[0] : null;
        $month = !empty($parts[1]) ? $parts[1] : null;
        $year = !empty($parts[2]) ? $parts[2] : null;
        
        $finalDBManager = $this->finalDBManager;
        $sql = "";
        $stmt = null;
        
        if(!is_null($year)){
            if(!is_null($month)){
                if(!is_null($day)){
                    $sql = "SELECT id FROM date_information WHERE year_value = :year AND month_value = :month AND day_value = :day";
                    $stmt = $finalDBManager->getConnection()->prepare($sql);

                    $stmt->bindValue('year', $year);
                    $stmt->bindValue('month', $month);
                    $stmt->bindValue('day', $day);
                } else {
                    $sql = "SELECT id FROM date_information WHERE year_value = :year AND month_value = :month";
                    $stmt = $finalDBManager->getConnection()->prepare($sql);

                    $stmt->bindValue('year', $year);
                    $stmt->bindValue('month', $month);
                }
            } else {
                if(!is_null($day)){
                    $sql = "SELECT id FROM date_information WHERE year_value = :year AND day_value = :day";
                    $stmt = $finalDBManager->getConnection()->prepare($sql);

                    $stmt->bindValue('year', $year);
                    $stmt->bindValue('day', $day);
                } else {
                    $sql = "SELECT id FROM date_information WHERE year_value = :year";
                    $stmt = $finalDBManager->getConnection()->prepare($sql);

                    $stmt->bindValue('year', $year);
                }
            }
        } else {
            if(!is_null($month)){
                if(!is_null($day)){
                    $sql = "SELECT id FROM date_information WHERE month_value = :month AND day_value = :day";
                    $stmt = $finalDBManager->getConnection()->prepare($sql);

                    $stmt->bindValue('month', $month);
                    $stmt->bindValue('day', $day);
                } else {
                    $sql = "SELECT id FROM date_information WHERE month_value = :month";
                    $stmt = $finalDBManager->getConnection()->prepare($sql);

                    $stmt->bindValue('month', $month);
                }
            } else {
                if(!is_null($day)){
                    $sql = "SELECT id FROM date_information WHERE day_value = :day";
                    $stmt = $finalDBManager->getConnection()->prepare($sql);

                    $stmt->bindValue('day', $day);
                } else {
                   //should never happen
                }
            }
        }
        
        $this->LOGGER->debug("Using query: ".$sql);

        $stmt->execute();

        $dates = $this->extractIdArray($stmt->fetchAll());
        
        $this->finalDBManager->clear();
        
        return $dates;
    }
    
    protected function findDatesBasedOnDateRange($fromDate, $toDate){
        $fromParts = explode(".", $fromDate);
        $fromDay = !empty($fromParts[0]) ? $fromParts[0] : null;
        $fromMonth = !empty($fromParts[1]) ? $fromParts[1] : null;
        $fromYear = !empty($fromParts[2]) ? $fromParts[2] : null;
        
        $toParts = explode(".", $toDate);
        $toDay = !empty($toParts[0]) ? $toParts[0] : null;
        $toMonth = !empty($toParts[1]) ? $toParts[1] : null;
        $toYear = !empty($toParts[2]) ? $toParts[2] : null;
        
        if(count($fromParts) != 3 || count($toParts) != 3){
            return array();
        }
        
        $finalDBManager = $this->finalDBManager;
        
        
        $fromQueryPart = $this->buildFromDateQuery($fromYear, $fromMonth, $fromDay);
        $toQueryPart = $this->buildToDateQuery($toYear, $toMonth, $toDay);

        $sql = "";
        if(!empty($fromQueryPart) && !empty($toQueryPart)){
            $sql = "SELECT id FROM date_information WHERE ".$fromQueryPart. " AND ". $toQueryPart;
        } else if(!empty($fromQueryPart)){
            $sql = "SELECT id FROM date_information WHERE ".$fromQueryPart;
        } else if(!empty($toQueryPart)){
            $sql = "SELECT id FROM date_information WHERE ".$toQueryPart;
        }
        
        $this->LOGGER->debug("Using query: ".$sql);
        
        $stmt = $stmt = $finalDBManager->getConnection()->prepare($sql);
        
        if(!is_null($fromYear)){
            $stmt->bindValue('fromYear', $fromYear);
        }
        if(!is_null($fromMonth)){
            $stmt->bindValue('fromMonth', $fromMonth);
        }
        if(!is_null($fromDay)){
           $stmt->bindValue('fromDay', $fromDay);
        }
        if(!is_null($toYear)){
            $stmt->bindValue('toYear', $toYear);
        }
        if(!is_null($toMonth)){
            $stmt->bindValue('toMonth', $toMonth);
        }
        if(!is_null($toDay)){
            $stmt->bindValue('toDay', $toDay);
        }
        
        $stmt->execute();

        $dates = $this->extractIdArray($stmt->fetchAll());
        
        $this->finalDBManager->clear();
        
        return $dates;
    }
    
    private function buildFromDateQuery($fromYear, $fromMonth, $fromDay){
        if(!is_null($fromYear)){
            if(!is_null($fromMonth)){
                if(!is_null($fromDay)){
                    return "(year_value > :fromYear OR (year_value = :fromYear AND (month_value > :fromMonth OR (month_value = :fromMonth AND day_value >= :fromDay))))";
                } else {
                    return "(year_value > :fromYear OR (year_value = :fromYear AND month_value >= :fromMonth))";
                }
            } else {
                if(!is_null($fromDay)){
                    return "(year_value > :fromYear OR (year_value = :fromYear AND day_value >= :fromDay))";
                } else {
                    return "(year_value >= :fromYear )";
                }
            }
        } else {
            if(!is_null($fromMonth)){
                if(!is_null($fromDay)){
                    return "(month_value > :fromMonth OR (month_value = :fromMonth AND day_value >= :fromDay))";
                } else {
                    return "(month_value >= :fromMonth)";
                }
            } else {
                if(!is_null($fromDay)){
                   return "(day_value >= :fromDay)";
                } else {
                   return "";
                }
            }
        }
    }
    
    private function buildToDateQuery($toYear, $toMonth, $toDay){
        if(!is_null($toYear)){
            if(!is_null($toMonth)){
                if(!is_null($toDay)){
                    return "(year_value < :toYear OR (year_value = :toYear AND (month_value < :toMonth OR (month_value = :toMonth AND day_value <= :toDay))))";
                } else {
                    return "(year_value < :toYear OR (year_value = :toYear AND month_value <= :toMonth))";
                }
            } else {
                if(!is_null($toDay)){
                    return "(year_value < :toYear OR (year_value = :toYear AND day_value <= :toDay))";
                } else {
                    return "(year_value <= :toYear)";
                }
            }
        } else {
            if(!is_null($toMonth)){
                if(!is_null($toDay)){
                    return "(month_value < :toMonth OR (month_value = :toMonth AND day_value <= :toDay))";
                } else {
                    return "(month_value <= :toMonth)";
                }
            } else {
                if(!is_null($toDay)){
                   return "(day_value <= :toDay)";
                } else {
                   return "";
                }
            }
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
            
            $this->finalDBManager->clear();
        
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

        $persons = $this->extractIdArray($stmt->fetchAll());
        
        $this->finalDBManager->clear();
        
        return $persons;
    }
    
    protected function extractMainPersons($personIds){
        $finalDBManager = $this->finalDBManager;
        $sql = "SELECT id FROM person WHERE id IN (?)";
        
        $stmt = $finalDBManager->getConnection()->executeQuery($sql, array($personIds), array(\Doctrine\DBAL\Connection::PARAM_INT_ARRAY));

        $persons= $this->extractIdArray($stmt->fetchAll());
        
        $this->finalDBManager->clear();
        
        return $persons;
    }
    
    protected function checkAllPersonsByQueryString($onlyMainPersons, $queryString){
        if($onlyMainPersons){
            return $this->checkPersonByQueryString($queryString);
        } else {
            $searchIds = $this->checkPersonByQueryString($queryString);
            
            $searchIds = array_merge($searchIds, $this->checkRelativeByQueryString($queryString));
            
            $searchIds = array_merge($searchIds, $this->checkPartnerByQueryString($queryString));
            
            sort($searchIds, SORT_NUMERIC);

            return $searchIds;
        }
    }
    
    protected function checkPersonByQueryString($queryString) {
        return $this->checkBasePersonByQueryString("SELECT id FROM person WHERE ", $queryString);
    }
    
    protected function checkRelativeByQueryString($queryString) {
        return $this->checkBasePersonByQueryString("SELECT id FROM relative WHERE ", $queryString);
    }
    
    protected function checkPartnerByQueryString($queryString) {
        return $this->checkBasePersonByQueryString("SELECT id FROM partner WHERE ", $queryString);
    }
    
    protected function checkBasePersonByQueryString($baseQuery, $queryString) {
        $finalDBManager = $this->finalDBManager;
        
        $sql = $baseQuery. "last_name LIKE :queryString "
                . "OR first_name LIKE :queryString "
                . "OR patronym LIKE :queryString "
                . "OR birth_name LIKE :queryString "
                . "OR fore_name LIKE :queryString";
        
        $this->LOGGER->debug("Using query: ".$sql);
        
        $stmt = $finalDBManager->getConnection()->prepare($sql);

        $stmt->bindValue('queryString', '%'.$queryString.'%');
        
        $stmt->execute();

        $persons = $this->extractIdArray($stmt->fetchAll());
        
        $this->finalDBManager->clear();
        
        return $persons;
    }

    protected function searchInBaptism($onlyMainPerson, $isAndCondition,$location, $territory, $country, $date,$fromDate,$toDate, $personReferenceIds = array()) {
        if(empty($location) && empty($date) && empty($fromDate) && empty($toDate)){
            return array();
        }
        
        if(count($personReferenceIds) > 0){
            $possibleBaptismIds = $this->getFieldForPersonIds($onlyMainPerson, 'baptism_id', $personReferenceIds);
            
            $baseQuery = "SELECT id FROM baptism WHERE ";
            $baptismIds = $this->baseSearchWithoutPerson($baseQuery, 'id', $isAndCondition,
                    array('baptism_locationid'), $location, 
                    array(), $territory, 
                    array(), $country, 
                    array('baptism_dateid'),  $date,$fromDate,$toDate,
                    $possibleBaptismIds);

            if(count($baptismIds) == 0){
                return array();
            }
            
            $personIds = $this->searchPersonBasedOn('SELECT id FROM person WHERE baptism_id IN (?)', $baptismIds, $personReferenceIds);

            if(!$onlyMainPerson) {
                $relativeIds = $this->searchPersonBasedOn('SELECT id FROM relative WHERE baptism_id IN (?)', $baptismIds, $personReferenceIds);

                $personIds = array_merge($personIds, $relativeIds);

                $partnerIds = $this->searchPersonBasedOn('SELECT id FROM partner WHERE baptism_id IN (?)', $baptismIds, $personReferenceIds);

                $personIds = array_merge($personIds, $partnerIds);
            }
            
        } else {
            $baseQuery = "SELECT id FROM baptism WHERE ";
            $baptismIds = $this->baseSearchWithoutPerson($baseQuery, 'id', $isAndCondition,
                    array('baptism_locationid'), $location, 
                    array(), $territory, 
                    array(), $country, 
                    array('baptism_dateid'), $date,$fromDate,$toDate);

            if(count($baptismIds) == 0){
                return array();
            }

            $personIds = $this->searchPersonBasedOn('SELECT id FROM person WHERE baptism_id IN (?)', $baptismIds, $personReferenceIds);

            if(!$onlyMainPerson) {
                $relativeIds = $this->searchPersonBasedOn('SELECT id FROM relative WHERE baptism_id IN (?)', $baptismIds, $personReferenceIds);

                $personIds = array_merge($personIds, $relativeIds);

                $partnerIds = $this->searchPersonBasedOn('SELECT id FROM partner WHERE baptism_id IN (?)', $baptismIds, $personReferenceIds);

                $personIds = array_merge($personIds, $partnerIds);
            }
        }
        
        $this->finalDBManager->clear();

        return $personIds;
    }
    
    protected function searchInBirth($onlyMainPerson,$isAndCondition,$location, $territory, $country, $date,$fromDate,$toDate, $personReferenceIds = array()) {

        if(count($personReferenceIds) > 0){
            $possibleBirthIds = $this->getFieldForPersonIds($onlyMainPerson, 'birth_id', $personReferenceIds);
            
            $baseQuery = "SELECT id FROM birth WHERE ";
            $birthIds = $this->baseSearchWithoutPerson($baseQuery, 'id', $isAndCondition,
                    array('birth_locationid', 'origin_locationid'), $location, 
                    array('birth_territoryid','origin_territoryid'), $territory, 
                    array('birth_countryid','origin_countryid'), $country, 
                    array('birth_dateid', 'proven_dateid'), $date,$fromDate,$toDate,
                    $possibleBirthIds);

            if(count($birthIds) == 0){
                return array();
            }
            
            $personIds = $this->searchPersonBasedOn('SELECT id FROM person WHERE birth_id IN (?)', $birthIds, $personReferenceIds);

            if(!$onlyMainPerson) {
                $relativeIds = $this->searchPersonBasedOn('SELECT id FROM relative WHERE birth_id IN (?)', $birthIds, $personReferenceIds);

                $personIds = array_merge($personIds, $relativeIds);

                $partnerIds = $this->searchPersonBasedOn('SELECT id FROM partner WHERE birth_id IN (?)', $birthIds, $personReferenceIds);

                $personIds = array_merge($personIds, $partnerIds);
            }
            
        } else {
            $baseQuery = "SELECT id FROM birth WHERE ";
            $birthIds = $this->baseSearchWithoutPerson($baseQuery, 'id', $isAndCondition,
                    array('birth_locationid', 'origin_locationid'), $location, 
                    array('birth_territoryid','origin_territoryid'), $territory, 
                    array('birth_countryid','origin_countryid'), $country, 
                    array('birth_dateid', 'proven_dateid'), $date,$fromDate,$toDate);


            if(count($birthIds) == 0){
                return array();
            }


            $personIds = $this->searchPersonBasedOn('SELECT id FROM person WHERE birth_id IN (?)', $birthIds, $personReferenceIds);

            if(!$onlyMainPerson) {
                $relativeIds = $this->searchPersonBasedOn('SELECT id FROM relative WHERE birth_id IN (?)', $birthIds, $personReferenceIds);

                $personIds = array_merge($personIds, $relativeIds);

                $partnerIds = $this->searchPersonBasedOn('SELECT id FROM partner WHERE birth_id IN (?)', $birthIds, $personReferenceIds);

                $personIds = array_merge($personIds, $partnerIds);
            }
        }
        
        $this->finalDBManager->clear();

        return $personIds;

    }
    
    protected function searchInDeath($onlyMainPerson,$isAndCondition,$location, $territory, $country, $date,$fromDate,$toDate, $personReferenceIds = array()) {
              
        if(count($personReferenceIds) > 0){
            $possibleDeathIds = $this->getFieldForPersonIds($onlyMainPerson, 'death_id', $personReferenceIds);
            
            $baseQuery = "SELECT id FROM death WHERE ";
            $deathIds = $this->baseSearchWithoutPerson($baseQuery, 'id', $isAndCondition,
                    array('funeral_locationid', 'death_locationid'), $location, 
                    array('territory_of_deathid'), $territory, 
                    array('death_countryid'), $country, 
                    array('funeral_dateid', 'death_dateid'), $date,$fromDate,$toDate,
                    $possibleDeathIds);

            if(count($deathIds) == 0){
                return array();
            }
            
            $personIds = $this->searchPersonBasedOn('SELECT id FROM person WHERE death_id IN (?)', $deathIds, $personReferenceIds);

            if(!$onlyMainPerson) {
                $relativeIds = $this->searchPersonBasedOn('SELECT id FROM relative WHERE death_id IN (?)', $deathIds, $personReferenceIds);

                $personIds = array_merge($personIds, $relativeIds);

                $partnerIds = $this->searchPersonBasedOn('SELECT id FROM partner WHERE death_id IN (?)', $deathIds, $personReferenceIds);

                $personIds = array_merge($personIds, $partnerIds);
            }
            
        } else {
            $baseQuery = "SELECT id FROM death WHERE ";
            $deathIds = $this->baseSearchWithoutPerson($baseQuery, 'id', $isAndCondition,
                    array('funeral_locationid', 'death_locationid'), $location, 
                    array('territory_of_deathid'), $territory, 
                    array('death_countryid'), $country, 
                    array('funeral_dateid', 'death_dateid'), $date,$fromDate,$toDate);


            if(count($deathIds) == 0){
                return array();
            }


            $personIds = $this->searchPersonBasedOn('SELECT id FROM person WHERE death_id IN (?)', $deathIds, $personReferenceIds);

            if(!$onlyMainPerson) {
                $relativeIds = $this->searchPersonBasedOn('SELECT id FROM relative WHERE death_id IN (?)', $deathIds, $personReferenceIds);

                $personIds = array_merge($personIds, $relativeIds);

                $partnerIds = $this->searchPersonBasedOn('SELECT id FROM partner WHERE death_id IN (?)', $deathIds, $personReferenceIds);

                $personIds = array_merge($personIds, $partnerIds);
            }
        }

        $this->finalDBManager->clear();

        return $personIds;
    }
    
    protected function searchForJobInPerson($onlyMainPerson,$job){
        
        $finalDBManager = $this->finalDBManager;

        $sql = "SELECT id FROM person WHERE jobid IN (SELECT id FROM job WHERE label LIKE :job)";
        $stmt = $finalDBManager->getConnection()->prepare($sql);
        
        $stmt->bindValue('job', $job);

        $stmt->execute();

        $personIds = $this->extractIdArray($stmt->fetchAll());
        if(!$onlyMainPerson) {
            $sql = "SELECT id FROM relative WHERE jobid IN (SELECT id FROM job WHERE label LIKE :job)";
            $stmt = $finalDBManager->getConnection()->prepare($sql);

            $stmt->bindValue('job', $job);

            $stmt->execute();

            $relativeIds = $this->extractIdArray($stmt->fetchAll());
        
            $personIds = array_merge($personIds, $relativeIds);

            $sql = "SELECT id FROM partner WHERE jobid IN (SELECT id FROM job WHERE label LIKE :job)";
            $stmt = $finalDBManager->getConnection()->prepare($sql);

            $stmt->bindValue('job', $job);

            $stmt->execute();

            $partnerIds = $this->extractIdArray($stmt->fetchAll());

            $personIds = array_merge($personIds, $partnerIds);
        }
        
        $this->finalDBManager->clear();

        return $personIds;
    }
    
    protected function searchForJobInRoadOfLife($onlyMainPerson,$job){
        $finalDBManager = $this->finalDBManager;

        $sql = "SELECT person_id FROM road_of_life WHERE jobid IN (SELECT id FROM job WHERE label LIKE :job)";
        $stmt = $finalDBManager->getConnection()->prepare($sql);
        
        $stmt->bindValue('job', $job);

        $stmt->execute();

        $jobs = $this->extractIdArray($stmt->fetchAll(), 'person_id');
        
        $this->finalDBManager->clear();
        
        return $jobs;
    }
    
    protected function searchForJobClassInPerson($onlyMainPerson,$jobClass){
        $finalDBManager = $this->finalDBManager;

        $sql = "SELECT id FROM person WHERE job_classid IN (SELECT id FROM job_class WHERE label LIKE :jobClass)";
        $stmt = $finalDBManager->getConnection()->prepare($sql);
        
        $stmt->bindValue('jobClass', $jobClass);

        $stmt->execute();

        $personIds = $this->extractIdArray($stmt->fetchAll());
        
        if(!$onlyMainPerson) {
            $sql = "SELECT id FROM relative WHERE job_classid IN (SELECT id FROM job_class WHERE label LIKE :jobClass)";
            $stmt = $finalDBManager->getConnection()->prepare($sql);

            $stmt->bindValue('jobClass', $jobClass);

            $stmt->execute();

            $relativeIds = $this->extractIdArray($stmt->fetchAll());
        
            $personIds = array_merge($personIds, $relativeIds);

            $sql = "SELECT id FROM partner WHERE job_classid IN (SELECT id FROM job_class WHERE label LIKE :jobClass)";
            $stmt = $finalDBManager->getConnection()->prepare($sql);

            $stmt->bindValue('jobClass', $jobClass);

            $stmt->execute();

            $partnerIds = $this->extractIdArray($stmt->fetchAll());

            $personIds = array_merge($personIds, $partnerIds);
        }

        
        $this->finalDBManager->clear();
        
        return $personIds;
    }
    
    protected function searchForNationInPerson($onlyMainPerson,$nation){
        $finalDBManager = $this->finalDBManager;

        $sql = "SELECT id FROM person WHERE nationid IN (SELECT id FROM nation WHERE nation_name LIKE :nation)";
        $stmt = $finalDBManager->getConnection()->prepare($sql);
        
        $stmt->bindValue('nation', $nation);

        $stmt->execute();

        $personIds = $this->extractIdArray($stmt->fetchAll());
        
        if(!$onlyMainPerson) {
            $sql = "SELECT id FROM relative WHERE nationid IN (SELECT id FROM nation WHERE nation_name LIKE :nation)";
            $stmt = $finalDBManager->getConnection()->prepare($sql);

            $stmt->bindValue('nation', $nation);

            $stmt->execute();

            $relativeIds = $this->extractIdArray($stmt->fetchAll());
        
            $personIds = array_merge($personIds, $relativeIds);

            $sql = "SELECT id FROM partner WHERE nationid IN (SELECT id FROM nation WHERE nation_name LIKE :nation)";
            $stmt = $finalDBManager->getConnection()->prepare($sql);

            $stmt->bindValue('nation', $nation);

            $stmt->execute();

            $partnerIds = $this->extractIdArray($stmt->fetchAll());

            $personIds = array_merge($personIds, $partnerIds);
        }

        $this->finalDBManager->clear();
        
        return $personIds;
    }
    
    protected function searchInEducations($isAndCondition,$location, $territory, $country, $date,$fromDate,$toDate, $personReferenceIds = array()) {
        $sql = "SELECT person_id FROM education WHERE ";
        
        return $this->baseSearchForPerson($sql, 'person_id', $isAndCondition,
                array('locationid', 'graduation_locationid'), $location, 
                array('territoryid'), $territory, 
                array('countryid'), $country, 
                array('from_dateid', 'to_dateid', 'proven_dateid', 'graduation_dateid'), $date,$fromDate,$toDate,
                array('person_id'), $personReferenceIds);
    }
    
    protected function searchInHonours($isAndCondition,$location, $territory, $country, $date,$fromDate,$toDate, $personReferenceIds = array()) {
        $sql = "SELECT person_id FROM honour WHERE ";
        
        return $this->baseSearchForPerson($sql, 'person_id', $isAndCondition,
                array('locationid'), $location, 
                array('territoryid'), $territory, 
                array('countryid'), $country, 
                array('from_dateid', 'to_dateid', 'proven_dateid'), $date,$fromDate,$toDate,
                array('person_id'), $personReferenceIds);
    }

    protected function searchInProperties($isAndCondition,$location, $territory, $country, $date,$fromDate,$toDate, $personReferenceIds = array()) {
        $sql = "SELECT person_id FROM property WHERE ";
        
        return $this->baseSearchForPerson($sql, 'person_id', $isAndCondition,
                array('locationid'), $location, 
                array('territoryid'), $territory, 
                array('countryid'), $country, 
                array('from_dateid', 'to_dateid', 'proven_dateid'), $date,$fromDate,$toDate,
                array('person_id'), $personReferenceIds);
    }
    
    protected function searchInRanks($isAndCondition,$location, $territory, $country, $date,$fromDate,$toDate, $personReferenceIds = array()) {
        $sql = "SELECT person_id FROM rank WHERE ";
        
        return $this->baseSearchForPerson($sql, 'person_id',$isAndCondition, 
                array('locationid'), $location, 
                array('territoryid'), $territory, 
                array('countryid'), $country, 
                array('from_dateid', 'to_dateid', 'proven_dateid'),  $date,$fromDate,$toDate,
                array('person_id'), $personReferenceIds);
    }
    
    protected function searchInReligions($isAndCondition,$location, $territory, $country, $date,$fromDate,$toDate, $personReferenceIds = array()) {
        if(empty($date) && empty($fromDate) && empty($toDate)){
            return array();
        }
        
        $sql = "SELECT person_id FROM religion WHERE ";
        
        return $this->baseSearchForPerson($sql, 'person_id', $isAndCondition,
                array(), $location, 
                array(), $territory, 
                array(), $country, 
                array('from_dateid', 'proven_dateid'),  $date,$fromDate,$toDate,
                array('person_id'), $personReferenceIds);
    }
    
    
    protected function searchInResidence($isAndCondition,$location, $territory, $country, $date,$fromDate,$toDate, $personReferenceIds = array()) {
        if(empty($location) && empty($territory) && empty($country)){
            return array();
        }
        
        $sql = "SELECT person_id FROM residence WHERE ";
        
        return $this->baseSearchForPerson($sql, 'person_id', $isAndCondition,
                array('residence_locationid'), $location, 
                array('residence_territoryid'), $territory, 
                array('residence_countryid'), $country, 
                array(),  $date,$fromDate,$toDate,
                array('person_id'), $personReferenceIds);
    }
    
    protected function searchInRoadOfLife($isAndCondition,$location, $territory, $country, $date,$fromDate,$toDate, $personReferenceIds = array()) {
        $sql = "SELECT person_id FROM road_of_life WHERE ";
        
        return $this->baseSearchForPerson($sql, 'person_id', $isAndCondition,
                array('locationid'), $location, 
                array('origin_territoryid', 'territoryid'), $territory, 
                array('origin_countryid','countryid'), $country, 
                array('from_dateid', 'to_dateid', 'proven_dateid'), $date,$fromDate,$toDate,
                array('person_id'), $personReferenceIds);
    }
    
    protected function searchInStatus($isAndCondition,$location, $territory, $country, $date,$fromDate,$toDate, $personReferenceIds = array()) {
        $sql = "SELECT person_id FROM status_information WHERE ";
        
        return $this->baseSearchForPerson($sql, 'person_id', $isAndCondition,
                array('locationid'), $location, 
                array('territoryid'), $territory, 
                array('countryid'), $country, 
                array('from_dateid', 'to_dateid', 'proven_dateid'), $date,$fromDate,$toDate,
                array('person_id'), $personReferenceIds);
    }
    
    protected function searchInWorks($isAndCondition,$location, $territory, $country, $date,$fromDate,$toDate, $personReferenceIds = array()) {
        $sql = "SELECT person_id FROM works WHERE ";
        
        return $this->baseSearchForPerson($sql, 'person_id', $isAndCondition,
                array('locationid'), $location, 
                array('territoryid'), $territory, 
                array('countryid'), $country, 
                array('from_dateid', 'to_dateid', 'proven_dateid'), $date,$fromDate,$toDate,
                array('person_id'), $personReferenceIds);
    }
    
    protected function searchInWedding($isAndCondition,$location, $territory, $country, $date,$fromDate,$toDate, $personReferenceIds = array()) {
         if(empty($location) && empty($territory) && empty($date) && empty($fromDate) && empty($toDate)){
            return array();
        }
        
        $sql = "SELECT id FROM wedding WHERE ";
        
        $weddingIds =  $this->baseSearchForPerson($sql, 'id', $isAndCondition,
                array('wedding_locationid'), $location, 
                array('wedding_territoryid'), $territory, 
                array(), $country, 
                array('wedding_dateID', 'banns_dateID', 'breakup_dateID', 'proven_dateID'), $date,$fromDate,$toDate,
                array('husband_ID', 'wife_ID'), $personReferenceIds);
        
        
        if(count($weddingIds) == 0){
            return array();
        }
        
        $this->LOGGER->debug("Found " . count($weddingIds). " matching wedding Ids.");
        
        $stmt = $this->finalDBManager->getConnection()->executeQuery('SELECT husband_ID, wife_ID FROM wedding WHERE id IN (?)', 
                array($weddingIds), array(\Doctrine\DBAL\Connection::PARAM_INT_ARRAY));

        $weddings = $stmt->fetchAll();
        
        $personIds = array();
        
        if(count($personReferenceIds) > 0){
            for($i = 0; $i < count($weddings); $i++){
                if(!is_null($weddings[$i]['husband_ID']) && in_array($weddings[$i]['husband_ID'], $personReferenceIds)){
                   $personIds[] = $weddings[$i]['husband_ID']; 
                }

                if(!is_null($weddings[$i]['wife_ID']) && in_array($weddings[$i]['wife_ID'], $personReferenceIds)){
                    $personIds[] = $weddings[$i]['wife_ID'];
                }
            }
        } else {
            for($i = 0; $i < count($weddings); $i++){
                if(!is_null($weddings[$i]['husband_ID'])){
                   $personIds[] = $weddings[$i]['husband_ID']; 
                }

                if(!is_null($weddings[$i]['wife_ID'])){
                    $personIds[] = $weddings[$i]['wife_ID'];
                }
            }
        }
        
        $this->finalDBManager->clear();
        
        return $personIds;
    }
    
    private function baseSearchForPerson($baseQuery,$extractFieldName,$isAndCondition, $locationIdentifier, $location, $territoryIdentifier, $territory, $countryIdentifier, $country,$dateIdentifier, $date,$fromDate,$toDate, $personIdentifier, $personReferenceIds){
        $finalDBManager = $this->finalDBManager;

        $sql = $baseQuery;

        $foundOne = false;
        $executeArray = array();
        $typeArray = array();

        $this->LOGGER->debug("Number of PersonReferenceIds: ".count($personReferenceIds). " and number of personIdentifiers: ".count($personIdentifier));
        $this->LOGGER->debug("Location: ".$location. " and number of locationIdentifiers: ".count($locationIdentifier));
        $this->LOGGER->debug("Territory: ".$territory. " and number of territoryIdentifiers: ".count($territoryIdentifier));
        $this->LOGGER->debug("Country: ".$country. " and number of countryIdentifiers: ".count($countryIdentifier));
        $this->LOGGER->debug("Date: ".$date."/".$fromDate."-".$toDate. " and number of dateIdentifiers: ".count($dateIdentifier));

        if (count($personReferenceIds) > 0 && count($personIdentifier) > 0) {
            $this->LOGGER->debug("Adding selection on persons to the query");
            $sql .= $this->buildQueryForIdentifier($personIdentifier);
            
            for($i = 0; $i < count($personIdentifier); $i++){
                $executeArray[] = $personReferenceIds;
                $typeArray[] = \Doctrine\DBAL\Connection::PARAM_INT_ARRAY;
            }
            
            $sql .= " AND ";
        }

        if (!empty($location) && count($locationIdentifier) > 0) {
            $sql .= $this->buildLocationQueryForIdentifier($locationIdentifier);
            $foundOne = true;
            
            for($i = 0; $i < count($locationIdentifier); $i++){
                $executeArray[] = $location;
                $typeArray[] = \PDO::PARAM_STR;
            }
        }

        if (!empty($territory) && count($territoryIdentifier) > 0) {
            if ($foundOne) {
                $sql .= $isAndCondition ? " AND " : " OR ";
            }
            $sql .= $this->buildTerritoryQueryForIdentifier($territoryIdentifier);
            $foundOne = true;
            
            for($i = 0; $i < count($territoryIdentifier); $i++){
                $executeArray[] = $territory;
                $typeArray[] = \PDO::PARAM_STR;
            }
        }

        if (!empty($country)  && count($countryIdentifier) > 0) {
            if ($foundOne) {
                $sql .= $isAndCondition ? " AND " : " OR ";
            }
            $sql .= $this->buildCountryQueryForIdentifier($countryIdentifier);
            $foundOne = true;
            
            for($i = 0; $i < count($countryIdentifier); $i++){
                $executeArray[] = $country;
                $typeArray[] = \PDO::PARAM_STR;
            }
        }

        if ((!empty($date) || !empty($fromDate) || !empty($toDate)) && count($dateIdentifier) > 0) {
            $dateSql = $this->buildDateQueryForIdentifier($dateIdentifier, $date, $fromDate, $toDate);
            
            if($dateSql != ""){
                if ($foundOne) {
                    $sql .= $isAndCondition ? " AND " : " OR ";
                }
                $sql .= $dateSql;
            }
           
        }

        $this->LOGGER->debug("Using query: " . $sql);
        
        if($baseQuery == $sql){
            $this->LOGGER->info("Skipping this query");
            return array();
        }
        
        $stmt = $finalDBManager->getConnection()->executeQuery($sql, $executeArray, $typeArray);

        $result = $this->extractIdArray($stmt->fetchAll(), $extractFieldName);
        
        $this->finalDBManager->clear();
        
        return $result;
    }
    
    private function searchPersonBasedOn($baseQuery, $referenceIds, $personReferenceIds = array()){
        $finalDBManager = $this->finalDBManager;

        $sql = $baseQuery;
        $executeArray = array();
        $typeArray = array();

        $executeArray[] = $referenceIds;
        $typeArray[] = \Doctrine\DBAL\Connection::PARAM_INT_ARRAY;

        
         $this->LOGGER->debug("Number of PersonReferenceIds: ".count($personReferenceIds));
        
        if (count($personReferenceIds) > 0) {
            $this->LOGGER->debug("Adding selection on persons to the query");
            $sql .= " AND id IN (?)";
            
            $executeArray[] = $personReferenceIds;
            $typeArray[] = \Doctrine\DBAL\Connection::PARAM_INT_ARRAY;
        }
        
        $this->LOGGER->debug("Using query: " . $sql);
        
        $stmt = $finalDBManager->getConnection()->executeQuery($sql, $executeArray, $typeArray);

        $result = $this->extractIdArray($stmt->fetchAll());
        
        $this->finalDBManager->clear();
        
        return $result;
    }

    private function baseSearchWithoutPerson($baseQuery,$extractFieldName,$isAndCondition, $locationIdentifier, $location, $territoryIdentifier, $territory, $countryIdentifier, $country,$dateIdentifier, $date,$fromDate,$toDate, $possibleIds = array()){
        $finalDBManager = $this->finalDBManager;

        $sql = $baseQuery;

        $foundOne = false;
        $executeArray = array();
        $typeArray = array();
        
        if (count($possibleIds) > 0) {
            $sql .= "id IN (?)";

            $executeArray[] = $possibleIds;
            $typeArray[] = \Doctrine\DBAL\Connection::PARAM_INT_ARRAY;
            
            $sql .= " AND ";
        }

        if (!empty($location) && count($locationIdentifier) > 0) {
            $sql .= $this->buildLocationQueryForIdentifier($locationIdentifier);
            $foundOne = true;
            
            for($i = 0; $i < count($locationIdentifier); $i++){
                $executeArray[] = $location;
                $typeArray[] = \PDO::PARAM_STR;
            }
        }

        if (!empty($territory) && count($territoryIdentifier) > 0) {
            if ($foundOne) {
                $sql .= $isAndCondition ? " AND " : " OR ";
            }
            $sql .= $this->buildTerritoryQueryForIdentifier($territoryIdentifier);
            $foundOne = true;
            
            for($i = 0; $i < count($territoryIdentifier); $i++){
                $executeArray[] = $territory;
                $typeArray[] = \PDO::PARAM_STR;
            }
        }

        if (!empty($country)  && count($countryIdentifier) > 0) {
            if ($foundOne) {
                $sql .= $isAndCondition ? " AND " : " OR ";
            }
            $sql .= $this->buildCountryQueryForIdentifier($countryIdentifier);
            $foundOne = true;
            
            for($i = 0; $i < count($countryIdentifier); $i++){
                $executeArray[] = $country;
                $typeArray[] = \PDO::PARAM_STR;
            }
        }

        if ((!empty($date) || !empty($fromDate) || !empty($toDate)) && count($dateIdentifier) > 0) {
            $dateSql = $this->buildDateQueryForIdentifier($dateIdentifier, $date, $fromDate, $toDate);
            
            if($dateSql != ""){
                if ($foundOne) {
                    $sql .= $isAndCondition ? " AND " : " OR ";
                }
                $sql .= $dateSql;
            }
           
        }

        $this->LOGGER->debug("Using query: " . $sql);
        
        if($baseQuery == $sql){
            $this->LOGGER->info("Skipping this query");
            return array();
        }
        
        $stmt = $finalDBManager->getConnection()->executeQuery($sql, $executeArray, $typeArray);

        $result = $this->extractIdArray($stmt->fetchAll(), $extractFieldName);
        
        $this->finalDBManager->clear();
        
        return $result;
    }
    
    private function buildQueryForIdentifier($identifierArray){
        $sql = null;
        if(count($identifierArray) == 1){
            $sql = $identifierArray[0]." IN (?)";
        } else {
            $sql = "(";
            
            for($i = 0; $i < count($identifierArray); $i++){
                if($i > 0){
                    $sql .= " OR ";
                }
                $sql .= $identifierArray[$i]." IN (?)";
            }
            
            $sql .= ")";
        }
        
        $this->LOGGER->debug("Using identifierSQL: '".$sql."'");
        
        return $sql;
    }
    
    private function buildLocationQueryForIdentifier($identifierArray){
        $sql = null;
        if(count($identifierArray) == 1){
            $sql = $identifierArray[0]." IN (SELECT id FROM location WHERE location_name LIKE ?)";
        } else {
            $sql = "(";
            
            for($i = 0; $i < count($identifierArray); $i++){
                if($i > 0){
                    $sql .= " OR ";
                }
                $sql .= $identifierArray[$i]." IN (SELECT id FROM location WHERE location_name LIKE ?)";
            }
            
            $sql .= ")";
        }
        
        $this->LOGGER->debug("Using identifierSQL: '".$sql."'");
        
        return $sql;
    }
    
    private function buildTerritoryQueryForIdentifier($identifierArray){
        $sql = null;
        if(count($identifierArray) == 1){
            $sql = $identifierArray[0]." IN (SELECT id FROM territory WHERE territory_name LIKE ?)";
        } else {
            $sql = "(";
            
            for($i = 0; $i < count($identifierArray); $i++){
                if($i > 0){
                    $sql .= " OR ";
                }
                $sql .= $identifierArray[$i]." IN (SELECT id FROM territory WHERE territory_name LIKE ?)";
            }
            
            $sql .= ")";
        }
        
        $this->LOGGER->debug("Using identifierSQL: '".$sql."'");
        
        return $sql;
    }
    
    private function buildCountryQueryForIdentifier($identifierArray){
        $sql = null;
        if(count($identifierArray) == 1){
            $sql = $identifierArray[0]." IN (SELECT id FROM country WHERE country_name LIKE ?)";
        } else {
            $sql = "(";
            
            for($i = 0; $i < count($identifierArray); $i++){
                if($i > 0){
                    $sql .= " OR ";
                }
                $sql .= $identifierArray[$i]." IN (SELECT id FROM country WHERE country_name LIKE ?)";
            }
            
            $sql .= ")";
        }
        
        $this->LOGGER->debug("Using identifierSQL: '".$sql."'");
        
        return $sql;
    }
    
    private function buildDateQueryForIdentifier($identifierArray, $date, $fromDate, $toDate){
        
        $dateQuery = $this->buildInternalQueryForDate($date, $fromDate, $toDate);
        
        $this->LOGGER->debug("Using internalDateQuery: '".$dateQuery."'");
        
        if($dateQuery == ""){
            return "";
        }
        
        $sql = null;
        if(count($identifierArray) == 1){
            $sql = $identifierArray[0]." IN (".$dateQuery.")";
        } else {
            $sql = "(";
            
            for($i = 0; $i < count($identifierArray); $i++){
                if($i > 0){
                    $sql .= " OR ";
                }
                $sql .= $identifierArray[$i]." IN (".$dateQuery.")";
            }
            
            $sql .= ")";
        }
        
        $this->LOGGER->debug("Using identifierSQL: '".$sql."'");
        
        return $sql;
    }
    
    private function buildInternalQueryForDate($date, $fromDate, $toDate){
        if (!empty($date)) {
            return $this->buildInternalQueryBasedOnDate($date);
        } else if (!empty($fromDate) && !empty($toDate)) {
            return $this->buildInternalQueryOnDateRange($fromDate, $toDate);
        }
        
        return "";
    }
    
    protected function buildInternalQueryBasedOnDate($date){
        $parts = explode(".", $date);
        
        if(count($parts) != 3){
            return "";
        }
        
        $day = !empty($parts[0]) ? $parts[0] : null;
        $month = !empty($parts[1]) ? $parts[1] : null;
        $year = !empty($parts[2]) ? $parts[2] : null;
        
        if(!is_null($year)){
            if(!is_null($month)){
                if(!is_null($day)){
                    return "SELECT id FROM date_information WHERE year_value = ".$year." AND month_value = ".$month." AND day_value = ".$day;
                } else {
                    return "SELECT id FROM date_information WHERE year_value = ".$year." AND month_value = ".$month."";
                }
            } else {
                if(!is_null($day)){
                    return "SELECT id FROM date_information WHERE year_value = ".$year." AND day_value = ".$day;
                } else {
                    return "SELECT id FROM date_information WHERE year_value = ".$year;
                }
            }
        } else {
            if(!is_null($month)){
                if(!is_null($day)){
                    return "SELECT id FROM date_information WHERE month_value = ".$month." AND day_value = ".$day;
                } else {
                    return "SELECT id FROM date_information WHERE month_value = ".$month;

                }
            } else {
                if(!is_null($day)){
                    return "SELECT id FROM date_information WHERE day_value = ".$day;
                } else {
                   //should never happen
                }
            }
        }
        
        return "";
    }
    
    protected function buildInternalQueryOnDateRange($fromDate, $toDate){
        $fromParts = explode(".", $fromDate);
        $fromDay = !empty($fromParts[0]) ? $fromParts[0] : null;
        $fromMonth = !empty($fromParts[1]) ? $fromParts[1] : null;
        $fromYear = !empty($fromParts[2]) ? $fromParts[2] : null;
        
        $toParts = explode(".", $toDate);
        $toDay = !empty($toParts[0]) ? $toParts[0] : null;
        $toMonth = !empty($toParts[1]) ? $toParts[1] : null;
        $toYear = !empty($toParts[2]) ? $toParts[2] : null;
        
        if(count($fromParts) != 3 || count($toParts) != 3){
            return "";
        }
        
        $fromQueryPart = $this->buildInternalFromDateQuery($fromYear, $fromMonth, $fromDay);
        $toQueryPart = $this->buildInternalToDateQuery($toYear, $toMonth, $toDay);

        if(!empty($fromQueryPart) && !empty($toQueryPart)){
            return "SELECT id FROM date_information WHERE ".$fromQueryPart. " AND ". $toQueryPart;
        } else if(!empty($fromQueryPart)){
            return "SELECT id FROM date_information WHERE ".$fromQueryPart;
        } else if(!empty($toQueryPart)){
            return "SELECT id FROM date_information WHERE ".$toQueryPart;
        }
        
        return "";
    }
    
    private function buildInternalFromDateQuery($fromYear, $fromMonth, $fromDay){
        if(!is_null($fromYear)){
            if(!is_null($fromMonth)){
                if(!is_null($fromDay)){
                    return "(year_value > ".$fromYear." OR (year_value = ".$fromYear." AND (month_value > ".$fromMonth." OR (month_value = ".$fromMonth." AND day_value >= ".$fromDay."))))";
                } else {
                    return "(year_value > ".$fromYear." OR (year_value = ".$fromYear." AND month_value >= ".$fromMonth."))";
                }
            } else {
                if(!is_null($fromDay)){
                    return "(year_value > ".$fromYear." OR (year_value = ".$fromYear." AND day_value >= ".$fromDay."))";
                } else {
                    return "(year_value >= ".$fromYear.")";
                }
            }
        } else {
            if(!is_null($fromMonth)){
                if(!is_null($fromDay)){
                    return "(month_value > ".$fromMonth." OR (month_value = ".$fromMonth." AND day_value >= ".$fromDay."))";
                } else {
                    return "(month_value >= ".$fromMonth.")";
                }
            } else {
                if(!is_null($fromDay)){
                   return "(day_value >= ".$fromDay.")";
                } else {
                   return "";
                }
            }
        }
    }
    
    private function buildInternalToDateQuery($toYear, $toMonth, $toDay){
        if(!is_null($toYear)){
            if(!is_null($toMonth)){
                if(!is_null($toDay)){
                    return "(year_value < ".$toYear." OR (year_value = ".$toYear." AND (month_value < ".$toMonth." OR (month_value = ".$toMonth." AND day_value <= ".$toDay."))))";
                } else {
                    return "(year_value < ".$toYear." OR (year_value = ".$toYear." AND month_value <= ".$toMonth."))";
                }
            } else {
                if(!is_null($toDay)){
                    return "(year_value < ".$toYear." OR (year_value = ".$toYear." AND day_value <= ".$toDay."))";
                } else {
                    return "(year_value <= ".$toYear.")";
                }
            }
        } else {
            if(!is_null($toMonth)){
                if(!is_null($toDay)){
                    return "(month_value < ".$toMonth." OR (month_value = ".$toMonth." AND day_value <= ".$toDay."))";
                } else {
                    return "(month_value <= ".$toMonth.")";
                }
            } else {
                if(!is_null($toDay)){
                   return "(day_value <= ".$toDay.")";
                } else {
                   return "";
                }
            }
        }
    }
    
    private function getFieldForPersonIds($onlyMainPersons, $field,$referencePersonIds){
        if(count($referencePersonIds) < 0 || $field == ""){
            return array();
        }

        $executeArray = array($referencePersonIds);
        $typeArray = array(\Doctrine\DBAL\Connection::PARAM_INT_ARRAY);
        
        $sql = "SELECT ".$field." FROM person WHERE id IN (?)";

        $stmt = $this->finalDBManager->getConnection()->executeQuery($sql, $executeArray,$typeArray);

        $personIds = $this->extractIdArray($stmt->fetchAll(), $field);
        
    
        if(!$onlyMainPersons){
            $sql = "SELECT ".$field." FROM relative WHERE id IN (?)";

            $stmt = $this->finalDBManager->getConnection()->executeQuery($sql, $executeArray,$typeArray);
            $relativeIds = $this->extractIdArray($stmt->fetchAll(), $field);

            $sql = "SELECT ".$field." FROM partner WHERE id IN (?)";

            $stmt = $this->finalDBManager->getConnection()->executeQuery($sql, $executeArray,$typeArray);

            $partnerIds = $this->extractIdArray($stmt->fetchAll(), $field);
            
            $personIds = array_merge($personIds, $relativeIds);
            
            $personIds = array_merge($personIds, $partnerIds);
            
        }
        
        $this->finalDBManager->clear();
        
        return $personIds;
    }
    
}

