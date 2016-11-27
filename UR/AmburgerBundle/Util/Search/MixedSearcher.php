<?php

namespace UR\AmburgerBundle\Util\Search;

class MixedSearcher extends BaseDataSearcher {
    
    private $onlyQueryStringSearcher;
    private $noQueryStringSearcher;
    private $onlyPersonDataSearcher;
    private $noQueryStringAndPersonDataSearcher;

    public function __construct($LOGGER, $finalDBManager)
    {
        parent::__construct($LOGGER, $finalDBManager);
        $this->onlyQueryStringSearcher = new OnlyQueryStringSearcher($this->LOGGER, $this->finalDBManager);
        
        $this->onlyPersonDataSearcher = new OnlyPersonDataSearcher($this->LOGGER, $this->finalDBManager);
        $this->noQueryStringAndPersonDataSearcher = new NoQueryStringAndPersonDataSearcher($this->LOGGER, $this->finalDBManager);
        $this->noQueryStringSearcher = new NoQueryStringSearcher($this->LOGGER, $this->finalDBManager);
    }
    
    public function isApplicable($queryString, $onlyMainPersons, $lastName, $firstName, $patronym, $location, $territory, $country, $date, $fromDate, $toDate){  
        return true;
    }
    
    //set everything to querystring and combine queries with or
    public function search($queryString, $onlyMainPersons, $lastName, $firstName, $patronym, $location, $territory, $country, $date, $fromDate, $toDate){
        
        $personIdsOne = $this->onlyQueryStringSearcher->search($queryString, $onlyMainPersons, $lastName, $firstName, $patronym, $location, $territory, $country, $date, $fromDate, $toDate);
        
        $this->LOGGER->debug("Found " .count($personIdsOne). " with onlyQueryStringSeacher.");
        $personIdsTwo = array();
        if($this->onlyPersonDataSearcher->isApplicable(null, $onlyMainPersons, $lastName, $firstName, $patronym, $location, $territory, $country, $date, $fromDate, $toDate)){
            $personIdsTwo = $this->onlyPersonDataSearcher->search($queryString, $onlyMainPersons, $lastName, $firstName, $patronym, $location, $territory, $country, $date, $fromDate, $toDate);
        
            $this->LOGGER->debug("Found " .count($personIdsTwo). " with onlyPersonDataSearcher.");
        } else if($this->noQueryStringAndPersonDataSearcher->isApplicable(null, $onlyMainPersons, $lastName, $firstName, $patronym, $location, $territory, $country, $date, $fromDate, $toDate)){
            $personIdsTwo = $this->noQueryStringAndPersonDataSearcher->search($queryString, $onlyMainPersons, $lastName, $firstName, $patronym, $location, $territory, $country, $date, $fromDate, $toDate);
        
            $this->LOGGER->debug("Found " .count($personIdsTwo). " with noQueryStringAndPersonDataSearcher.");
        } else if($this->noQueryStringSearcher->isApplicable(null, $onlyMainPersons, $lastName, $firstName, $patronym, $location, $territory, $country, $date, $fromDate, $toDate)){
            $personIdsTwo = $this->noQueryStringSearcher->search($queryString, $onlyMainPersons, $lastName, $firstName, $patronym, $location, $territory, $country, $date, $fromDate, $toDate);
            
            $this->LOGGER->debug("Found " .count($personIdsTwo). " with noQueryStringSearcher.");
        } else{
            $this->LOGGER->error("Found no matching searcher in MixedSearcher.");
        }

        $intersectedPersonIds = array_intersect($personIdsOne, $personIdsTwo);
     
        sort($intersectedPersonIds);
        
        return $intersectedPersonIds;
    }
}

