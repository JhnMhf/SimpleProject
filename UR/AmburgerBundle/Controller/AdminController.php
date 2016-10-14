<?php

namespace UR\AmburgerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller implements AdminSessionController
{
    private $LOGGER;

    private function getLogger()
    {
        if(is_null($this->LOGGER)){
            $this->LOGGER = $this->get('monolog.logger.default');
        }
        
        return $this->LOGGER;
    }
    
    public function overviewAction(){
        $systemDBManager = $this->get('doctrine')->getManager('system');
        $users = $systemDBManager->getRepository('AmburgerBundle:User')->findBy(array(), array('id'=>'asc'));
        
        return $this->render('AmburgerBundle:Administration:user_overview.html.twig', array('logged_in'=>true, 'users'=>$users));
    }
    
    public function deleteUserAction($USERID){
        $systemDBManager = $this->get('doctrine')->getManager('system');
        $systemDBManager->getRepository('AmburgerBundle:User')->deleteUser($USERID);
        
        $response = new Response();
        $response->setStatusCode("200");
        return $response;
    }
    
    public function nominateAdminAction($USERID){
        $systemDBManager = $this->get('doctrine')->getManager('system');
        $systemDBManager->getRepository('AmburgerBundle:User')->nominateAdmin($USERID);
        
        $response = new Response();
        $response->setStatusCode("200");
        return $response;
    }
    
    public function revokeAdminAction($USERID){
        $systemDBManager = $this->get('doctrine')->getManager('system');
        $systemDBManager->getRepository('AmburgerBundle:User')->revokeAdmin($USERID);
        
        $response = new Response();
        $response->setStatusCode("200");
        return $response;
    }
    
    public function changeUserAction($USERID){
        $systemDBManager = $this->get('doctrine')->getManager('system');
        $user = $systemDBManager->getRepository('AmburgerBundle:User')->getUserByUserId($USERID);
        
        return $this->render('AmburgerBundle:Administration:admin_change_user.html.twig', 
                array('logged_in' => true, 'username' => $user->getName(), 'passwords_empty' => false,
                    'passwords_do_not_match' => false,'password_updated' => false));
    }
    
    public function changeUserSaveAction($USERID){
        $systemDBManager = $this->get('doctrine')->getManager('system');
        $user = $systemDBManager->getRepository('AmburgerBundle:User')->getUserByUserId($USERID);
        
        $newPassword = $this->getRequest()->request->get('password');
        $newPasswordRepetition = $this->getRequest()->request->get('password-repetition');

        if($newPassword == "" || $newPasswordRepetition == ""){
            return $this->render('AmburgerBundle:Administration:admin_change_user.html.twig', 
                    array('logged_in' => true, 'username' => $user->getName(), 'passwords_empty' => true,'passwords_do_not_match' => true));
        }

        if($newPassword != $newPasswordRepetition){
            return $this->render('AmburgerBundle:Administration:admin_change_user.html.twig', 
                    array('logged_in' => true, 'username' => $user->getName(), 'passwords_empty' => false,'passwords_do_not_match' => true));
        }
        $systemDBManager->getRepository('AmburgerBundle:User')->updatePassword($USERID, $newPassword);

        return $this->redirect($this->generateUrl('admin_overview'));
    }
    
    public function createUserAction(){
        return $this->render('AmburgerBundle:Administration:admin_create_user.html.twig', 
                array('logged_in' => true,'username_exists'=>false, 'passwords_empty' => false,
                    'passwords_do_not_match' => false));
    }
    
    public function createUserSaveAction(){
        $systemDBManager = $this->get('doctrine')->getManager('system');
        
        $newUserName = $this->getRequest()->get('username');
        
        $user = $systemDBManager->getRepository('AmburgerBundle:User')->getUser($newUserName);
        
        if(!is_null($user)){
            return $this->render('AmburgerBundle:Administration:admin_create_user.html.twig', 
                array('logged_in' => true,'username_exists'=>true, 'passwords_empty' => false,
                    'passwords_do_not_match' => false));
        }
        
        $newPassword = $this->getRequest()->get('password');
        $newPasswordRepetition = $this->getRequest()->get('password-repetition');
        
        if($newPassword == "" || $newPasswordRepetition == ""){
            return $this->render('AmburgerBundle:Administration:admin_create_user.html.twig', 
                array('logged_in' => true,'username_exists'=>false, 'passwords_empty' => true,
                    'passwords_do_not_match' => false));
        }

        if($newPassword != $newPasswordRepetition){
            return $this->render('AmburgerBundle:Administration:admin_create_user.html.twig', 
                array('logged_in' => true,'username_exists'=>false, 'passwords_empty' => false,
                    'passwords_do_not_match' => true));
        }
        
        $systemDBManager->getRepository('AmburgerBundle:User')->createNewUser($newUserName, $newPassword);
        
        return $this->redirect($this->generateUrl('admin_overview'));
    }
}
