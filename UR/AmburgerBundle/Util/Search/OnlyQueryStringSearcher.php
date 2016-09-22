<?php

namespace UR\AmburgerBundle\Util\Search;

class OnlyQueryStringSearcher extends BaseDataSearcher {

    public function search($queryString, $onlyMainPersons, $lastName, $firstName, $patronym, $location, $territory, $country, $date, $fromDate, $toDate){
        
        $listOfPossibleIds = [];
        
            for($i = 0; $i < 100; $i++){
                $listOfPossibleIds[] = $i;
            }
            
            return $listOfPossibleIds;
    }
    
    public function isApplicable($queryString, $onlyMainPersons, $lastName, $firstName, $patronym, $location, $territory, $country, $date, $fromDate, $toDate){  
        return !$this->geographicalMarkersAreSet($location, $territory, $country)
                && !$this->dateMarkersAreSet($date, $fromDate, $toDate) 
                && !$this->personDataMarkersAreSet($lastName, $firstName, $patronym, $onlyMainPersons);
    }
}

