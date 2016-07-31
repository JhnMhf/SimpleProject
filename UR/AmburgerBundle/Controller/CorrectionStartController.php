<?php

namespace UR\AmburgerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CorrectionStartController extends Controller
{
         
    public function indexAction(){
        return $this->render('AmburgerBundle:DataCorrection:start.html.twig');
    }
    
    public function nextAction(){
        $response = array();
        
        //@TODO: Load next oid
        $response["oid"] = 1;
        
        $serializer = $this->get('serializer');
        $json = $serializer->serialize($response, 'json');
        $jsonResponse = new JsonResponse();
        $jsonResponse->setContent($json);
        
        return $jsonResponse;
    }

    public function checkAction($OID){
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
        
        $response = new Response();
        $response->setStatusCode($statusCode);
        
        return $response;
    }
    
    public function startWorkAction($OID){
        $systemDBManager = $this->get('doctrine')->getManager('system');
        $personData = $systemDBManager->getRepository('AmburgerBundle:PersonData')->findOneByOid($OID);
        
        if($personData->getCurrentlyInProcess()){
            $response = new Response();
            $response->setStatusCode("403");
            return $response;
        } 
        
        $personData->setCurrentlyInProcess(true);
        
        $systemDBManager->merge($personData);
        $systemDBManager->flush($personData);
        
        $response = new Response();
        $response->setStatusCode("200");
        return $response;
    }
    
}
