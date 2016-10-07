<?php

namespace UR\AmburgerBundle\Util;

use UR\AmburgerBundle\Entity\CorrectionSession;

class CorrectionChangeTracker {
    
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
    
    public function trackChange($ID, $userName,$userId, $newData, $oldData = null){
        $change = new \UR\AmburgerBundle\Entity\ChangeTracking();
        $change->setPersonId($ID);
        $change->setActiveUserId($userId);
        $change->setActiveUserName($userName);
        $change->setNewData($newData);
        $change->setOldData($oldData);
        
        $this->getSystemDBManager()->persist($change);
        $this->getSystemDBManager()->flush();
    }
}

