<?php

namespace UR\AmburgerBundle\Util;

use Doctrine\Common\Collections\Criteria;

class PossibleDuplicatesFinder {
    
    private $LOGGER;
    private $container;
    private $personComparer;
    
    public function __construct($container)
    {
        $this->container = $container;
        $this->personComparer = $this->get('comparer.service');
    }
    
    private function get($identifier){
        return $this->container->get($identifier);
    }
    
    private function getLogger(){
        if(is_null($this->LOGGER)){
            $this->LOGGER = $this->get('monolog.logger.default');
        }
        
        return $this->LOGGER;
    }
    
    public function findPossibleDuplicates($em, $ID){
        
        $person = $em->getRepository('NewBundle:Person')->findOneById($ID);
        
        if(is_null($person)){
            $person = $em->getRepository('NewBundle:Relative')->findOneById($ID);
        }
        
        if(is_null($person)){
            $person = $em->getRepository('NewBundle:Partner')->findOneById($ID);
        }
        
        if(is_null($person)){
            throw new Exception("No person with ID '".$ID."' is saved in the database.");
        }
        
        $this->getLogger()->info("Searching possible Duplicates for: ".$person);
        
        $possibleDuplicatesFromDB = $this->findPossibleDuplicatesInDB($em, $person);
        
        $this->getLogger()->info("Found ".count($possibleDuplicatesFromDB). " duplicates from DB.");
        
        //check for possible siblings over father and mother
        $possibleDuplicatesFromSiblings = $this->searchForPossibleDuplicatesFromSiblings($em, $ID);
        
        $this->getLogger()->info("Found ".count($possibleDuplicatesFromSiblings). " duplicates from siblings.");
        
        //check for possible parents over siblings
        $possibleDuplicatesFromParents = $this->searchForPossibleDuplicatesFromParents($em, $ID);
        
        $this->getLogger()->info("Found ".count($possibleDuplicatesFromParents). " duplicates from parents.");
        
        //check for possible siblings over father and mother
        $possibleDuplicatesFromChildren = $this->searchForPossibleDuplicatesFromChildren($em, $ID);
        
        $this->getLogger()->info("Found ".count($possibleDuplicatesFromChildren). " duplicates from children.");
        
        //check for possible parents over siblings
        $possibleDuplicatesFromPartners = $this->searchForPossibleDuplicatesFromPartners($em, $ID);
        
        $this->getLogger()->info("Found ".count($possibleDuplicatesFromPartners). " duplicates from partners.");
        
        $duplicateObjects = array();
        
        for($i = 0; $i < count($possibleDuplicatesFromDB); $i++){
            if($possibleDuplicatesFromDB != $ID){
                $duplicatePerson = $this->loadPersonByID($em, $possibleDuplicatesFromDB[$i]);
                    
                if(!is_null($duplicatePerson)){
                    $duplicateObjects[] = $duplicatePerson;
                }
            }
        }
        
        for($i = 0; $i < count($possibleDuplicatesFromSiblings); $i++){
            $duplicateObjects[] = $this->loadPersonByID($em, $possibleDuplicatesFromSiblings[$i]);
        }
        
        for($i = 0; $i < count($possibleDuplicatesFromParents); $i++){
            $duplicateObjects[] = $this->loadPersonByID($em, $possibleDuplicatesFromParents[$i]);
        }
        
        for($i = 0; $i < count($possibleDuplicatesFromChildren); $i++){
            $duplicateObjects[] = $this->loadPersonByID($em, $possibleDuplicatesFromChildren[$i]);
        }
        
        for($i = 0; $i < count($possibleDuplicatesFromPartners); $i++){
            $duplicateObjects[] = $this->loadPersonByID($em, $possibleDuplicatesFromPartners[$i]);
        }
        
        $remainingCheckedDuplicates = $this->checkPossibleDuplicates($person, $duplicateObjects);
        
        $this->getLogger()->info("After comparing with requested person ".count($remainingCheckedDuplicates). " person(s) remain.");
        
        return $remainingCheckedDuplicates;
    }
    
    private function findPossibleDuplicatesInDB($em, $person){
        

        if(!is_null($person->getFirstName()) && !is_null($person->getLastName())){
            return $this->findPossibleDuplicatesWithFirstAndLastname($em, $person);
        } else if(!is_null($person->getFirstName())){
            return $this->findPossibleDuplicatesWithFirstname($em, $person);
        } else if(!is_null($person->getLastName())){
            return $this->findPossibleDuplicatesWithLastname($em, $person);
        }
        
        return array();
    }
    
    private function findPossibleDuplicatesWithFirstAndLastname($em, $person){
        $queryBuilder = $em->getRepository('NewBundle:Person')->createQueryBuilder('p');
            
        $personResults = $queryBuilder
                ->select('p.id')
                ->where('p.id != :id '
                        . 'AND ((p.firstName = :firstName AND p.lastName = :lastName) '
                        . 'OR (p.firstName = :firstName AND p.lastName IS NULL) '
                        . 'OR (p.firstName IS NULL AND p.lastName = :lastName))')
                ->setParameter('id', $person->getId())
                ->setParameter('lastName', $person->getLastName())
                ->setParameter('firstName', $person->getFirstName())
                ->getQuery()
                ->getResult();
        
        
        $queryBuilder = $em->getRepository('NewBundle:Relative')->createQueryBuilder('r');

        $relativeResults = $queryBuilder
                ->select('r.id')
                ->where('r.id != :id '
                        . 'AND ((r.firstName = :firstName AND r.lastName = :lastName) '
                        . 'OR (r.firstName = :firstName AND r.lastName IS NULL) '
                        . 'OR (r.firstName IS NULL AND r.lastName = :lastName))')
                ->setParameter('id', $person->getId())
                ->setParameter('lastName', $person->getLastName())
                ->setParameter('firstName', $person->getFirstName())
                ->getQuery()
                ->getResult();

        $queryBuilder = $em->getRepository('NewBundle:Partner')->createQueryBuilder('p');

        $partnerResults = $queryBuilder
                ->select('p.id')
                ->where('p.id != :id '
                        . 'AND ((p.firstName = :firstName AND p.lastName = :lastName) '
                        . 'OR (p.firstName = :firstName AND p.lastName IS NULL) '
                        . 'OR (p.firstName IS NULL AND p.lastName = :lastName))')
                ->setParameter('id', $person->getId())
                ->setParameter('lastName', $person->getLastName())
                ->setParameter('firstName', $person->getFirstName())
                ->getQuery()
                ->getResult();
        
        $fullResults = array_merge($personResults, $relativeResults);
        return array_merge($fullResults, $partnerResults);
    }
    
    private function findPossibleDuplicatesWithFirstname($em, $person){
        $queryBuilder = $em->getRepository('NewBundle:Person')->createQueryBuilder('p');
            
        $personResults = $queryBuilder
                ->select('p.id')
                ->where('p.id != :id AND p.firstName = :firstName')
                ->setParameter('id', $person->getId())
                ->setParameter('firstName', $person->getFirstName())
                ->getQuery()
                ->getResult();

        $queryBuilder = $em->getRepository('NewBundle:Relative')->createQueryBuilder('r');

        $relativeResults = $queryBuilder
                ->select('r.id')
                ->where('r.id != :id AND r.firstName = :firstName)')
                ->setParameter('id', $person->getId())
                ->setParameter('firstName', $person->getFirstName())
                ->getQuery()
                ->getResult();

        $queryBuilder = $em->getRepository('NewBundle:Partner')->createQueryBuilder('p');

        $partnerResults = $queryBuilder
                ->select('p.id')
                ->where('p.id != :id AND p.firstName = :firstName')
                ->setParameter('id', $person->getId())
                ->setParameter('firstName', $person->getFirstName())
                ->getQuery()
                ->getResult();
        
        $fullResults = array_merge($personResults, $relativeResults);
        return array_merge($fullResults, $partnerResults);
    }
    
    private function findPossibleDuplicatesWithLastname($em, $person){
        $queryBuilder = $em->getRepository('NewBundle:Person')->createQueryBuilder('p');
            
        $personResults = $queryBuilder
                ->select('p.id')
                ->where('p.id != :id AND p.lastName = :lastName')
                ->setParameter('id', $person->getId())
                ->setParameter('lastName', $person->getLastName())
                ->getQuery()
                ->getResult();

        $queryBuilder = $em->getRepository('NewBundle:Relative')->createQueryBuilder('r');

        $relativeResults = $queryBuilder
                ->select('r.id')
                ->where('r.id != :id AND r.lastName = :lastName')
                ->setParameter('id', $person->getId())
                ->setParameter('lastName', $person->getLastName())
                ->getQuery()
                ->getResult();

        $queryBuilder = $em->getRepository('NewBundle:Partner')->createQueryBuilder('p');

        $partnerResults = $queryBuilder
                ->select('p.id')
                ->where('p.id != :id AND p.lastName = :lastName')
                ->setParameter('id', $person->getId())
                ->setParameter('lastName', $person->getLastName())
                ->getQuery()
                ->getResult();

        $fullResults = array_merge($personResults, $relativeResults);
        return array_merge($fullResults, $partnerResults);
    }
    
    private function checkPossibleDuplicates($person, $listOfPossibleDuplicates){
        $remainingPossibleDuplicates = array();
        
        array_unique($listOfPossibleDuplicates);
        
        for($i = 0; $i < count($listOfPossibleDuplicates); $i++){
            if($this->personComparer->comparePersons($person, $listOfPossibleDuplicates[$i], true)){
                $remainingPossibleDuplicates[] = $listOfPossibleDuplicates[$i];
            }
        }
        
        array_unique($remainingPossibleDuplicates);
        
        return $remainingPossibleDuplicates;
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
    
    private function searchForPossibleDuplicatesFromSiblings($em, $ID) {
        $this->getLogger()->info("Searching for duplicates from siblings");
        $possibleSiblings = array();
        $siblingEntries = $em->getRepository('NewBundle:IsSibling')->loadSiblings($ID);
        
        $siblingRelativeIds = array();
        
        for($i = 0; $i < count($siblingEntries); $i++){
            $siblingRelativeIds[] = $this->getRelativeId($ID, $siblingEntries[$i]);
        }
        
        $this->getLogger()->debug("Found ".count($siblingEntries)." siblings.");
        
        if(count($siblingEntries) > 0){
            $parentEntries = $em->getRepository('NewBundle:IsParent')->loadParents($ID);
            
            $this->getLogger()->debug("Found ".count($parentEntries)." parents.");
            
            if(count($parentEntries) > 0){
        
                for($i = 0; $i < count($parentEntries); $i++){
                    $relativeId = $this->getRelativeId($ID, $parentEntries[$i]);
                    $childrenOfParentEntries = $em->getRepository('NewBundle:IsParent')->loadChildren($relativeId);
                    
                    $this->getLogger()->debug("Found ".count($childrenOfParentEntries)." children of parents.");

                    if(count($childrenOfParentEntries) > 0){
                        for($j = 0; $j < count($childrenOfParentEntries); $j++){
                            $secondRelativeId = $this->getRelativeId($relativeId, $childrenOfParentEntries[$j]);

                            if($secondRelativeId != $ID 
                                    && !in_array($secondRelativeId, $siblingRelativeIds)
                                    && !in_array($secondRelativeId, $possibleSiblings)){
                                $possibleSiblings[] = $secondRelativeId;
                            }
                        }
                    }
                }
            }
        }

        
        
        return $possibleSiblings;
    }
    
    private function searchForPossibleDuplicatesFromParents($em, $ID) {
        $this->getLogger()->info("Searching for duplicates from parents");
        $possibleParents = array();
        $parentEntries = $em->getRepository('NewBundle:IsParent')->loadParents($ID);
        
        $parentRelativeIds = array();
        
        for($i = 0; $i < count($parentEntries); $i++){
            $parentRelativeIds[] = $this->getRelativeId($ID, $parentEntries[$i]);
        }
        
        $this->getLogger()->debug("Found ".count($parentEntries)." parents.");
        
        if(count($parentEntries) > 0){
            $siblingEntries = $em->getRepository('NewBundle:IsSibling')->loadSiblings($ID);
            
            $this->getLogger()->debug("Found ".count($siblingEntries)." siblings.");
             
            if(count($siblingEntries) > 0){

                for($i = 0; $i < count($siblingEntries); $i++){
                    $relativeId = $this->getRelativeId($ID, $siblingEntries[$i]);
                    $parentOfSiblingEntries = $em->getRepository('NewBundle:IsParent')->loadParents($siblingEntries[$i]);

                    $this->getLogger()->debug("Found ".count($parentOfSiblingEntries)." parents of siblings.");
                    
                    if(count($siblingEntries) > 0){
                        for($j = 0; $j < count($parentOfSiblingEntries); $j++){
                            $secondRelativeId = $this->getRelativeId($relativeId, $parentOfSiblingEntries[$j]);
                            if($secondRelativeId != $ID 
                                    && !in_array($secondRelativeId, $parentRelativeIds)
                                    && !in_array($secondRelativeId, $possibleParents)){
                                $possibleParents[] = $secondRelativeId;
                            }
                        }
                    }

                }
            }
        }
        
        return $possibleParents;
    }
    
   private function searchForPossibleDuplicatesFromChildren($em, $ID) {
        $this->getLogger()->info("Searching for duplicates from children");
        $possibleChildren = array();
        $childrenEntries = $em->getRepository('NewBundle:IsParent')->loadChildren($ID);
        
        $childRelativeIds = array();
        
        for($i = 0; $i < count($childrenEntries); $i++){
            $childRelativeIds[] = $this->getRelativeId($ID, $childrenEntries[$i]);
        }
        
        $this->getLogger()->debug("Found ".count($childrenEntries)." children.");
        
        if(count($childrenEntries) > 0){
            $partnerEntries = $em->getRepository('NewBundle:Wedding')->loadMarriagePartners($ID);
            
            $this->getLogger()->debug("Found ".count($partnerEntries)." partners.");
            
            if(count($partnerEntries) > 0){
                for($i = 0; $i < count($partnerEntries); $i++){
                    $relativeId = $this->getRelativeId($ID, $partnerEntries[$i]);
                    $childrenOfPartnerEntry = $em->getRepository('NewBundle:IsParent')->loadChildren($relativeId);
                    
                    $this->getLogger()->debug("Found ".count($childrenOfPartnerEntry)." children of partners.");

                    if(count($childrenOfPartnerEntry) > 0){
                    
                        for($j = 0; $j < count($childrenOfPartnerEntry); $j++){
                            $secondRelativeId = $this->getRelativeId($relativeId, $childrenOfPartnerEntry[$j]);

                            if($secondRelativeId != $ID 
                                    && !in_array($secondRelativeId, $childRelativeIds)
                                    && !in_array($secondRelativeId, $possibleChildren)){
                                $possibleChildren[] = $secondRelativeId;
                            }
                        }
                    
                    }
                }
            }
        
            
        }

        return $possibleChildren;
    }
    
    private function searchForPossibleDuplicatesFromPartners($em, $ID) {
        $this->getLogger()->info("Searching for duplicates from partners");
        $possiblePartners = array();
        $partnerEntries = $em->getRepository('NewBundle:Wedding')->loadMarriagePartners($ID);
        
        $partnerRelativeIds = array();
        
        for($i = 0; $i < count($partnerEntries); $i++){
            $partnerRelativeIds[] = $this->getRelativeId($ID, $partnerEntries[$i]);
        }
        
        $this->getLogger()->debug("Found ".count($partnerEntries)." partners.");
        
        if(count($partnerEntries) > 0){
            $childrenEntries = $em->getRepository('NewBundle:IsParent')->loadChildren($ID);
            
            $this->getLogger()->debug("Found ".count($childrenEntries)." children.");

            if(count($childrenEntries) > 0){

                for($i = 0; $i < count($childrenEntries); $i++){
                    $relativeId = $this->getRelativeId($ID, $childrenEntries[$i]);
                    $parentOfChildrenEntries = $em->getRepository('NewBundle:IsParent')->loadParents($childrenEntries[$i]);
                    
                    $this->getLogger()->debug("Found ".count($parentOfChildrenEntries)." parents of children.");

                    if(count($parentOfChildrenEntries) > 0){
                    
                        for($j = 0; $j < count($parentOfChildrenEntries); $j++){
                            $secondRelativeId = $this->getRelativeId($relativeId, $parentOfChildrenEntries[$j]);
                            if($secondRelativeId != $ID 
                                    && !in_array($secondRelativeId, $partnerRelativeIds)
                                    && !in_array($secondRelativeId, $possiblePartners)){
                                $possiblePartners[] = $secondRelativeId;
                            }
                        }
                        
                    }
                }
            }
        }
        
        return $possiblePartners;
    }
    
    private function getRelativeId($id, $relationShipObj) {
        $classOfObj = get_class($relationShipObj);

        switch ($classOfObj) {
            case "UR\DB\NewBundle\Entity\IsSibling":
                if ($relationShipObj->getSiblingOneId() == $id) {
                    return $relationShipObj->getSiblingTwoId();
                } else {
                    return $relationShipObj->getSiblingOneId();
                }
            case "UR\DB\NewBundle\Entity\IsParent":
                if ($relationShipObj->getChildId() == $id) {
                    return $relationShipObj->getParentId();
                } else {
                    return $relationShipObj->getChildId();
                }
            case "UR\DB\NewBundle\Entity\IsGrandParent":
                if ($relationShipObj->getGrandChildId() == $id) {
                    return $relationShipObj->getGrandParentId();
                } else {
                    return $relationShipObj->getGrandChildId();
                }
            case "UR\DB\NewBundle\Entity\IsParentInLaw":
                if ($relationShipObj->getChildInLawId() == $id) {
                    return $relationShipObj->getParentInLawId();
                } else {
                    return $relationShipObj->getChildInLawId();
                }
            case "UR\DB\NewBundle\Entity\Wedding":
                if ($relationShipObj->getHusbandid() == $id) {
                    return $relationShipObj->getWifeid();
                } else {
                    return $relationShipObj->getHusbandid();
                }
        }
    }
}

