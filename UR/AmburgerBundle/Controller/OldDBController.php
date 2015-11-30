<?php

namespace UR\AmburgerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use UR\DB\OldDBBundle\Entity\Person;


class OldDBController extends Controller
{
    public function personAction($ID)
    {
    	$oldDBManager = $this->get('doctrine')->getManager('old');

    	$person = $oldDBManager->getRepository('OldBundle:Person')->findOneById($ID);

    	//$result = $oldDBManager->createQuery("SELECT *
        //    FROM OldDBBundle:Person WHERE id= LIMIT 1")
        //    ->getResult();

        if(!$person){
        	return new Response("Invalid ID");
        }

        //return $this->render('AmburgerBundle:Default:index.html.twig', array('name' => $name));

        return new Response("Test: ".$person->getName()." ".$person->getVornamen());
    }
}
