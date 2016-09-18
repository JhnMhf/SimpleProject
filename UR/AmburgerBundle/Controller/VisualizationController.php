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
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of UserController
 *
 * @author johanna
 */
class VisualizationController extends Controller{
    
    public function indexAction()
    {
        return $this->render('AmburgerBundle:Visualization:index.html.twig');
    }

    public function detailAction($ID)
    {
        return $this->render('AmburgerBundle:Visualization:detail.html.twig');
    }
}
