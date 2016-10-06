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
        $sql = 'INSERT INTO AmburgerSystemDB.person_data (person_id) SELECT ID FROM FinalAmburgerDB.person WHERE ID NOT IN (SELECT person_id FROM AmburgerSystemDB.person_data)';
        $stmt = $this->getSystemDBManager()->getConnection()->prepare($sql);
        
        $stmt->execute();
        
        $sql = 'INSERT INTO AmburgerSystemDB.person_data (person_id) SELECT ID FROM FinalAmburgerDB.relative WHERE ID NOT IN (SELECT person_id FROM AmburgerSystemDB.person_data)';
        $stmt = $this->getSystemDBManager()->getConnection()->prepare($sql);
        
        $stmt->execute();
        
        
        $sql = 'INSERT INTO AmburgerSystemDB.person_data (person_id) SELECT ID FROM FinalAmburgerDB.partner WHERE ID NOT IN (SELECT person_id FROM AmburgerSystemDB.person_data)';
        $stmt = $this->getSystemDBManager()->getConnection()->prepare($sql);
        
        $stmt->execute();
        
        $this->getSystemDBManager()->flush();
    }
}

