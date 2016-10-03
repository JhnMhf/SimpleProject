<?php

namespace UR\AmburgerBundle\Util\Search;

class OnlyQueryStringSearcher extends BaseDataSearcher {

    public function search($queryString, $onlyMainPersons, $lastName, $firstName, $patronym, $location, $territory, $country, $date, $fromDate, $toDate){
        $lastName = $queryString;
        $firstName = $queryString;
        $patronym = $queryString;
        $location = $queryString;
        $territory = $queryString;
        $country = $queryString;
        $date = $queryString;
        
        $locationReferenceId = array();
        $territoryReferenceId = array();
        $countryReferenceId = array();
        $possibleDateReferenceIds = array();

        if (!empty($location)) {
            $locationReferenceId = $this->findLocation($location);
            $this->LOGGER->debug("Found " . count($locationReferenceId) . " locations.");
        }

        if (!empty($territory)) {
            $territoryReferenceId = $this->findTerritory($territory);
            $this->LOGGER->debug("Found " . count($territoryReferenceId) . " territories.");
        }

        if (!empty($country)) {
            $countryReferenceId = $this->findCountry($country);
            $this->LOGGER->debug("Found " . count($countryReferenceId) . " countries.");
        }

        if (!empty($date)) {
            $possibleDateReferenceIds = $this->findDatesBasedOnDate($date);
            $this->LOGGER->debug("Found " . count($possibleDateReferenceIds) . " dates.");
        } else if (!empty($fromDate) && !empty($toDate)) {
            $possibleDateReferenceIds = $this->findDatesBasedOnDateRange($fromDate, $toDate);
            $this->LOGGER->debug("Found " . count($possibleDateReferenceIds) . " dateranges.");
        }

        $personIds = array();
        
        if (count($locationReferenceId) > 0 
                || count($territoryReferenceId) > 0 
                || count($countryReferenceId) > 0 
                || count($possibleDateReferenceIds) > 0) {
            
            $personIds = $this->searchInEducations(false,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds);
            $personIds = array_merge($personIds,$this->searchInHonours(false,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds));
            $personIds = array_merge($personIds,$this->searchInProperties(false,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds));
            $personIds = array_merge($personIds,$this->searchInRanks(false,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds));
            $personIds = array_merge($personIds,$this->searchInReligions(false,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds));
            $personIds = array_merge($personIds,$this->searchInResidence(false,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds));
            $personIds = array_merge($personIds,$this->searchInRoadOfLife(false,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds));
            $personIds = array_merge($personIds,$this->searchInStatus(false,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds));
            $personIds = array_merge($personIds,$this->searchInWorks(false,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds));
            $personIds = array_merge($personIds,$this->searchInWedding(false,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds));

            
            //baptism, birth, death
            $personIds = array_merge($personIds,$this->searchInBaptism($onlyMainPersons,false,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds));
            $personIds = array_merge($personIds,$this->searchInBirth($onlyMainPersons,false,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds));
            $personIds = array_merge($personIds,$this->searchInDeath($onlyMainPersons,false,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds));
        }


        $personIds = array_merge($personIds,$this->searchForJobInPerson($onlyMainPersons, $queryString));
        $personIds = array_merge($personIds,$this->searchForJobInRoadOfLife($onlyMainPersons, $queryString));
        $personIds = array_merge($personIds,$this->searchForJobClassInPerson($onlyMainPersons, $queryString));
        $personIds = array_merge($personIds,$this->searchForNationInPerson($onlyMainPersons, $queryString));
        
        $personIds = array_merge($personIds, $this->checkAllPersonsByQueryString($onlyMainPersons, $queryString));
        
        $personIds = array_unique($personIds);
        
        if($onlyMainPersons){
            $personIds = $this->extractMainPersons($personIds);
        }
        
        sort($personIds);
        
        return $personIds;
    }
    
    public function isApplicable($queryString, $onlyMainPersons, $lastName, $firstName, $patronym, $location, $territory, $country, $date, $fromDate, $toDate){  
        return !$this->geographicalMarkersAreSet($location, $territory, $country)
                && !$this->dateMarkersAreSet($date, $fromDate, $toDate) 
                && !$this->personDataMarkersAreSet($lastName, $firstName, $patronym, null);
    }
}

