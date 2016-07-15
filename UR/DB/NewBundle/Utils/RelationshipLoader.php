<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UR\DB\NewBundle\Utils;

/**
 * Description of RelationshipLoader
 *
 * @author johanna
 */
class RelationshipLoader {
    private $LOGGER;
    private $container;
    private $newDBManager;

    public function __construct($container) {
        $this->container = $container;
    }
    
    private function get($identifier) {
        return $this->container->get($identifier);
    }
    
    private function getLogger(){
        if(is_null($this->LOGGER)){
            $this->LOGGER = $this->get('monolog.logger.default');
        }
        
        return $this->LOGGER;
    }
    
    private function getDBManager(){
        if(is_null($this->newDBManager)){
            $this->newDBManager = $this->get('doctrine')->getManager('new');
        }
        
        return $this->newDBManager;
    }
    
    //@TODO: Adapt it to do "breath" first instead of "depth" first
    //in other words load the relations of the main person first
    //then go through all direct relatives and load their relations
    //continue doing this till its finished
    public function loadRelatives($id, $skipRelativesForPersons = true){
        $alreadyLoaded[] = array();
        $alreadyLoaded[] = $id;
        
        $relatives[] = array();
        
        $relatives["parents"] = $this->loadParents($id,$alreadyLoaded, $skipRelativesForPersons);
        $relatives["children"] = $this->loadChildren($id,$alreadyLoaded, $skipRelativesForPersons);
        $relatives["grandparents"] = $this->loadGrandparents($id,$alreadyLoaded,  $skipRelativesForPersons);
        $relatives["grandchildren"] = $this->loadGrandchildren($id,$alreadyLoaded,  $skipRelativesForPersons);
        $relatives["siblings"] = $this->loadSiblings($id,$alreadyLoaded,  $skipRelativesForPersons);
        $relatives["marriagePartners"] = $this->loadMarriagePartners($id,$alreadyLoaded,  $skipRelativesForPersons);
        $relatives["parentsInLaw"] = $this->loadParentsInLaw($id,$alreadyLoaded,  $skipRelativesForPersons);
        $relatives["childrenInLaw"] = $this->loadChildrenInLaw($id,$alreadyLoaded,  $skipRelativesForPersons);
        
        return $relatives;
    }
    
    private function loadParents($id,$alreadyLoaded,  $skipRelativesForPersons = true){
        $this->getLogger()->info("Loading parents for id: ".$id);

        $queryBuilder = $this->getDBManager()->getRepository('NewBundle:IsParent')->createQueryBuilder('p');

        $parentIds = $queryBuilder
                ->where('p.childID = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getResult();
        
        $this->getLogger()->info("Found  ".count($parentIds)." parents");
        
        return $this->generateRelativesEntry($id,$alreadyLoaded,  $parentIds, $skipRelativesForPersons);
    }
    
    private function loadChildren($id,$alreadyLoaded,  $skipRelativesForPersons = true){
        $this->getLogger()->info("Loading children for id: ".$id);

        $queryBuilder = $this->getDBManager()->getRepository('NewBundle:IsParent')->createQueryBuilder('p');

        $childrenIds = $queryBuilder
                ->where('p.parentID = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getResult();
        
        $this->getLogger()->info("Found  ".count($childrenIds)." children");
        
        return $this->generateRelativesEntry($id,$alreadyLoaded,  $childrenIds, $skipRelativesForPersons);
    }
    
    private function loadGrandparents($id,$alreadyLoaded,  $skipRelativesForPersons = true){
        $this->getLogger()->info("Loading grandparents for id: ".$id);

        $queryBuilder = $this->getDBManager()->getRepository('NewBundle:IsGrandparent')->createQueryBuilder('gp');

        $grandparentIds = $queryBuilder
                ->where('gp.grandChildID = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getResult();
        
        $this->getLogger()->info("Found  ".count($grandparentIds)." grandparents");
        
        return $this->generateRelativesEntry($id,$alreadyLoaded,  $grandparentIds, $skipRelativesForPersons);
    }
    
    private function loadGrandchildren($id,$alreadyLoaded,  $skipRelativesForPersons = true){
        $this->getLogger()->info("Loading grandchildren for id: ".$id);

        $queryBuilder = $this->getDBManager()->getRepository('NewBundle:IsGrandparent')->createQueryBuilder('gp');

        $grandchildrenIds = $queryBuilder
                ->where('gp.grandParentID = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getResult();
        
        $this->getLogger()->info("Found  ".count($grandchildrenIds)." grandchildren");
        
        return $this->generateRelativesEntry($id,$alreadyLoaded,  $grandchildrenIds, $skipRelativesForPersons);
    }
    
    private function loadSiblings($id,$alreadyLoaded,  $skipRelativesForPersons = true){
        $this->getLogger()->info("Loading siblings for id: ".$id);

        $queryBuilder = $this->getDBManager()->getRepository('NewBundle:IsSibling')->createQueryBuilder('s');

        $siblingIds = $queryBuilder
                ->where('s.siblingOneid = :id OR s.siblingTwoid = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getResult();
        
        $this->getLogger()->info("Found  ".count($siblingIds)." siblings");
        
        return $this->generateRelativesEntry($id,$alreadyLoaded,  $siblingIds, $skipRelativesForPersons);
    }
    
    private function loadMarriagePartners($id,$alreadyLoaded,  $skipRelativesForPersons = true){
        $this->getLogger()->info("Loading marriage partners for id: ".$id);

        $queryBuilder = $this->getDBManager()->getRepository('NewBundle:Wedding')->createQueryBuilder('w');

        $marriagePartnerIds = $queryBuilder
                ->where('w.husbandId = :id OR w.wifeId = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getResult();
        
        $this->getLogger()->info("Found  ".count($marriagePartnerIds)." marriage partners");
        
        return $this->generateRelativesEntry($id,$alreadyLoaded,  $marriagePartnerIds, $skipRelativesForPersons);
    }
    
    private function loadParentsInLaw($id,$alreadyLoaded,  $skipRelativesForPersons = true){
        $this->getLogger()->info("Loading parentInLaw for id: ".$id);

        $queryBuilder = $this->getDBManager()->getRepository('NewBundle:IsParentInLaw')->createQueryBuilder('p');

        $parentsInLawIds = $queryBuilder
                ->where('p.childInLawid = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getResult();
        
        $this->getLogger()->info("Found  ".count($parentsInLawIds)." parentInLaw");
            
        return $this->generateRelativesEntry($id,$alreadyLoaded,  $parentsInLawIds, $skipRelativesForPersons);
    }
    
     private function loadChildrenInLaw($id,$alreadyLoaded,  $skipRelativesForPersons = true){
        $this->getLogger()->info("Loading childrenInLaw for id: ".$id);

        $queryBuilder = $this->getDBManager()->getRepository('NewBundle:IsParentInLaw')->createQueryBuilder('p');

        $childrenInLawIds = $queryBuilder
                ->where('p.parentInLawid = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getResult();
        
        $this->getLogger()->info("Found  ".count($childrenInLawIds)." childrenInLaw");
            
        return $this->generateRelativesEntry($id,$alreadyLoaded,  $childrenInLawIds, $skipRelativesForPersons);
    }
    
    private function generateRelativesEntry($id, $alreadyLoaded,  $relativesIds, $skipRelativesForPersons = true){
        $relativesArray = array();
        
        for ($i = 0; $i < count($relativesIds); $i++) {
            $idOfRelative = $this->getRelativeId($id, $relativesIds[$i]);
            
            if(!in_array($idOfRelative, $alreadyLoaded)){
                $this->getLogger()->info("Loading Id ".$idOfRelative);
                $alreadyLoaded[] = $idOfRelative;
                $person = $this->loadPersonByID($idOfRelative);
            
                if($person != null){
                    $entry = array();
                    $entry["relation"] = $relativesIds[$i];
                    $entry["person"] = $person;

                    //if the person is no "person" or skipRelativesForPersons is deactivated
                    if(get_class($person) != PersonClasses::PERSON_CLASS || !$skipRelativesForPersons){
                        $entry["relatives"] = $this->loadRelatives($idOfRelative, $skipRelativesForPersons);
                    }

                   $relativesArray[] = $entry;
                }
            } else{
                $this->getLogger()->info("ID ".$idOfRelative. " was already loaded.");
            }
            

        }
        
        return $relativesArray;
    }
    
    private function loadWeddingData($idOne, $idTwo){
        //@TODO: Load wedding data
        return "weddingData";
    }
    
    private function getRelativeId($id, $relationShipObj){
        $classOfObj = get_class($relationShipObj);
        
        switch($classOfObj){
            case "UR\DB\NewBundle\Entity\IsSibling":
                if($relationShipObj->getSiblingOneId() == $id){
                    return $relationShipObj->getSiblingTwoId();
                } else {
                    return $relationShipObj->getSiblingOneId();
                }
            case "UR\DB\NewBundle\Entity\IsParent":
               if($relationShipObj->getChildId() == $id){
                    return $relationShipObj->getParentId();
                } else {
                    return $relationShipObj->getChildId();
                }
            case "UR\DB\NewBundle\Entity\IsGrandParent":
                if($relationShipObj->getGrandChildId() == $id){
                    return $relationShipObj->getGrandParentId();
                } else {
                    return $relationShipObj->getGrandChildId();
                }
            case "UR\DB\NewBundle\Entity\IsParentInLaw":
               if($relationShipObj->getChildInLawId() == $id){
                    return $relationShipObj->getParentInLawId();
                } else {
                    return $relationShipObj->getChildInLawId();
                }
            case "UR\DB\NewBundle\Entity\Wedding":
                if($relationShipObj->getHusbandid() == $id){
                    return $relationShipObj->getWifeid();
                } else {
                    return $relationShipObj->getHusbandid();
                }
        }
    }
    
    private function loadPersonByID($ID, $type = "id"){
        if($type == 'id'){
            $person = $this->getDBManager()->getRepository('NewBundle:Person')->findOneById($ID);
        
            if(is_null($person)){
                $person = $this->getDBManager()->getRepository('NewBundle:Relative')->findOneById($ID);
            }

            if(is_null($person)){
                $person = $this->getDBManager()->getRepository('NewBundle:Partner')->findOneById($ID);
            }

            if(is_null($person)){
               //throw exception
            }

            return $person;
        } else if($type == 'oid'){
            $person = $this->getDBManager()->getRepository('NewBundle:Person')->findOneByOid($ID);
        
            if(is_null($person)){
               //throw exception
            }

            return $person;
        }
            
        return null;
    }
}
