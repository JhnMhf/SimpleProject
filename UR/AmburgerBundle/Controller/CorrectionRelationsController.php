<?php

namespace UR\AmburgerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CorrectionRelationsController extends Controller implements CorrectionSessionController
{
    private $LOGGER;

    private function getLogger()
    {
        if(is_null($this->LOGGER)){
            $this->LOGGER = $this->get('monolog.logger.default');
        }
        
        return $this->LOGGER;
    }
    
    public function indexAction($OID)
    {
        $this->getLogger()->debug("Relations page called: ".$OID);
        return $this->render('AmburgerBundle:DataCorrection:related_person.html.twig');
    }
    
    public function loadDirectRelativesAction($OID)
    {
        $this->getLogger()->debug("LoadDirectRelativesAction called: ".$OID);
        $relationShipLoader = $this->get('relationship_loader.service');
        $em = $this->get('doctrine')->getManager('final');
        $relatives = $relationShipLoader->loadOnlyDirectRelatives($em,$ID);
        
        $serializer = $this->container->get('serializer');
        $json = $serializer->serialize($relatives, 'json');
        $response = new JsonResponse();
        $response->setContent($json);
        
        return $response;
    }
    
    public function findPossibleRelativesAction($OID){
        $this->getLogger()->debug("FindPossibleRelativesAction called: ".$OID);
        $em = $this->get('doctrine')->getManager('final');

        $possibleRelatives = $this->get('possible_relatives_finder.service')->findPossibleRelatives($em, $OID);
        
        $serializer = $this->container->get('serializer');
        $json = $serializer->serialize($possibleRelatives, 'json');
        $response = new JsonResponse();
        $response->setContent($json);
        
        return $response;
    }
    
    public function saveAction($OID){
        $this->getLogger()->debug("Save relation called: ".$OID);
    }
}
