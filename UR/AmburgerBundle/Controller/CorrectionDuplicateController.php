<?php

namespace UR\AmburgerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CorrectionDuplicateController extends Controller implements CorrectionSessionController
{
    private $LOGGER;

    private function getLogger()
    {
        if(is_null($this->LOGGER)){
            $this->LOGGER = $this->get('monolog.logger.default');
        }
        
        return $this->LOGGER;
    }
    
    public function indexAction($ID)
    {
        $this->getLogger()->debug("Duplicates side called: ".$ID);
        return $this->render('AmburgerBundle:DataCorrection:duplicate_persons.html.twig', array('logged_in'=>true));
    }
    
    public function loadPersonWithRelativesAction($ID)
    {
        $this->getLogger()->debug("Loading person data: ".$ID);

        $serializer = $this->get('serializer');
        $json = $serializer->serialize($this->loadPersonWithRelatives($ID), 'json');
        $jsonResponse = new JsonResponse();
        $jsonResponse->setContent($json);
        
        return $jsonResponse;
    }
   
    
    public function loadDuplicatesAction($ID)
    {
        $this->getLogger()->debug("Loading duplicates: ".$ID);
        $em = $this->get('doctrine')->getManager('final');
        $duplicatePersons = $this->get('possible_duplicates_finder.service')->findPossibleDuplicates($em,$ID);
        
        if(count($duplicatePersons) > 0){
            $duplicatesData = array();
            
            for($i = 0; $i < count($duplicatePersons); $i++){
                $duplicatesData[] = $this->loadPersonWithRelativesFromObj($duplicatePersons[$i]);
            }
            
            $serializer = $this->get('serializer');
            $json = $serializer->serialize($duplicatesData, 'json');
            $jsonResponse = new JsonResponse();
            $jsonResponse->setContent($json);

            return $jsonResponse;
        } else {
            $response = new Response();
            $response->setStatusCode("204");
            
            return $response;
        }

    }
    
    public function mergeAction($ID, $duplicateId){
        $this->getLogger()->debug("Merge duplicates: ".$ID." and ".$duplicateId);
        
        $person = $this->loadPersonByID($em, $ID);
        $duplicate = $this->loadPersonByID($em, $ID);
        
        //check if someone else is correcting this person currently?
        
        $mergedPerson = $this->get("person_merging.service")->mergePersons($person, $duplicate);
        
        //@TODO: Remove correction data?
        
        
        if($mergedPerson->getId() == $ID){
            $response = new Response();
            $response->setStatusCode("200");

            return $response;
        } else {
            $response = new Response();
            $response->setStatusCode("406");

            return $response;
        }
    }
    
    private function loadPersonWithRelatives($ID){
        $relationShipLoader = $this->get('relationship_loader.service');
        $em = $this->get('doctrine')->getManager('final');
        $response = array();
        $response['person'] = $this->loadPersonByID($em, $ID);
        $response['relatives'] = $relationShipLoader->loadOnlyDirectRelatives($em,$ID);
        
        return $response;
    }
    
    private function loadPersonWithRelativesFromObj($person){
        $relationShipLoader = $this->get('relationship_loader.service');
        $em = $this->get('doctrine')->getManager('final');
        $response = array();
        $response['person'] = $person;
        $response['relatives'] = $relationShipLoader->loadOnlyDirectRelatives($em,$person->getId());
        
        return $response;
    }
    
    private function loadPersonByID($em, $ID){
        $person = $em->getRepository('NewBundle:Person')->findOneById($ID);
        
        if(is_null($person)){
            $person = $em->getRepository('NewBundle:Relative')->findOneById($ID);
        }
        
        if(is_null($person)){
            $person = $em->getRepository('NewBundle:Partner')->findOneById($ID);
        }
                
        return !is_null($person) ? $person : array();
    }
}
