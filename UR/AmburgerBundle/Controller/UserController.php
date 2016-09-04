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
    
    public function addUserAction(Request $request)
    {
        $session = $this->getRequest()->getSession();
        if($session->get('userid')){
            //TODO: check rights of user if he is allowed to create a new user?
            $newUser = $request->request->get('username');
            $newPassword = $request->request->get('password');
            
            $systemDBManager = $this->get('doctrine')->getManager('system');
            $this->addUser($newUser, $newPassword);
            return $this->redirect($this->generateUrl('addUser_index'));
        }else{
            return $this->redirect($this->generateUrl('login'));
        }
    }
    
    private function addUser($username, $password){
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $newUser = new User();
        $newUser->setName($username);
        $newUser->setPassword($hashedPassword);
        
        $systemDBManager = $this->get('doctrine')->getManager('system');
        $systemDBManager->persist($newUser);
        $systemDBManager->flush();
    }
}
