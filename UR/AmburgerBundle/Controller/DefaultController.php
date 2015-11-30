<?php

namespace UR\AmburgerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AmburgerBundle:Default:index.html.twig', array('name' => $name));
    }

    public function testAction()
    {
        return $this->render('AmburgerBundle:Default:test.html.twig');
    }
}
