<?php

namespace UR\AmburgerBundle\Util\Search;

class NoQueryStringSearcher extends BaseDataSearcher {

    public function isApplicable($queryString, $onlyMainPersons, $lastName, $firstName, $patronym, $location, $territory, $country, $date, $fromDate, $toDate){  
        return empty($queryString);
    }
    
    //write queries which only use what is set

    //check birth, baptism, death, job, jobclass, nation
    //again for them?
    public function search($queryString, $onlyMainPersons, $lastName, $firstName, $patronym, $location, $territory, $country, $date, $fromDate, $toDate){
        //write queries which only use what is set
        $matchingPersonIds = $this->checkAllPersons($onlyMainPersons, $lastName, $firstName, $patronym);

        //check birth, baptism, death, job, jobclass, nation
        //again for them?


        return $matchingPersonIds;
    }
}

