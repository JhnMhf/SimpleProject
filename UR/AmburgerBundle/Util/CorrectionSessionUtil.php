<?php

namespace UR\AmburgerBundle\Util;

use UR\AmburgerBundle\Entity\CorrectionSession;

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
    
    public function checkCorrectionSession($ID, $session){
        $this->LOGGER->debug("Checking the correction session.");
        if(!$this->checkSession($session)){
            return false;
        }
        
        $userId = $session->get('userid');
        
        $correctionSession = $this->getSystemDBManager()->getRepository('AmburgerBundle:CorrectionSession')
                ->findOneBy(array('personId' => $ID, 'activeUserId' => $userId));
                
        if(is_null($correctionSession)){
            $this->LOGGER->debug("This user is not working on this currently.");
            return false;
        }
        
        //update correction session to keep it alive
        
        $this->getSystemDBManager()->persist($correctionSession);
        $this->getSystemDBManager()->flush();
                
        return true;
    } 
    
    public function startCorrectionSession($ID, $personData, $session){
        $username = $session->get('name');
        $userId = $session->get('userid');
        $this->LOGGER->debug("Starting the correction session for User: ".$username. " with ID: ".$userId);
        $personData->setCurrentlyInProcess(true);
        
        $this->getSystemDBManager()->merge($personData);
        
        $correctionSession = new CorrectionSession();
        $correctionSession->setPersonId($ID);
        $correctionSession->setActiveUserName($username);
        $correctionSession->setActiveUserId($userId);
        $correctionSession->setSessionIdentifier($session->getId());      
        
        $this->getSystemDBManager()->persist($correctionSession);
        
        $this->getSystemDBManager()->flush($personData);
    }
    
    public function stopCorrectionSessionsForUser($userId){
        
        $correctionSessions = $this->getSystemDBManager()->getRepository('AmburgerBundle:CorrectionSession')
                ->findBy(array('activeUserId' => $userId));
        
        for($i = 0; $i < count($correctionSessions); $i++){
            $this->stopCorrectionSession($correctionSessions[$i]);
        }
       
        $this->getSystemDBManager()->flush();
    }
    
    public function stopCorrectionSession($correctionSession){
        $personData = $this->getSystemDBManager()->getRepository('AmburgerBundle:PersonData')->findOneByPersonId($correctionSession->getPersonId());

        //remove currently in progress flag from personData
        $personData->setCurrentlyInProcess(false);
        
        $this->getSystemDBManager()->merge($personData);
            
        $this->getSystemDBManager()->remove($correctionSession);
    }
    
    public function completeCorrectionSession($personId){
        $personData = $this->getSystemDBManager()->getRepository('AmburgerBundle:PersonData')->findOneByPersonId($personId);

        //remove currently in progress flag from personData
        $personData->setCurrentlyInProcess(false);
        $personData->setCompleted(true);
        
        $this->getSystemDBManager()->merge($personData);
            
        $correctionSession = $this->getSystemDBManager()->getRepository('AmburgerBundle:CorrectionSession')
                ->findOneBy(array('personId' => $personId));
        
        $this->getSystemDBManager()->remove($correctionSession);
        
        $this->getSystemDBManager()->flush();
    }
}

