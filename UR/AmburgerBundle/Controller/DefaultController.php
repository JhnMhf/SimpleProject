<?php

namespace UR\AmburgerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

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
    
    public function overviewAction()
    {
        return $this->render('AmburgerBundle:Default:overview.html.twig');
    }
    
    public function baseAction(){
        return $this->render('AmburgerBundle:DataCorrection:base.html.twig');
    }
    
    public function migrateProcessAction()
    {
        $numberOfMigratedPersons = $this->get("migration_process.service")->run();
        
        return new Response("Number of migrated persons: ".$numberOfMigratedPersons);
    }
    
    public function personDataAction(){
        $this->get('person_data_creator.service')->createMissingEntries();
        
        return new Response();
    }
    
}
