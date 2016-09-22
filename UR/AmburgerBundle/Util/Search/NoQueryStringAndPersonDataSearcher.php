<?php

namespace UR\AmburgerBundle\Util\Search;

class NoQueryStringAndPersonDataSearcher extends BaseDataSearcher {

    public function isApplicable($queryString, $onlyMainPersons, $lastName, $firstName, $patronym, $location, $territory, $country, $date, $fromDate, $toDate){  
        return empty($queryString) && !$this->personDataMarkersAreSet($lastName, $firstName, $patronym);
    }
    
    //write queries which only use what is set, extract person ids
    //baptism, death and birth have to be handled extra
    public function search($queryString, $onlyMainPersons, $lastName, $firstName, $patronym, $location, $territory, $country, $date, $fromDate, $toDate){
        
        $listOfPossibleIds = [];
        
            for($i = 0; $i < 100; $i++){
                $listOfPossibleIds[] = $i;
            }
            
            return $listOfPossibleIds;
    }
}

