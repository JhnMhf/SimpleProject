<?php

namespace UR\AmburgerBundle\Util;

use UR\AmburgerBundle\Entity\PersonData;

class PersonDataCreator {
    
    private $LOGGER;
    private $container;
    private $systemDBManager;
    private $finalDBManager;
    
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
   
    public function createMissingEntries(){
        $entries = $this->findMissingEntries();
        
        for($i = 0; $i < count($entries); $i++){
            $personData = new PersonData();
            $personData->setOid($entries[$i]['OID']);
            
            $this->getSystemDBManager()->persist($personData);
        }
        
        $this->getSystemDBManager()->flush();
    }
    
    private function findMissingEntries(){
        $sql = 'SELECT OID FROM NewAmburgerDB.person WHERE OID NOT IN (SELECT OID FROM AmburgerSystemDB.person_data)';
        $stmt = $this->getSystemDBManager()->getConnection()->prepare($sql);
        
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}

