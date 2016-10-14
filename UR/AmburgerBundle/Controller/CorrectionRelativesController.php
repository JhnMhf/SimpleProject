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
    
    public function indexAction($ID)
    {
        $this->getLogger()->debug("Relatives page called: ".$ID);
        return $this->render('AmburgerBundle:DataCorrection:related_person.html.twig', array('logged_in'=>true));
    }
    
    public function loadPersonAction($ID){
        $this->getLogger()->info("loadPersonAction called: ".$ID);
        $finalDBManager = $this->get('doctrine')->getManager('final');
        
        $person = $this->loadPerson($finalDBManager,$ID);
        
        $serializer = $this->container->get('serializer');
        $json = $serializer->serialize($person, 'json');
        $response = new JsonResponse();
        $response->setContent($json);
        
        return $response;
    }
    
    private function loadPerson($em, $ID){
        
        $person = $em->getRepository('NewBundle:Person')->findOneById($ID);
        
        if(is_null($person)){
            $person = $em->getRepository('NewBundle:Relative')->findOneById($ID);
        }
        
        if(is_null($person)){
            $person = $em->getRepository('NewBundle:Partner')->findOneById($ID);
        }
                
        return !is_null($person) ? $person : array();
    }
    
    public function loadDirectRelativesAction($ID)
    {
        $this->getLogger()->info("LoadDirectRelativesAction called: ".$ID);
        $relationShipLoader = $this->get('relationship_loader.service');
        $em = $this->get('doctrine')->getManager('final');
        
        $relatives = $relationShipLoader->loadOnlyDirectRelatives($em,$ID);
        
        $serializer = $this->container->get('serializer');
        $json = $serializer->serialize($relatives, 'json');
        $response = new JsonResponse();
        $response->setContent($json);
        
        return $response;
    }
    
    public function findPossibleRelativesAction($ID){
        $this->getLogger()->info("FindPossibleRelativesAction called: ".$ID);
        $em = $this->get('doctrine')->getManager('final');

        $possibleRelatives = $this->get('possible_relatives_finder.service')->findPossibleRelatives($em, $ID);
        
        $serializer = $this->container->get('serializer');
        $json = $serializer->serialize($possibleRelatives, 'json');
        $response = new JsonResponse();
        $response->setContent($json);
        
        return $response;
    }
    
    public function createAction($ID){
        $this->getLogger()->info("Create relation called: ".$ID);
        $response = new Response();

        $content = $this->get("request")->getContent();
        
        
        if (!empty($content))
        {
            $serializer = $this->get('serializer');
            
            $relationData = $serializer->deserialize($content, 'array', 'json');
            
            $this->createRelation($relationData);
            
            $session = $this->get("request")->getSession();
            $this->get('correction_change_tracker')->trackChange($ID,$session->get('name'),$session->get('userid'), $content);
            $response->setStatusCode("202");
        } else {
            $response->setContent("Missing Content.");
            $response->setStatusCode("406");
        }

        return $response;
    }
    
    public function updateAction($ID){
        $this->getLogger()->info("Update relation called: ".$ID);
        $response = new Response();

        $content = $this->get("request")->getContent();
        
        
        if (!empty($content))
        {
            $serializer = $this->get('serializer');
            
            $relationData = $serializer->deserialize($content, 'array', 'json');
            
            $oldRelation = $this->loadRelation($relationData);
            
            $this->updateRelation($relationData);

            $oldData = $serializer->serialize($oldRelation, 'json');
            
            $session = $this->get("request")->getSession();
            $this->get('correction_change_tracker')->trackChange($ID,$session->get('name'),$session->get('userid'), $content,$oldData);
            $response->setStatusCode("202");
        } else {
            $response->setContent("Missing Content.");
            $response->setStatusCode("406");
        }

        return $response;
    }
    
    public function removeAction($ID){
        $this->getLogger()->info("Remove relation called: ".$ID);
        $response = new Response();

        $content = $this->get("request")->getContent();
        
        
        if (!empty($content))
        {
            $serializer = $this->get('serializer');
            
            $relationData = $serializer->deserialize($content, 'array', 'json');
            $oldRelation = $this->loadRelation($relationData);
            
            $this->removeRelation($relationData);

            $oldData = $serializer->serialize($oldRelation, 'json');
            
            $session = $this->get("request")->getSession();
            $this->get('correction_change_tracker')->trackChange($ID,$session->get('name'),$session->get('userid'), $content,$oldData);
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
    
    private function loadRelation($relationData){
        $em = $this->get('doctrine')->getManager('final');

        $type = $relationData['originRelation'];
        
        switch($type){
            case 'parent':
                $queryBuilder = $em->getRepository('NewBundle:IsParent')->createQueryBuilder('p');

                return $queryBuilder
                        ->where('p.childID = :personId AND p.parentID = :relativeId')
                        ->setParameter('personId', $relationData['personId'])
                        ->setParameter('relativeId', $relationData['relativeId'])
                        ->getQuery()
                        ->getResult();
            case 'child':
                $queryBuilder = $em->getRepository('NewBundle:IsParent')->createQueryBuilder('p');

                return $queryBuilder
                        ->where('p.childID = :relativeId AND p.parentID = :personId')
                        ->setParameter('personId', $relationData['personId'])
                        ->setParameter('relativeId', $relationData['relativeId'])
                        ->getQuery()
                        ->getResult();
            case 'sibling':
                $queryBuilder = $em->getRepository('NewBundle:IsSibling')->createQueryBuilder('s');

                return $queryBuilder
                        ->where('(s.siblingOneid = :personId AND s.siblingTwoid = :relativeId) OR (s.siblingOneid = :relativeId AND s.siblingTwoid = :personId)')
                        ->setParameter('personId', $relationData['personId'])
                        ->setParameter('relativeId', $relationData['relativeId'])
                        ->getQuery()
                        ->getResult();
            case 'marriagePartner':
                $queryBuilder = $em->getRepository('NewBundle:Wedding')->createQueryBuilder('w');

                return $queryBuilder
                        ->where('(w.husbandId = :personId AND w.wifeId = :relativeId) OR (w.husbandId = :relativeId AND w.wifeId = :personId)')
                        ->setParameter('personId', $relationData['personId'])
                        ->setParameter('relativeId', $relationData['relativeId'])
                        ->getQuery()
                        ->getResult();
        }
        
    }
}
