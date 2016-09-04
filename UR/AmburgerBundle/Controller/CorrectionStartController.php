<?php

namespace UR\AmburgerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CorrectionStartController extends Controller implements SessionController
{
    
    private $LOGGER;

    private function getLogger()
    {
        if(is_null($this->LOGGER)){
            $this->LOGGER = $this->get('monolog.logger.default');
        }
        
        return $this->LOGGER;
    }
         
    public function indexAction(){
        $this->getLogger()->debug("Start action called.");
        $session = $this->getRequest()->getSession();
        if($this->get('correction_session.service')->checkSession($session)){
            return $this->render('AmburgerBundle:DataCorrection:start.html.twig');
        } else {
            return $this->redirect($this->generateUrl('login'));
        }
    }
    
    public function nextAction(){
        $this->getLogger()->debug("Next person action called.");
        $response = array();
        
        $systemDBManager = $this->get('doctrine')->getManager('system');
        $query = $systemDBManager->getRepository('AmburgerBundle:PersonData')->createQueryBuilder('p')
            ->where('p.currentlyInProcess = false AND p.completed = false')
            ->orderBy('p.oid', 'ASC')
            ->getQuery();
        $personData = $query->getResult();
        
        if(is_null($personData) || count($personData) == 0){
            $this->getLogger()->debug("There exists no uncorrected person anymore.");
            throw $this->createNotFoundException("There exists no uncorrected person anymore.");
        }
        
        $this->getLogger()->debug("Found ".count($personData)." uncorrected persons.");
        
        if(count($personData) > 1){
            //get first
            $personData = $personData[0];
        }
        
        $this->getLogger()->debug("Next person found: ".$personData->getOid());
        
        $response["oid"] = $personData->getOid();
        
        $serializer = $this->get('serializer');
        $json = $serializer->serialize($response, 'json');
        $jsonResponse = new JsonResponse();
        $jsonResponse->setContent($json);
        
        return $jsonResponse;
    }

    public function checkAction($OID){
        $this->getLogger()->debug("Check action called: ".$OID);
        $systemDBManager = $this->get('doctrine')->getManager('system');
        $personData = $systemDBManager->getRepository('AmburgerBundle:PersonData')->findOneByOid($OID);
        
        if(is_null($personData)){
            throw $this->createNotFoundException("The person with the OID:'".$OID."' does not exist");
        }

        $statusCode = "200";
        
        if($personData->getCurrentlyInProcess()){
            $statusCode = "409";
        } else if($personData->getCompleted()){
             $statusCode = "300";
        }
        
        $this->getLogger()->debug("Response code: ".$statusCode);
        
        $response = new Response();
        $response->setStatusCode($statusCode);
        
        return $response;
    }
    
    public function startWorkAction($OID){
        $this->getLogger()->debug("Start work action called ".$OID);
        $systemDBManager = $this->get('doctrine')->getManager('system');
        $personData = $systemDBManager->getRepository('AmburgerBundle:PersonData')->findOneByOid($OID);
        
        if($personData->getCurrentlyInProcess()){
            $this->getLogger()->debug("Already in progress.");
            $response = new Response();
            $response->setStatusCode("403");
            return $response;
        } 
        
        $this->get('correction_session.service')->startCorrectionSession($OID, $personData, $this->getRequest()->getSession());
        
        $response = new Response();
        $response->setStatusCode("200");
        return $response;
    }
    
}
