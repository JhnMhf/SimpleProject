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
        
        $duplicateObjects = array();
        
        for($i = 0; $i < count($possibleDuplicatesFromDB); $i++){
            $person = $this->loadPersonByID($em, $possibleDuplicatesFromDB[$i]);
                    
           if(!is_null($person)){
                $duplicateObjects[] = $person;
           }
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
        
        for($i = 0; $i < count($listOfPossibleDuplicates); $i++){
            if($this->personComparer->comparePersons($person, $listOfPossibleDuplicates[$i], true)){
                $remainingPossibleDuplicates[] = $listOfPossibleDuplicates[$i];
            }
        }
        
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
}

