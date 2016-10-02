<?php

namespace UR\AmburgerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CorrectionRelativesController extends Controller implements CorrectionSessionController
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
        $this->getLogger()->debug("Relatives page called: ".$OID);
        return $this->render('AmburgerBundle:DataCorrection:related_person.html.twig');
    }
    
    public function loadPersonAction($OID){
        $this->getLogger()->info("loadPersonAction called: ".$OID);
        $finalDBManager = $this->get('doctrine')->getManager('final');
        
        $person = $finalDBManager->getRepository('NewBundle:Person')->findOneByOid($OID);
        
        $serializer = $this->container->get('serializer');
        $json = $serializer->serialize($person, 'json');
        $response = new JsonResponse();
        $response->setContent($json);
        
        return $response;
    }
    
    public function loadDirectRelativesAction($OID)
    {
        $this->getLogger()->info("LoadDirectRelativesAction called: ".$OID);
        $relationShipLoader = $this->get('relationship_loader.service');
        $em = $this->get('doctrine')->getManager('final');
        
        $person = $em->getRepository('NewBundle:Person')->findOneByOid($OID);
        
                
        if(is_null($person)){
            throw new Exception("No person with OID '".$OID."' is saved in the database.");
        }
        
        $relatives = $relationShipLoader->loadOnlyDirectRelatives($em,$person->getId());
        
        $serializer = $this->container->get('serializer');
        $json = $serializer->serialize($relatives, 'json');
        $response = new JsonResponse();
        $response->setContent($json);
        
        return $response;
    }
    
    public function findPossibleRelativesAction($OID){
        $this->getLogger()->info("FindPossibleRelativesAction called: ".$OID);
        $em = $this->get('doctrine')->getManager('final');

        $possibleRelatives = $this->get('possible_relatives_finder.service')->findPossibleRelatives($em, $OID);
        
        $serializer = $this->container->get('serializer');
        $json = $serializer->serialize($possibleRelatives, 'json');
        $response = new JsonResponse();
        $response->setContent($json);
        
        return $response;
    }
    
    public function createAction($OID){
        $this->getLogger()->info("Create relation called: ".$OID);
        $response = new Response();

        $content = $this->get("request")->getContent();
        
        
        if (!empty($content))
        {
            $serializer = $this->get('serializer');
            
            $relationData = $serializer->deserialize($content, 'array', 'json');
            
            $this->createRelation($relationData);
            $response->setStatusCode("202");
        } else {
            $response->setContent("Missing Content.");
            $response->setStatusCode("406");
        }

        return $response;
    }
    
    public function updateAction($OID){
        $this->getLogger()->info("Update relation called: ".$OID);
        $response = new Response();

        $content = $this->get("request")->getContent();
        
        
        if (!empty($content))
        {
            $serializer = $this->get('serializer');
            
            $relationData = $serializer->deserialize($content, 'array', 'json');
            $this->updateRelation($relationData);

            $response->setStatusCode("202");
        } else {
            $response->setContent("Missing Content.");
            $response->setStatusCode("406");
        }

        return $response;
    }
    
    public function removeAction($OID){
        $this->getLogger()->info("Remove relation called: ".$OID);
        $response = new Response();

        $content = $this->get("request")->getContent();
        
        
        if (!empty($content))
        {
            $serializer = $this->get('serializer');
            
            $relationData = $serializer->deserialize($content, 'array', 'json');
            $this->removeRelation($relationData);

            $response->setStatusCode("202");
        } else {
            $response->setContent("Missing Content.");
            $response->setStatusCode("406");
        }

        return $response;
    }
    
    private function createRelation($relationData){
        $em = $this->get('doctrine')->getManager('final');
        
        $personId = $relationData['personId'];
        $relativeId = $relationData['relativeId'];
        $type = $relationData['currentRelation'];
        
        $entity = null;
        
        switch($type){
            case 'parent':
                $entity = new \UR\DB\NewBundle\Entity\IsParent();
                $entity->setChildID($personId);
                $entity->setParentID($relativeId);
                break;
            case 'child':
                $entity = new \UR\DB\NewBundle\Entity\IsParent();
                $entity->setChildID($relativeId);
                $entity->setParentID($personId);
                break;
            case 'sibling':
                $entity = new \UR\DB\NewBundle\Entity\IsSibling();
                $entity->setSiblingOneid($personId);
                $entity->setSiblingTwoid($relativeId);
                break;
            case 'marriagePartner':
                $entity = $this->createWedding($relationData);
                break;
        }
        
        $em->persist($entity);
        $em->flush();
    }
    
    private function createWedding($relationData){
        $entity = new \UR\DB\NewBundle\Entity\Wedding();
        
        if($relationData['personGender'] == \UR\DB\NewBundle\Utils\Gender::MALE) {
            $entity->setHusbandId($relationData['personId']);
            $entity->setWifeId($relationData['relativeId']);
        } else if($relationData['relativeGender'] == \UR\DB\NewBundle\Utils\Gender::MALE){
            $entity->setHusbandId($relationData['relativeId']);
            $entity->setWifeId($relationData['personId']);
        } else {
            $entity->setHusbandId($relationData['personId']);
            $entity->setWifeId($relationData['relativeId']);
        }
        
        return $entity;
    }
    
    private function updateRelation($relationData){
        $this->removeRelation($relationData);
        $this->createRelation($relationData);
    }
    
    private function removeRelation($relationData){
        $em = $this->get('doctrine')->getManager('final');

        $type = $relationData['originRelation'];
        
        $sql = "";
        
        switch($type){
            case 'parent':
                $sql = "DELETE FROM is_parent WHERE childID = :personId AND parentID = :relativeId";
                break;
            case 'child':
                $sql = "DELETE FROM is_parent WHERE childID = :relativeId AND parentID = :personId";
                break;
            case 'sibling':
                $sql = "DELETE FROM is_sibling WHERE (sibling_oneID = :personId AND sibling_twoID = :relativeId) "
                    . "OR (sibling_oneID = :relativeId AND sibling_twoID = :personId)";
                break;
            case 'marriagePartner':
                $sql = "DELETE FROM wedding WHERE (husband_ID = :personId AND wife_ID = :relativeId) "
                    . "OR (husband_ID = :relativeId AND wife_ID = :personId)";
                break;
        }
        
        $stmt = $em->getConnection()->prepare($sql);
        
        $stmt->bindValue('personId', $relationData['personId']);
        $stmt->bindValue('relativeId', $relationData['relativeId']);

        $stmt->execute();
    }
}
