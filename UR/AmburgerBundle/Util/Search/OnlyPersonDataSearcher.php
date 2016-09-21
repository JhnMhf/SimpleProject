<?php

namespace UR\AmburgerBundle\Util\Search;

class OnlyPersonDataSearcher extends BaseDataSearcher {
    
    public function search($queryString, $onlyMainPersons, $lastName, $firstName, $patronym, $location, $territory, $country, $date, $fromDate, $toDate){
        return $this->checkAllPersonsByNames($onlyMainPersons, $lastName, $firstName, $patronym);
    }

}

