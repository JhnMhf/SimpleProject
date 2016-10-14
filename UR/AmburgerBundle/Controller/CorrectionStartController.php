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
        //@TODO: Check if this can be removed?
        if($this->get('correction_session.service')->checkSession($session)){
            return $this->render('AmburgerBundle:DataCorrection:start.html.twig', array('logged_in'=>true));
        } else {
            return $this->redirect($this->generateUrl('login'));
        }
    }
    
        
    public function statisticsAction() {
        $this->getLogger()->debug("statisticsAction called.");
        
        $statistics = $array();
        
        $statistics['total'] = $this->totalNumberOfPersonsToCorrect();
        $statistics['inProcess'] = $this->totalNumberOfPersonsToCorrect();
        $statistics['completed'] = $this->totalNumberOfPersonsToCorrect();

        $serializer = $this->get('serializer');
        $json = $serializer->serialize($statistics, 'json');
        $jsonResponse = new JsonResponse();
        $jsonResponse->setContent($json);
        
        return $jsonResponse;
    }
    
    private function totalNumberOfPersonsToCorrect(){
        $systemDBManager = $this->get('doctrine')->getManager('system');
        
        $qb = $systemDBManager->getRepostiory('AmburgerBundle:PersonData')->createQueryBuilder('pd');
        return $qb
            ->select('count(pd.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
    
    private function numberOfPersonsInProcess(){
        $systemDBManager = $this->get('doctrine')->getManager('system');
        
        $qb = $systemDBManager->getRepostiory('AmburgerBundle:PersonData')->createQueryBuilder('pd');
        return $qb
            ->select('count(pd.id)')
            ->where('pd.currentlyInProcess = 1')
            ->getQuery()
            ->getSingleScalarResult();
    }
    
        
    private function numberOfPersonsCompleted(){
        $systemDBManager = $this->get('doctrine')->getManager('system');
        
        $qb = $systemDBManager->getRepostiory('AmburgerBundle:PersonData')->createQueryBuilder('pd');
        return $qb
            ->select('count(pd.id)')
            ->where('pd.completed = 1')
            ->getQuery()
            ->getSingleScalarResult();
    }
    
    public function nextAction(){
        $this->getLogger()->debug("Next person action called.");
        $response = array();
        
        $systemDBManager = $this->get('doctrine')->getManager('system');
        $personData = $systemDBManager->getRepository('AmburgerBundle:PersonData')->getRandomEntities(1);
        
        if(is_null($personData) || count($personData) == 0){
            $this->getLogger()->debug("There exists no uncorrected person anymore.");
            throw $this->createNotFoundException("There exists no uncorrected person anymore.");
        }
        
        $this->getLogger()->debug("Found ".count($personData)." uncorrected persons.");
        
        if(count($personData) >= 1){
            //get first
            $personData = $personData[0];
        }
        
        $this->getLogger()->debug("Next person found: ".$personData->getPersonId());
        
        $response["id"] = $personData->getPersonId();
        
        $serializer = $this->get('serializer');
        $json = $serializer->serialize($response, 'json');
        $jsonResponse = new JsonResponse();
        $jsonResponse->setContent($json);
        
        return $jsonResponse;
    }

    public function checkAction($ID){
        $this->getLogger()->debug("Check action called: ".$ID);
        
        $headers = $this->getRequest()->headers->all();

        $response = new Response();
        
        if(!array_key_exists('type', $headers)){
            $this->getLogger()->debug("Didn't find the header 'type'");
            $response->setStatusCode(400);

            return $response;
        }
        
        $systemDBManager = $this->get('doctrine')->getManager('system');

        $personData = null;

        if($headers['type'][0] == 'ID'){
            $this->getLogger()->debug("Check called for ID");
            $personData = $systemDBManager->getRepository('AmburgerBundle:PersonData')->findOneByPersonId($ID);
        
            if(is_null($personData)){
                throw $this->createNotFoundException("The person with the ID:'".$ID."' does not exist");
            }
        } else if($headers['type'][0] == 'OID'){
            $this->getLogger()->debug("Check called for OID");
            $finalDBManager = $this->get('doctrine')->getManager('final');
            $realID = $finalDBManager->getRepository('NewBundle:Person')->getIDForOID($ID);
            
            if(is_null($realID)){
                throw $this->createNotFoundException("The person with the OID:'".$ID."' does not exist");
            }
            
            $personData = $systemDBManager->getRepository('AmburgerBundle:PersonData')->findOneByPersonId($realID);
        
            if(is_null($personData)){
                throw $this->createNotFoundException("The person with the ID:'".$realID."' does not exist");
            }
            
            $response->headers->set('ID', $realID);
            
        } else {
            $this->getLogger()->debug("Wrong content in the header 'type'");
            $response->setStatusCode(400);

            return $response;
        }
        
        
        $statusCode = "200";
        
        if($personData->getCurrentlyInProcess()){
            $statusCode = "409";
        } else if($personData->getCompleted()){
             $statusCode = "300";
        }
        
        $this->getLogger()->debug("Response code: ".$statusCode);
        
        $response->setStatusCode($statusCode);
        
        return $response;
    }
    
    public function startWorkAction($ID){
        $this->getLogger()->debug("Start work action called ".$ID);
        $systemDBManager = $this->get('doctrine')->getManager('system');
        $personData = $systemDBManager->getRepository('AmburgerBundle:PersonData')->findOneByPersonId($ID);
        
        if($personData->getCurrentlyInProcess()){
            $this->getLogger()->debug("Already in progress.");
            $response = new Response();
            $response->setStatusCode("403");
            return $response;
        } 
        
        $this->get('correction_session.service')->startCorrectionSession($ID, $personData, $this->getRequest()->getSession());
        
        $response = new Response();
        $response->setStatusCode("200");
        return $response;
    }
}
