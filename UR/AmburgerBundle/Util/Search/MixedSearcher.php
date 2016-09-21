<?php

namespace UR\AmburgerBundle\Util\Search;

class MixedSearcher extends BaseDataSearcher {

    public function search($queryString, $onlyMainPersons, $lastName, $firstName, $patronym, $location, $territory, $country, $date, $fromDate, $toDate){
        
        $listOfPossibleIds = [];
        
            for($i = 0; $i < 100; $i++){
                $listOfPossibleIds[] = $i;
            }
            
            return $listOfPossibleIds;
            
            /*
             * 
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
        
        if(!empty($queryString)){
            $possibleJobIds = $this->findJob($queryString);
            $possibleJobClassIds = $this->findJobClass($queryString);
            $possibleNationIds = $this->findNation($queryString);
        }
        
        $educationIds = $this->searchInEducations($noLocationOrDateUsedForSearch, $queryString, 
                $locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds);
        
        
        $ids = $educationIds;
        
        if($noLocationOrDateUsedForSearch){
            //combine results from references with found persons from checkallpersons
            
            //get person ids for birth, baptism, death etc in an extra query
            
            //if only main person, the persons from references must be check if they are a main person
        } else {
            //build intersection of persons which are in references as well as in checkallpersons
        }
        
        return $personIds;
             */
    }
    
        
    private function searchInEducations($onlyQueryStringUsedForReferences, $queryString, 
            $locationReferenceId, $territoryReferenceId, $countryReferenceId, $possibleDateReferenceIds){
        
    }
}

