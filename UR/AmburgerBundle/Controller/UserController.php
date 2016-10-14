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
            return $this->render('AmburgerBundle:Administration:user_profile.html.twig', array('logged_in' => true, 'passwords_empty' => false,'passwords_do_not_match' => false,'password_updated' => false));
        }else{
            return $this->redirect($this->generateUrl('login'));
        }
    }
    
    public function saveAction()
    {
        $session = $this->getRequest()->getSession();
        if($session->get('userid')){
            $newPassword = $this->getRequest()->request->get('password');
            $newPasswordRepetition = $this->getRequest()->request->get('password-repetition');
            
            if($newPassword == "" || $newPasswordRepetition == ""){
                return $this->render('AmburgerBundle:Administration:user_profile.html.twig', array('logged_in' => true, 'passwords_empty' => true,'passwords_do_not_match' => true,'password_updated' => false));
            }
            
            if($newPassword != $newPasswordRepetition){
                return $this->render('AmburgerBundle:Administration:user_profile.html.twig', array('logged_in' => true, 'passwords_empty' => false,'passwords_do_not_match' => true,'password_updated' => false));
            }
            
            $systemDBManager = $this->get('doctrine')->getManager('system');
            
            $systemDBManager->getRepository('AmburgerBundle:User')->updatePassword($session->get('userid'), $newPassword);
            
             return $this->render('AmburgerBundle:Administration:user_profile.html.twig', array('logged_in' => true, 'passwords_empty' => false,'passwords_do_not_match' => false,'password_updated' => true));
        }else{
            return $this->redirect($this->generateUrl('login'));
        }
    }
}
