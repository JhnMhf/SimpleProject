<?php

namespace UR\AmburgerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CorrectionPersonController extends Controller
{
    public function indexAction($OID)
    {
        return $this->render('AmburgerBundle:DataCorrection:person.html.twig');
    }
    
    public function loadAction($OID)
    {
        // return json response, with old, new and final in one?
        $response = array();
        
        //@TODO: Load person from old db and format like json of new db
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
        $data = array();        
        
        $oldDBManager = $this->get('doctrine')->getManager('old');
        
        $IDData = $oldDBManager->getRepository('OldBundle:Ids')->findOneByOid($OID);

        $ID = $IDData->getId();
        
        $data['oid'] = $OID;
        $data['person'] = $oldDBManager->getRepository('OldBundle:Person')->findOneById($ID);
        $data['herkunft'] = $oldDBManager->getRepository('OldBundle:Herkunft')->findOneById($ID);
        $data['tod'] = $oldDBManager->getRepository('OldBundle:Tod')->findOneById($ID);
        
        return $data;
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
        $response = new Response();

        //@TODO: Validate if this user is currently working on this person
        $content = $this->get("request")->getContent();
        
        
        
        //@TODO: Add error if no content is found.
        if (!empty($content))
        {
            //@TODO: Check for right content? oid etc?
            //http://jmsyst.com/libs/serializer/master/usage
            
            //Alternative: http://symfony.com/doc/current/components/serializer.html#deserializing-an-object
            $serializer = $this->get('serializer');
            $personEntity = $serializer->deserialize($content,'UR\DB\NewBundle\Entity\Person', 'json');
            
            //print_r($personEntity);
            
            //@TODO: check if oids are matching
            
            $finalDBManager = $this->get('doctrine')->getManager('final');
            
            //@TODO: Necessary only for testing?
            
            //@TODO: New dates are still not getting saved.
            //Maybe add doctrine pre persist listener, which checks for dateReferences and saves the dates?
            
            //@TODO: Current highest id?
            //@TODO: Person Ids not being set by serializer...
            //@TODO: Perhaps the merging of One-To-Many Relations has to be done manually.
            if(is_null($this->loadFinalPersonByOID($OID))){
                //first persist if not existant
                $finalDBManager->persist($personEntity);
                $finalDBManager->flush();
            }
                            
            //merge necessary to set relations right/ update the values

            $finalDBManager->merge($personEntity);
            $finalDBManager->flush();

            
            $response->setStatusCode("202");
        } else {
            $response->setContent("{Missing Content.");
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
