<?php

namespace UR\AmburgerBundle\Util;

use UR\AmburgerBundle\Entity\PersonData;

class OriginOfDataTracker {
    
    private $LOGGER;
    private $container;
    private $systemDBManager;
    
    public function __construct($container)
    {
        $this->container = $container;
        $this->LOGGER = $this->get('monolog.logger.default');
    } 
    
    private function get($identifier){
        return $this->container->get($identifier);
    }
   
    private function getSystemDBManager(){
        if(is_null($this->systemDBManager)){
            $this->systemDBManager = $this->get('doctrine')->getManager('system');
        }
        
        return $this->systemDBManager;
    }
   
    public function trackData($personId, $dataArray){
        $originOfDataEntry = new \UR\AmburgerBundle\Entity\OriginOfData();
        
        $serializer = $this->get('serializer');
        $serializedData = $serializer->serialize($dataArray, 'json');
        
        $originOfDataEntry->setPersonId($personId);
        $originOfDataEntry->setData($serializedData);
        
        $this->getSystemDBManager()->persist($originOfDataEntry);
        $this->getSystemDBManager()->flush();
    }
}

