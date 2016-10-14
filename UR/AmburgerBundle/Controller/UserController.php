<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace UR\AmburgerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use UR\AmburgerBundle\Entity\User;

/**
 * Description of UserController
 *
 * @author johanna
 */
class UserController extends Controller implements SessionController{
    
    /* 
        Returns the user html.
    */
    public function indexAction()
    {
        $session = $this->getRequest()->getSession();
        if($session->get('userid')){
            return $this->render('AmburgerBundle:Administration:user.html.twig', array('show_username_notice'=>false, 'show_password_notice'=>false));
        }else{
            return $this->redirect($this->generateUrl('login'));
        }
    }
}
