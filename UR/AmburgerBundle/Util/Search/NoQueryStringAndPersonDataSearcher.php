<?php

namespace UR\AmburgerBundle\Util\Search;

class NoQueryStringAndPersonDataSearcher extends BaseDataSearcher {

    public function isApplicable($queryString, $onlyMainPersons, $lastName, $firstName, $patronym, $location, $territory, $country, $date, $fromDate, $toDate) {
        return empty($queryString) && !$this->personDataMarkersAreSet($lastName, $firstName, $patronym, $onlyMainPersons);
    }

    //write queries which only use what is set, extract person ids
    //baptism, death and birth have to be handled extra
    public function search($queryString, $onlyMainPersons, $lastName, $firstName, $patronym, $location, $territory, $country, $date, $fromDate, $toDate) {

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

        if (count($locationReferenceId) == 0 && count($territoryReferenceId) == 0 && count($countryReferenceId) == 0 && count($possibleDateReferenceIds) == 0) {
            return array();
        }

        $personIds = $this->searchInEducations(true,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds);
        $personIds = array_merge($personIds,$this->searchInHonours(true,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds));
        $personIds = array_merge($personIds,$this->searchInProperties(true,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds));
        $personIds = array_merge($personIds,$this->searchInRanks(true,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds));
        $personIds = array_merge($personIds,$this->searchInReligions(true,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds));
        $personIds = array_merge($personIds,$this->searchInResidence(true,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds));
        $personIds = array_merge($personIds,$this->searchInRoadOfLife(true,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds));
        $personIds = array_merge($personIds,$this->searchInStatus(true,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds));
        $personIds = array_merge($personIds,$this->searchInWorks(true,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds));
        
        //baptism, birth, death
        $personIds = array_merge($personIds,$this->searchInBaptism($onlyMainPersons,true,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds));
        $personIds = array_merge($personIds,$this->searchInBirth($onlyMainPersons,true,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds));
        $personIds = array_merge($personIds,$this->searchInDeath($onlyMainPersons,true,$locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds));
        
        
        $personIds =array_unique($personIds);
        
        sort($personIds);
        
        return $personIds;
    }

}
