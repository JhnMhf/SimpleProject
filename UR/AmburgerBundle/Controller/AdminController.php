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
    
    public function userOverviewAction(){
        $systemDBManager = $this->get('doctrine')->getManager('system');
        $users = $systemDBManager->getRepository('AmburgerBundle:User')->findBy(array(), array('id'=>'asc'));
        return $this->render('AmburgerBundle:Administration:user_overview.html.twig', array('logged_in'=>true, 'users'=>$users));
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
