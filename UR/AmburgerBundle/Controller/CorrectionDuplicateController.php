<?php

namespace UR\AmburgerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class CorrectionDuplicateController extends Controller
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
        $this->getLogger()->debug("Duplicates side called: ".$OID);
        $session = $this->getRequest()->getSession();
        if($this->get('correction_session.service')->checkSession($session)){
            //check if allowed for this person
            if($this->get('correction_session.service')->checkCorrectionSession($OID,$session)){
                return $this->render('AmburgerBundle:DataCorrection:duplicate_persons.html.twig');
            } else {
                $response = new Response();
                $response->setStatusCode("403");
                return $response;
            }
        } else {
            return $this->redirect($this->generateUrl('login'));
        }
    }
    
    public function loadAction($OID)
    {
        $this->getLogger()->debug("Loading duplicates: ".$OID);
        $response["duplicate_persons"] = array();
        
        $serializer = $this->get('serializer');
        $json = $serializer->serialize($response, 'json');
        $jsonResponse = new JsonResponse();
        $jsonResponse->setContent($json);
        
        return $jsonResponse;
    }
}
