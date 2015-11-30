<?php

namespace UR\DB\OldBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('OldBundle:Default:index.html.twig', array('name' => $name));
    }
}
