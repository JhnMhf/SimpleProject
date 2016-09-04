<?php

namespace UR\AmburgerBundle\Util;

class CorrectionSessionUtil {
    
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
    
    public function checkSession($session){
        $this->LOGGER->debug("Checking the normal session.");
        if(is_null($session) || is_null($session->get('userid'))){
            $this->LOGGER->debug("Session not valid.");
            return false;
        }
        
        return true;
    } 
    
    public function checkCorrectionSession($OID, $session){
        $this->LOGGER->debug("Checking the correction session.");
        if(!$this->checkSession($session)){
            return false;
        }
        
        $userId = $session->get('userid');
        
        $correctionSession = $this->getSystemDBManager()->getRepository('AmburgerBundle:CorrectionSession')
                ->findOneBy(array('oid' => $OID, 'activeUserId' => $userId));
                
        if(is_null($correctionSession)){
            $this->LOGGER->debug("This user is not working on this currently.");
            return false;
        }
                
        return true;
    } 
    
    public function startCorrectionSession($OID, $personData, $session){
        $this->LOGGER->debug("Starting the correction session.");
        $personData->setCurrentlyInProcess(true);
        
        $this->getSystemDBManager()->merge($personData);
        
        $correctionSession = new CorrectionSession();
        $correctionSession->setOid($OID);
        $correctionSession->setActiveUserName($session->get('name'));
        $correctionSession->setActiveUserId($session->get('userid'));
        $correctionSession->setSessionIdentifier($session->getId());      
        
        $this->getSystemDBManager()->persist($correctionSession);
        
        $this->getSystemDBManager()->flush($personData);
    }
}

