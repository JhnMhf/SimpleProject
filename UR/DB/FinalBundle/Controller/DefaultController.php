<?php

namespace UR\DB\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('FinalBundle:Default:index.html.twig', array('name' => $name));
    }
}
