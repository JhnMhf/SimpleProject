<?php

namespace UR\AmburgerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;


class LoginController extends Controller
{
    /* 
        Returns the login html.
    */
    public function indexAction()
    {
        return $this->render('AmburgerBundle:DataCorrection:login.html.twig', array('show_username_notice'=>false, 'show_password_notice'=>false));
    }
    /* 
        Checks if the user exists and if the password is correct. If everything is correct the user gets logged in.
    */
    public function loginAction(Request $request)
    {
        $username = $request->request->get('username');
        $password = $request->request->get('password');
        $systemDBManager = $this->get('doctrine')->getManager('system');
        $user = $systemDBManager->getRepository('AmburgerBundle:User')->checkUser($username, $password);
        if(is_numeric($user)){
            switch($user){
                // username
                case -1:
                    return $this->render('AmburgerBundle:DataCorrection:login.html.twig', array('show_username_notice'=>true, 'show_password_notice'=>false));
                // password
                case -2:
                    return $this->render('AmburgerBundle:DataCorrection:login.html.twig', array('show_password_notice'=>true, 'show_username_notice'=>false));
                default:
                    return $this->render('AmburgerBundle:DataCorrection:login.html.twig');
            }
        }else{
            //set logged in
            //set user session
            $session = $this->getRequest()->getSession(); // Get started session
            if(!$session instanceof Session){
                $session = new Session(); // if there is no session, start it
                $session->start();
            }
            $value = $session->getId(); // get session id
            $session->set('name', $username);
            $session->set('userid', $user->getId());
            return $this->redirect($this->generateUrl('overview'));
        }
    }
    /* 
        Logs the user out by invalidating the session.
    */
    public function logoutAction(Request $request)
    {
        $session = $request->getSession();
        $session->invalidate();
        
        return $this->redirect($this->generateUrl('login'));
    }
}
