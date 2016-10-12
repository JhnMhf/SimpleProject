<?php

namespace UR\AmburgerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

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
    
    public function familyTreeAction(){
        return $this->render('AmburgerBundle:Visualization:test.html.twig');
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
        
        $serializer = $this->container->get('serializer');
        $json = $serializer->serialize($possibleRelatives, 'json');
        $response = new JsonResponse();
        $response->setContent($json);
        
        return $response;
    }
    
    public function loadTrackedCorrectionAction($ID){
        $em = $this->get('doctrine')->getManager('system');
        
        $change = $em->getRepository('AmburgerBundle:ChangeTracking')->findOneById($ID);
        
        echo stream_get_contents($change->getNewData());
        
        echo stream_get_contents($change->getOldData());
        
        return new Response();
    }
    
}
