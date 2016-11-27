<?php

namespace UR\AmburgerBundle\Util\Search;

class SearchUtil {
    
    private $LOGGER;
    private $container;
    private $finalDBManager;
    private $searcherStrategies;
    
    public function __construct($container)
    {
        $this->container = $container;
        $this->initiateStrategies();
    } 
    
    private function get($identifier){
        return $this->container->get($identifier);
    }
    
    private function getLogger(){
        if(is_null($this->LOGGER)){
            $this->LOGGER = $this->get('monolog.logger.default');
        }
        
        return $this->LOGGER;
    }
    
    private function getFinalDBManager(){
        if(is_null($this->finalDBManager)){
            $this->finalDBManager = $this->get('doctrine')->getManager('final');
        }
        
        return $this->finalDBManager;
    }
    
    private function initiateStrategies(){
        $this->searcherStrategies = [];
        
        $this->searcherStrategies[] = new OnlyPersonDataSearcher($this->getLogger(), $this->getFinalDBManager());
        $this->searcherStrategies[] = new NoQueryStringAndPersonDataSearcher($this->getLogger(), $this->getFinalDBManager());
        $this->searcherStrategies[] = new NoQueryStringSearcher($this->getLogger(), $this->getFinalDBManager());
        $this->searcherStrategies[] = new OnlyQueryStringSearcher($this->getLogger(), $this->getFinalDBManager());
        $this->searcherStrategies[] = new MixedSearcher($this->getLogger(), $this->getFinalDBManager());
        
    }

    //boolean logic:
    // example: A AND (B OR C)
    // first search all entries matching A, 
    // then all entries matching B, 
    // then all entries matching C
    // build intersection of AB and AC
    // then add to one list and return
    
    //example 2: A AND (B XOR C)
    // first search all entries matching A, 
    // then all entries matching B, 
    // then all entries matching C
    // remove from matching entries of B all entries which are also in C, and vice-versa at the same time!!!
    // build intersection of AB and AC
    // then add to one list and return
    
    //Consequences: We have to first check if the queryString is boolean logic
    //and afterwards we have to do the search
    //and in the case of boolean logic we have to have a rule based system to
    //connect the results again
    
    //remember queryString can be a boolean logic query AND it should be checked
    //for almost anything...
    
    //1. if location, territory or country is set, load the matching id from db
    //1.1 if for any of this fields a value is set, but no matching id is found return empty list?
    //2. use queryString to find matching jobs, jobclass, nations and reuse them later while searching
    //3. search through educations etc. (how to handle death, birth and baptism?)
    //3.1 if year/fromYear&toYear is set, first search in date and use this in the next query
    //3.2. if no year/fromYear&toYear is set ignore date table and just use data from location, territory, country
    //4. search through birth, baptism and death and use the ids in the person
    //5. combine the found person ids from all relations to a big list
    //6. search person while using the person ids and lastName, firstName, patronym
    //6.1 if only main persons is true, check only the person table
    //6.2 if it is false check all tables
    //7. return complete list of matching ids
    
    
    //maybe it could be faster in case of set lastName/firstName/ patronym to first filter the persons
    //and then filter the rest?
    
    public function search($queryString, $onlyMainPersons, $lastName, $firstName, $patronym, $location, $territory, $country, $date, $fromDate, $toDate){
        
        $this->getLogger()->info(sprintf("Searching with the parameters: "
                . "onlyMainPersons=%s, lastName=%s, firstName=%s, patronym=%s, "
                . "location=%s, territory=%s, country=%s, date=%s, fromDate=%s, "
                . "toDate=%s", $onlyMainPersons, $lastName, $firstName, 
                $patronym, $location, $territory, $country, $date, $fromDate, 
                $toDate));
        
        for($i = 0; $i < count($this->searcherStrategies); $i++){
            $searcher = $this->searcherStrategies[$i];
            if($searcher->isApplicable($queryString, $onlyMainPersons, $lastName, $firstName, $patronym, $location, $territory, $country, $date, $fromDate, $toDate)){
                $this->getLogger()->info("Searching with the class: ".get_class($searcher));
                
                return (array) $searcher->search($queryString, $onlyMainPersons, $lastName, $firstName, $patronym, $location, $territory, $country, $date, $fromDate, $toDate);
            }
        }

        throw new \Exception("Found not matching search strategy.");
    }

}

