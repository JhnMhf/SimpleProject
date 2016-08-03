<?php

namespace UR\AmburgerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class CorrectionDuplicateController extends Controller
{
    public function indexAction($OID)
    {
        return $this->render('AmburgerBundle:DataCorrection:duplicate_persons.html.twig');
    }
    
    public function loadAction($OID)
    {
        $response["duplicate_persons"] = array();
        
        $serializer = $this->get('serializer');
        $json = $serializer->serialize($response, 'json');
        $jsonResponse = new JsonResponse();
        $jsonResponse->setContent($json);
        
        return $jsonResponse;
    }
}
