<?php

namespace UR\AmburgerBundle\EventListener;

use UR\AmburgerBundle\Controller\CorrectionSessionController;
use UR\AmburgerBundle\Controller\SessionController;
use UR\AmburgerBundle\Controller\AdminSessionController;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SessionListener
{
    private $LOGGER;
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
        $this->LOGGER = $this->get('monolog.logger.default');
    } 
    
    private function get($identifier){
        return $this->container->get($identifier);
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }
        
        if($controller[0] instanceof AdminSessionController){
            $this->checkAdminSession($event, $controller[0]);
        } else if ($controller[0] instanceof CorrectionSessionController) {
            $this->checkCorrectionSession($event, $controller[0]);
        } else if($controller[0] instanceof SessionController){
            $this->checkNormalSession($event, $controller[0]);
        }
    }
    
    private function checkAdminSession($event, $controller){
        $this->LOGGER->debug("Checking Adminsession in filter.");
        $this->checkNormalSession($event, $controller);
        
        $session = $event->getRequest()->getSession();
        $userId = $session->get('userid');
        
        $systemDBManager = $this->get('doctrine')->getManager('system');
        
        if(!$systemDBManager->getRepository('AmburgerBundle:User')->isAdmin($userId)){
            throw new AccessDeniedHttpException("The user has not the necessary rights.");
        }
    }
    
    private function checkNormalSession($event, $controller){
        $this->LOGGER->debug("Checking Session in filter.");
        $session = $event->getRequest()->getSession();

        if(!$this->get('correction_session.service')->checkSession($session)){
            $redirectUrl = $controller->generateUrl('login');

            $this->LOGGER->debug("Redirecting to login at: ".$redirectUrl);

            $event->setController(function() use ($redirectUrl) {
                return new RedirectResponse($redirectUrl);
            });
        }
    }
    
    private function checkCorrectionSession($event, $controller){
        $this->checkNormalSession($event, $controller);
        $this->LOGGER->debug("Checking CorrectionSession in filter.");

        $session = $event->getRequest()->getSession();
        $ID = $event->getRequest()->get('ID');

        $this->LOGGER->debug("Found ID: ".$ID);
        
        if(!$this->get('correction_session.service')->checkCorrectionSession($ID,$session)){
                //throw new AccessDeniedHttpException'(The user is currently not working on this!');
            
            $redirectUrl = $controller->generateUrl('correction');

            $this->LOGGER->debug("Redirecting to start at: ".$redirectUrl);
            
            $event->setController(function() use ($redirectUrl) {
                return new RedirectResponse($redirectUrl);
            });
        } 
    }
}