<?php

namespace UR\AmburgerBundle\Util;

class DuplicatePersonsFinder {
    
    private $LOGGER;
    private $container;
    
    public function __construct($container)
    {
        $this->container = $container;
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

    //@TODO: Implement DuplicatePersonsFinder
    public function findDuplicatePersons($em, $ID){
       //search based on lastname, then compare all
       //search based on same firstname but no lastname
    }
}

