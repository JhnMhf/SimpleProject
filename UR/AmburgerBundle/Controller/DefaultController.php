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
        $numberOfMigratedPersons = $this->get("migration_process.service")->runWithTestdata();
        
        return new Response("Number of migrated persons: ".$numberOfMigratedPersons);
    }
    
    public function personDataAction(){
        $this->get('person_data_creator.service')->createMissingEntries();
        
        return new Response();
    }
    
    public function findPossibleRelativesAction($database, $OID){
        $em = null;
        
        if($database == 'final'){
            $em = $this->get('doctrine')->getManager('final');
        } else if($database == 'new'){
            $em = $this->get('doctrine')->getManager('new');
        } else  {
            throw new Exception("Invalid database");
        }
        
        
        $possibleRelatives = $this->get('possible_relatives_finder.service')->findPossibleRelatives($em, $OID);
        
        print_r($possibleRelatives);
        
        return new Response();
    }
    
}
