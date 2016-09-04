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
    
    public function loadNextAction($OID)
    {
        $this->getLogger()->debug("Load next relation called: ".$OID);
    }
    
    public function saveAction($OID){
        $this->getLogger()->debug("Save relation called: ".$OID);
    }
}
