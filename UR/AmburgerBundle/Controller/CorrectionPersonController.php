<?php

namespace UR\AmburgerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CorrectionPersonController extends Controller implements CorrectionSessionController
{
    private $LOGGER;

    private function getLogger()
    {
        if(is_null($this->LOGGER)){
            $this->LOGGER = $this->get('monolog.logger.default');
        }
        
        return $this->LOGGER;
    }
    
    public function indexAction($OID)
    {
        $this->getLogger()->debug("Person correction side called: ".$OID);
        return $this->render('AmburgerBundle:DataCorrection:person.html.twig');
    }
    
    public function loadAction($OID)
    {
        $this->getLogger()->debug("Loading person data: ".$OID);
        $response = array();
        
        $response["old"] = $this->loadOldPersonByOID($OID);
        $response["new"] = $this->loadNewPersonByOID($OID);
        $response["final"] = $this->loadFinalPersonByOID($OID);
        
        if(is_null($response["final"])){
            $response["final"] = $response["new"];
        }
        
        $serializer = $this->get('serializer');
        $json = $serializer->serialize($response, 'json');
        $jsonResponse = new JsonResponse();
        $jsonResponse->setContent($json);
        
        return $jsonResponse;
    }
    
    private function loadOldPersonByOID($OID){
        return $this->get('old_db_loader.service')->loadPersonByOID($OID);
    }
    
    private function loadNewPersonByOID($OID){
        
        $newDBManager = $this->get('doctrine')->getManager('new');
        
        $person = $newDBManager->getRepository('NewBundle:Person')->findOneByOid($OID);
        
        return !is_null($person) ? $person : array();
    }
    
    private function loadFinalPersonByOID($OID){
        
        $finalDBManager = $this->get('doctrine')->getManager('final');
        
        return $finalDBManager->getRepository('NewBundle:Person')->findOneByOid($OID);
    }
    
    public function saveAction($OID){
        $this->getLogger()->debug("Saving person data: ".$OID);
        $response = new Response();

        $content = $this->get("request")->getContent();
        
        
        if (!empty($content))
        {
            //http://jmsyst.com/libs/serializer/master/usage
            
            //Alternative: http://symfony.com/doc/current/components/serializer.html#deserializing-an-object
            $serializer = $this->get('serializer');
            

            $personEntity = $serializer->deserialize($content,'UR\DB\NewBundle\Entity\Person', 'json');
            
            if($personEntity->getOid() == $OID){
                $em = $this->get('doctrine')->getManager('final');
                $this->get('person_saver.service')->savePerson($em,$this->get("request")->getSession(),$content, $personEntity);

                $response->setStatusCode("202");
            }else {
                $response->setContent("OIDs do not match");
                $response->setStatusCode("406");
            }

        } else {
            $response->setContent("Missing Content.");
            $response->setStatusCode("406");
        }

        return $response;
    }
    
    
    
    public function dateSerializeAction(){
        $content = $this->get("request")->getContent();
        
        $serializer = $this->get('serializer');
        $entity = $serializer->deserialize($content,'UR\DB\NewBundle\Types\DateReference', 'json');
        
        print_r($entity);
        
        return new Response();
    }
}
