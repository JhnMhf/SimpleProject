<?php

namespace UR\AmburgerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CorrectionEndController extends Controller implements CorrectionSessionController
{
    
    private $LOGGER;

    private function getLogger()
    {
        if(is_null($this->LOGGER)){
            $this->LOGGER = $this->get('monolog.logger.default');
        }
        
        return $this->LOGGER;
    }
         
    public function indexAction($ID){
        $this->getLogger()->debug("End action called.");
        $session = $this->getRequest()->getSession();
        if($this->get('correction_session.service')->checkSession($session)){
            return $this->render('AmburgerBundle:DataCorrection:end.html.twig', array('logged_in'=>true));
        } else {
            return $this->redirect($this->generateUrl('login'));
        }
    }
           
    public function completeAction($ID){
        $this->getLogger()->debug("Complete action called.");
        
        $this->get('correction_session.service')->completeCorrectionSession($ID);
        
        $response = new Response();
        $response->setStatusCode("200");
        return $response;
    }  

}
