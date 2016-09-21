<?php

namespace UR\AmburgerBundle\Util\Search;

class NoQueryStringAndPersonDataSearcher extends BaseDataSearcher {

    public function search($queryString, $onlyMainPersons, $lastName, $firstName, $patronym, $location, $territory, $country, $date, $fromDate, $toDate){
        
        $listOfPossibleIds = [];
        
            for($i = 0; $i < 100; $i++){
                $listOfPossibleIds[] = $i;
            }
            
            return $listOfPossibleIds;
    }
}

