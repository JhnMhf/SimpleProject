<?php

namespace UR\AmburgerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CorrectionDuplicateController extends Controller implements CorrectionSessionController
{
    private $LOGGER;

    private function getLogger()
    {
        if(is_null($this->LOGGER)){
            $this->LOGGER = $this->get('monolog.logger.default');
        }
        
        return $this->LOGGER;
    }
    
    public function indexAction($ID)
    {
        $this->getLogger()->debug("Duplicates side called: ".$ID);
        return $this->render('AmburgerBundle:DataCorrection:duplicate_persons.html.twig');
    }
    
    public function loadAction($ID)
    {
        $this->getLogger()->debug("Loading duplicates: ".$ID);
        $em = $this->get('doctrine')->getManager('final');
        $duplicatePersons = $this->get('possible_duplicates_finder.service')->findPossibleDuplicates($em,$ID);
        
        if(count($duplicatePersons) > 0){
            $serializer = $this->get('serializer');
            $json = $serializer->serialize($duplicatePersons, 'json');
            $jsonResponse = new JsonResponse();
            $jsonResponse->setContent($json);

            return $jsonResponse;
        } else {
            $response = new Response();
            $response->setStatusCode("204");
            
            return $response;
        }

    }
}
