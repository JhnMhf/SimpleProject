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
    
    public function indexAction($ID)
    {
        $this->getLogger()->debug("Person correction side called: ".$ID);
        return $this->render('AmburgerBundle:DataCorrection:person.html.twig');
    }
    
    public function loadAction($ID)
    {
        $this->getLogger()->debug("Loading person data: ".$ID);
        $response = array();
        
        $response["old"] = $this->loadOldPersonByID($ID);
        $response["new"] = $this->loadNewPersonByID($ID);
        $response["final"] = $this->loadFinalPersonByID($ID);
        
        if(is_null($response["final"])){
            $response["final"] = $response["new"];
        }
        
        $serializer = $this->get('serializer');
        $json = $serializer->serialize($response, 'json');
        $jsonResponse = new JsonResponse();
        $jsonResponse->setContent($json);
        
        return $jsonResponse;
    }
    
    //@TODO: Not finished
    public function loadWeddingAction($ID){
        $this->getLogger()->debug("Loading wedding data for ID: ".$ID);
        $response = array();
        
        $response["old"] =  $this->loadWeddingFromOldDB($ID);
        $response["new"] = $this->loadWeddingFromNewDB($ID);
        $response["final"] = $this->loadWeddingFromFinalDB($ID);
        
        if(is_null($response["final"])){
            $response["final"] = $response["new"];
        }
        
        $serializer = $this->get('serializer');
        $json = $serializer->serialize($response, 'json');
        $jsonResponse = new JsonResponse();
        $jsonResponse->setContent($json);
        
        return $jsonResponse;
    }
    
    private function loadOldPersonByID($ID){
        $finalDBManager = $this->get('doctrine')->getManager('final');
        $person = $finalDBManager->getRepository('NewBundle:Person')->findOneById($ID);
        
        if(is_null($person)){
            //@TODO: Enable loading of data for other persons
            return array();
        }
        
        return $this->get('old_db_loader.service')->loadPersonByOID($person->getOid());
    }
    
    private function loadNewPersonByID($ID){
        
        $newDBManager = $this->get('doctrine')->getManager('new');
        
        $person = $newDBManager->getRepository('NewBundle:Person')->findOneById($ID);
        
        if(is_null($person)){
            $person = $newDBManager->getRepository('NewBundle:Relative')->findOneById($ID);
        }
        
        if(is_null($person)){
            $person = $newDBManager->getRepository('NewBundle:Partner')->findOneById($ID);
        }
        
        return !is_null($person) ? $person : array();
    }
    
    private function loadFinalPersonByID($ID){
        
        $finalDBManager = $this->get('doctrine')->getManager('final');
        
        $person = $finalDBManager->getRepository('NewBundle:Person')->findOneById($ID);
        
        if(is_null($person)){
            $person = $finalDBManager->getRepository('NewBundle:Relative')->findOneById($ID);
        }
        
        if(is_null($person)){
            $person = $finalDBManager->getRepository('NewBundle:Partner')->findOneById($ID);
        }
                
        return !is_null($person) ? $person : array();
    }
    
    public function saveAction($ID){
        $this->getLogger()->debug("Saving person data: ".$ID);
        $response = new Response();

        $content = $this->get("request")->getContent();
        
        
        if (!empty($content))
        {
            //http://jmsyst.com/libs/serializer/master/usage
            
            //Alternative: http://symfony.com/doc/current/components/serializer.html#deserializing-an-object
            $serializer = $this->get('serializer');
            

            $personEntity = $serializer->deserialize($content,'UR\DB\NewBundle\Entity\Person', 'json');
            
            if($personEntity->getId() == $ID){
                $em = $this->get('doctrine')->getManager('final');
                $this->get('person_saver.service')->savePerson($em,$this->get("request")->getSession(),$content, $personEntity);

                $response->setStatusCode("202");
            }else {
                $response->setContent("IDs do not match");
                $response->setStatusCode("406");
            }

        } else {
            $response->setContent("Missing Content.");
            $response->setStatusCode("406");
        }

        return $response;
    }
    
    public function saveWeddingAction($ID){
        $this->getLogger()->debug("Saving wedding data: ".$ID);
        $response = new Response();

        $content = $this->get("request")->getContent();
        
        
        if (!empty($content))
        {
            //http://jmsyst.com/libs/serializer/master/usage
            
            //Alternative: http://symfony.com/doc/current/components/serializer.html#deserializing-an-object
            $serializer = $this->get('serializer');
            

            $weddingData = $serializer->deserialize($content,'array<UR\DB\NewBundle\Entity\Wedding>', 'json');
            

            $em = $this->get('doctrine')->getManager('final');
            $this->get('person_saver.service')->saveWeddings($ID,$em,$this->get("request")->getSession(),$content, $weddingData);

            $response->setStatusCode("202");
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
    
    private function loadWeddingFromOldDB($ID){
        //for main person find it over the oid.
        $sql = "SELECT vornamen, name,`order`, nation, aufgebot, 
                verheiratet,  hochzeitstag, hochzeitsort, hochzeitsterritorium, 
                auflösung, gelöst, `vorher-nachher`
                FROM OldAmburgerDB.`ehepartner` 
                WHERE ID=
                (SELECT id FROM OldAmburgerDB.ids 
                    WHERE oid = 
                    (SELECT oid FROM FinalAmburgerDB.person WHERE id = :personID)
                )";

        $stmt = $this->get('doctrine')->getManager('old')->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $ID);
        $stmt->execute();

        return $stmt->fetchAll();
        
        return array();
    }
    
   private function loadWeddingFromNewDB($ID){
        $newDBManager = $this->get('doctrine')->getManager('new');
       
        $data = array();

        $data['weddings'] = $this->internalLoadWeddingData($newDBManager, $ID);
        
        $data['personData'] = array();
        
        for($i = 0; $i < count($data['weddings']); $i++){
            $data['personData'][] = $this->loadFirstnameAndLastnameById($newDBManager, $data['weddings'][$i]->getWifeId());
        }
        
        return $data;
    }
    
    private function loadWeddingFromFinalDB($ID){
        $finalDBManager = $this->get('doctrine')->getManager('final');

        $data = array();
        
        $data['weddings'] = $this->internalLoadWeddingData($finalDBManager, $ID);
        
        $data['personData'] = array();
        
        for($i = 0; $i < count($data['weddings']); $i++){
            $data['personData'][] = $this->loadFirstnameAndLastnameById($finalDBManager, $data['weddings'][$i]->getWifeId());
        }
        
        return $data;
    }
    
    private function internalLoadWeddingData($em, $ID){
        $weddings = $em->getRepository('NewBundle:Wedding')->loadWeddingsForHusband($ID);
         
        return !is_null($weddings) ? $weddings : array();
    }
    
    private function loadFirstnameAndLastnameById($em, $personID){
        $person = $em->getRepository('NewBundle:Person')->findOneById($personID);
        
        if(is_null($person)){
            $person = $em->getRepository('NewBundle:Relative')->findOneById($personID);
        }
        
        if(is_null($person)){
            $person = $em->getRepository('NewBundle:Partner')->findOneById($personID);
        }
                
        $personData = array();
        
        $personData['id'] = $personID;
        $personData['first_name'] = $person->getFirstName();
        $personData['last_name'] = $person->getLastName();
        
        return $personData;
    }
}
