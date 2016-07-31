<?php

namespace UR\AmburgerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CorrectionPersonController extends Controller
{
    public function indexAction($OID)
    {
        return $this->render('AmburgerBundle:DataCorrection:person.html.twig');
    }
    
    public function loadAction($OID)
    {
        // return json response, with old, new and final in one?
        $response = array();
        
        $response["old"] = array();
        $response["new"] = $this->loadNewPersonByOID($OID);
        $response["final"] = array();
        
        $serializer = $this->get('serializer');
        $json = $serializer->serialize($response, 'json');
        $jsonResponse = new JsonResponse();
        $jsonResponse->setContent($json);
        
        return $jsonResponse;
    }
    
    private function loadNewPersonByOID($OID){
        
        $newDBManager = $this->get('doctrine')->getManager('new');
        
        return $newDBManager->getRepository('NewBundle:Person')->findOneByOid($OID);
    }
}
