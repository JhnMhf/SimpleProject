<?php

namespace UR\AmburgerBundle\Util;

class PossibleRelativesFinder {
    
    const CSV_FILE = "@AmburgerBundle/Resources/files/patronym_exceptions.csv";
    const DELIMITER = "=";
    
    private $LOGGER;
    private $container;
    private $relationShipLoader;
    
    private $exceptionsMap;
    
    public function __construct($container)
    {
        $this->container = $container;
        $this->relationShipLoader = $this->get('relationship_loader.service');
        $this->createExceptionsMap();
    }
    
    private function createExceptionsMap(){
        $exceptionsMap = [];
        
        $path = $this->get('kernel')->locateResource(self::CSV_FILE);
       
        $lines = file($path);

        foreach($lines as $line)
        {
            $splittedLine = explode(self::DELIMITER,$line);
            $exceptionsMap[trim($splittedLine[0])] = trim($splittedLine[1]);
        }
        
        $keys = array_map('strlen', array_keys($exceptionsMap));
        array_multisort($keys, SORT_DESC, $exceptionsMap);
        
        $this->exceptionsMap = $exceptionsMap;
    }
    
    private function get($identifier){
        return $this->container->get($identifier);
    }
    
    private function getLogger(){
        if(is_null($this->LOGGER)){
            $this->LOGGER = $this->get('monolog.logger.relativesFinder');
        }
        
        return $this->LOGGER;
    }
    
    public function findPossibleRelatives($em, $ID){
        
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
        
        $this->getLogger()->info("Searching possible Relatives for: ".$person);
        
        $possibleRelatives = $this->loadPossibleRelatives($em, $person->getGender(), $person->getFirstName(), $person->getLastName(), $person->getPatronym());
        
        //remove the person itself
        $possibleRelatives = $this->removeElementWithValue($possibleRelatives, "ID", $person->getId());
        
        $this->getLogger()->info("Found ".count($possibleRelatives). " possible relatives.");
        
        if(count($possibleRelatives) > 0){
            $this->getLogger()->info("Loading existing relatives and removing them from the list.");
            $existingRelatives = $this->relationShipLoader->loadOnlyRelativeIds($em,$person->getId(),false, false);
            
            $this->getLogger()->debug("Loaded ".count($existingRelatives). " existing relatives.");
            
            //remove already known relatives
            for($i = 0; $i < count($existingRelatives); $i++){
                $possibleRelatives = $this->removeElementWithValue($possibleRelatives, "ID", $existingRelatives[$i]);
            }
            
            $this->getLogger()->info("After filtering ".count($possibleRelatives). " possible relatives remain.");
        }
        
        //necessary to fill possible unsetted values
        $possibleRelatives = array_values($possibleRelatives);
        
        //check for possible siblings over father and mother
        $possibleSiblings = $this->searchForPossibleSiblings($em, $ID);
        
        //check for possible parents over siblings
        $possibleParents = $this->searchForPossibleParents($em, $ID);
        
        //check for possible siblings over father and mother
        $possibleChildren = $this->searchForPossibleChildren($em, $ID);
        
        //check for possible parents over siblings
        $possiblePartners = $this->searchForPossiblePartners($em, $ID);
        
        //enrich possibleRelatives with Data
        $fullPossibleRelatives = [];

        for($i = 0; $i < count($possibleRelatives); $i++){
            $fullPossibleRelatives[] = $this->loadPerson($em, $possibleRelatives[$i]['ID']);
        }
        
        for($i = 0; $i < count($possibleSiblings); $i++){
            $fullPossibleRelatives[] = $this->loadPerson($em, $possibleSiblings[$i]);
        }
        
        for($i = 0; $i < count($possibleParents); $i++){
            $fullPossibleRelatives[] = $this->loadPerson($em, $possibleParents[$i]);
        }
        
        for($i = 0; $i < count($possibleChildren); $i++){
            $fullPossibleRelatives[] = $this->loadPerson($em, $possibleChildren[$i]);
        }
        
        for($i = 0; $i < count($possiblePartners); $i++){
            $fullPossibleRelatives[] = $this->loadPerson($em, $possiblePartners[$i]);
        }
        
        array_unique($fullPossibleRelatives);

        return $fullPossibleRelatives;
    }

    //@TODO: Find mothers based on siblings, marriage partner etc.
    public function findPossibleRelativesByOID($em, $OID){
        $this->getLogger()->info("Searching possible relatives for OID: ".$OID);
        
        $person = $em->getRepository('NewBundle:Person')->findOneByOid($OID);
        
        if(is_null($person)){
            throw new Exception("No person with OID '".$OID."' is saved in the database.");
        }
        
        return $this->findPossibleRelatives($em, $person->getId());
    }
    
    private function removeElementWithValue($array, $key, $value){
        foreach($array as $subKey => $subArray){
             if($subArray[$key] == $value){
                  unset($array[$subKey]);
             }
        }
        return $array;
   }
   
   private function loadPerson($em, $ID){
        $person = $em->getRepository('NewBundle:Person')->findOneById($ID);

        if (is_null($person)) {
            $person = $em->getRepository('NewBundle:Relative')->findOneById($ID);
        }

        if (is_null($person)) {
            $person = $em->getRepository('NewBundle:Partner')->findOneById($ID);
        }

        if (is_null($person)) {
            //throw exception
        }

        return $person;
   }
    
    private function loadPossibleRelatives($em, $gender, $firstName, $lastName, $patronym){
        $this->getLogger()->debug("Searching for Person with firstName: '".$firstName."', patronym: '".$patronym."' and lastName: '".$lastName."'");
        $possibleRelatives = array();
        if(!is_null($lastName) && trim($lastName) != ''){
            $this->getLogger()->info("Person with firstName: '".$firstName."', patronym: '".$patronym."' and lastName: '".$lastName."' loaded.");
            $matchingPatronyms = $this->generateMatchingPatronyms($gender, $firstName, $patronym);
            $matchingFirstNames = $this->generateMatchingFirstNames($gender, $firstName, $patronym);
            
            if(count($matchingFirstNames) > 0 && count($matchingPatronyms) > 0){
                $possibleRelatives = array_merge($possibleRelatives, $this->loadWithLastName($em, 'person', $lastName, $matchingPatronyms, $matchingFirstNames));
                $possibleRelatives = array_merge($possibleRelatives, $this->loadWithLastName($em, 'partner', $lastName, $matchingPatronyms, $matchingFirstNames));
                $possibleRelatives = array_merge($possibleRelatives, $this->loadWithLastName($em, 'relative', $lastName, $matchingPatronyms, $matchingFirstNames));
            }
        }  else if((!is_null($firstName) && trim($firstName) != '') 
                    || (!is_null($patronym) && trim($patronym) != '')
                ){
            $this->getLogger()->info("Person  with firstName: '".$firstName.", patronym: '".$patronym."' and without lastName loaded.");
            $matchingPatronyms = $this->generateMatchingPatronyms($gender,$firstName, $patronym);
            $matchingFirstNames = $this->generateMatchingFirstNames($gender, $firstName, $patronym);
            
            if(count($matchingFirstNames) > 0 && count($matchingPatronyms) > 0){
                $possibleRelatives = array_merge($possibleRelatives, $this->loadWithoutLastName($em, 'person', $matchingPatronyms, $matchingFirstNames));
                $possibleRelatives = array_merge($possibleRelatives, $this->loadWithoutLastName($em, 'partner', $matchingPatronyms, $matchingFirstNames));
                $possibleRelatives = array_merge($possibleRelatives, $this->loadWithoutLastName($em, 'relative', $matchingPatronyms, $matchingFirstNames));
            }
        } else {
            $this->getLogger()->info("Person has no firstname, patronym and lastname.");
        }
        return $possibleRelatives;
    }
    
    private function generateMatchingPatronyms($gender,$firstName, $patronym){
        $matchingPatronyms = array();
        
        if($gender == \UR\DB\NewBundle\Utils\Gender::FEMALE){
           $this->getLogger()->debug("Not generating matching patronyms from firstNames for female persons.");
        } else {
            if(!is_null($firstName) && trim($firstName) != ''){
                $this->getLogger()->debug("Generating matching patronyms from firstName: ".$firstName);
                $firstNames = explode(" ", $firstName);

                for($i= 0; $i < count($firstNames); $i++){
                    $matchingPatronyms = array_merge($matchingPatronyms, $this->generatePatronymsFromFirstName($firstNames[$i]));
                }
            }
        }
        
        if(!is_null($patronym) && trim($patronym) != ''){
            $matchingPatronyms[] = $patronym;
            $this->getLogger()->debug("generateMatchingPatronyms: Trying to extract firstName from patronym: ".$patronym);
            
            $patronyms = explode(" ", $patronym);
            
            for($i= 0; $i < count($patronyms); $i++){
                 $extractedFirstname = $this->extractFirstNameFromPatronym($patronyms[$i]);
            
                if(!is_null($extractedFirstname)){
                    $this->getLogger()->debug("generateMatchingPatronyms: Extracted firstName '".$extractedFirstname."' from patronym:".$patronyms[$i]);

                    $matchingPatronyms = array_merge($matchingPatronyms, $this->generatePatronymsFromFirstName($extractedFirstname));
                }
            }
            
        }
        

        return $matchingPatronyms;
    }
    
    private function generateMatchingFirstNames($gender,$firstName, $patronym){
        $matchingFirstNames = array();
        
        if(!is_null($patronym) && trim($patronym) != ''){
            $this->getLogger()->debug("generateMatchingFirstNames: Trying to extract firstName from patronym: ".$patronym);
            
            $patronyms = explode(" ", $patronym);
            
            for($i= 0; $i < count($patronyms); $i++){
                 $extractedFirstname = $this->extractFirstNameFromPatronym($patronyms[$i]);
            
                if(!is_null($extractedFirstname)){
                    $this->getLogger()->debug("generateMatchingFirstNames: Extracted firstName '".$extractedFirstname."' from patronym:".$patronyms[$i]);

                    $matchingFirstNames[] = $extractedFirstname;
                }
            }
            
        }
        
        return $matchingFirstNames;
    }
    
    /*
     *   -owna  -owitsch  -ewna  -ewitsch
     *   -ovna  -owič  -evna  -ewič
     */
    
    private function generatePatronymsFromFirstName($firstName){
        $matchingPatronyms = array();
        $matchingPatronyms[] = $firstName."owna";
        $matchingPatronyms[] = $firstName."ovna";
        $matchingPatronyms[] = $firstName."ewna";
        $matchingPatronyms[] = $firstName."evna";
        $matchingPatronyms[] = $firstName."ična";
        $matchingPatronyms[] = $firstName."inična";
        $matchingPatronyms[] = $firstName."owič";
        $matchingPatronyms[] = $firstName."ovič";
        $matchingPatronyms[] = $firstName."owitsch";
        $matchingPatronyms[] = $firstName."ewič";
        $matchingPatronyms[] = $firstName."evič";
        $matchingPatronyms[] = $firstName."ewitsch";
        $matchingPatronyms[] = $firstName."ič";
        $matchingPatronyms[] = $firstName."itsch";
        
        if(array_key_exists($firstName, $this->exceptionsMap)){
            $matchingPatronyms[] = $this->exceptionsMap[$firstName];
        }
            
        return $matchingPatronyms;
    }
    
    private function extractFirstNameFromPatronym($patronym){
        $exceptionFirstname = array_search($patronym, $this->exceptionsMap);
        if($exceptionFirstname){
            return $exceptionFirstname;
        } else if(stripos($patronym, "owna")){
            $pos = stripos($patronym, "owna");
            
            return substr($patronym, 0, $pos);
        } else if(stripos($patronym, "ovna")){
            $pos = stripos($patronym, "ovna");
            
            return substr($patronym, 0, $pos);
        } else if(stripos($patronym, "ewna")){
            $pos = stripos($patronym, "ewna");
            
            return substr($patronym, 0, $pos);
        } else if(stripos($patronym, "evna")){
            $pos = stripos($patronym, "evna");
            
            return substr($patronym, 0, $pos);
        }else if(stripos($patronym, "ična")){
            $pos = stripos($patronym, "ična");
            
            return substr($patronym, 0, $pos);
        } else if(stripos($patronym, "inična")){
            $pos = stripos($patronym, "inična");
            
            return substr($patronym, 0, $pos);
        }else if(stripos($patronym, "owič")){
            $pos = stripos($patronym, "owič");
            
            return substr($patronym, 0, $pos);
        } else if(stripos($patronym, "ovič")){
            $pos = stripos($patronym, "ovič");
            
            return substr($patronym, 0, $pos);
        } else if(stripos($patronym, "owitsch")){
            $pos = stripos($patronym, "owitsch");
            
            return substr($patronym, 0, $pos);
        }else if(stripos($patronym, "ewič")){
            $pos = stripos($patronym, "ewič");
            
            return substr($patronym, 0, $pos);
        } else if(stripos($patronym, "evič")){
            $pos = stripos($patronym, "evič");
            
            return substr($patronym, 0, $pos);
        } else if(stripos($patronym, "ewitsch")){
            $pos = stripos($patronym, "ewitsch");
            
            return substr($patronym, 0, $pos);
        } else if(stripos($patronym, "ič")){
            $pos = stripos($patronym, "ič");
            
            return substr($patronym, 0, $pos);
        } else if(stripos($patronym, "itsch")){
            $pos = stripos($patronym, "itsch");
            
            return substr($patronym, 0, $pos);
        }
        
        return null;
    }
    
    private function loadWithLastName($em, $table, $lastName, $matchingPatronyms, $matchingFirstNames){
        $sql = "SELECT ID, first_name,patronym, last_name FROM ".$table." WHERE last_name LIKE '%".$lastName."%'";
        
        if(count($matchingPatronyms) > 0 && count($matchingFirstNames) > 0){
            $sql .= " AND (";
            
            $sql .= $this->createMatchingPatronymsQuery($matchingPatronyms);
            $sql .= " OR ". $this->createMatchingFirstnamesQuery($matchingFirstNames);

            $sql .= ")";
        } else if(count($matchingPatronyms) > 0){
            $sql .= " AND (";
            
            $sql .= $this->createMatchingPatronymsQuery($matchingPatronyms);

            $sql .= ")";
        } else if(count($matchingFirstNames) > 0){
            $sql .= " AND (";
            
            $sql .= $this->createMatchingFirstnamesQuery($matchingFirstNames);

            $sql .= ")";
        }
        
        $this->getLogger()->debug("Running query for loadWithLastName: ".$sql);
        
        $stmt = $em->getConnection()->prepare($sql);
        
        $stmt->execute();
        
        $results = $stmt->fetchAll();
        
        $this->getLogger()->debug("Found ".count($results). " results");
        
        return $results;
    }

    private function loadWithoutLastName($em, $table, $arrayOfMatchingPatronyms, $matchingFirstNames){
        $sql = "SELECT ID, first_name,patronym, last_name FROM ".$table." WHERE ";
        
        if(count($arrayOfMatchingPatronyms) > 0 && count($matchingFirstNames) > 0){
            $sql .= $this->createMatchingPatronymsQuery($arrayOfMatchingPatronyms);
            $sql .= " OR ". $this->createMatchingFirstnamesQuery($matchingFirstNames);
        } else if(count($arrayOfMatchingPatronyms) > 0){
            $sql .= $this->createMatchingPatronymsQuery($arrayOfMatchingPatronyms);
        } else if(count($matchingFirstNames) > 0){
            $sql .= $this->createMatchingFirstnamesQuery($matchingFirstNames);
        }
        
        $this->getLogger()->debug("Running query for loadWithoutLastName: ".$sql);

        $stmt = $em->getConnection()->prepare($sql);
        
        $stmt->execute();
        
        $results = $stmt->fetchAll();
        
        $this->getLogger()->debug("Found ".count($results). " results");
        
        return $results;
    }
     
    private function createMatchingPatronymsQuery($matchingPatronyms){
        $sql = "";
        
        for($i= 0; $i < count($matchingPatronyms); $i++){
            if($i == 0){
                $sql .= "patronym LIKE '%".$matchingPatronyms[$i]."%'";
            } else {
                $sql .= " OR patronym LIKE '%".$matchingPatronyms[$i]."%'";
            }
        }
        
        return $sql;
    }
    
    private function createMatchingFirstnamesQuery($matchingFirstNames){
        $sql = "";
        
        for($i= 0; $i < count($matchingFirstNames); $i++){
            if($i == 0){
                $sql .= "first_name LIKE '%".$matchingFirstNames[$i]."%'";
            } else {
                $sql .= " OR first_name LIKE '%".$matchingFirstNames[$i]."%'";
            }
        }
        
        return $sql;
    }
    
    private function searchForPossibleSiblings($em, $ID) {
        $possibleSiblings = array();
        $siblingIds = $this->extractIdArray($ID,$em->getRepository('NewBundle:IsSibling')->loadSiblings($ID));

        $parentEntries = $em->getRepository('NewBundle:IsParent')->loadParents($ID);
        
        for($i = 0; $i < count($parentEntries); $i++){
            $relativeId = $this->getRelativeId($ID, $parentEntries[$i]);
            $childrenOfParentEntries = $em->getRepository('NewBundle:IsParent')->loadChildren($relativeId);
            
            for($j = 0; $j < count($childrenOfParentEntries); $j++){
                $secondRelativeId = $this->getRelativeId($relativeId, $childrenOfParentEntries[$j]);
                
                if($secondRelativeId != $ID 
                        && !in_array($secondRelativeId, $possibleSiblings)
                        && !in_array($secondRelativeId, $siblingIds)){
                    $possibleSiblings[] = $secondRelativeId;
                }
            }
        }
        
        return $possibleSiblings;
    }
    
    private function searchForPossibleParents($em, $ID) {
        $possibleParents = array();
        $parentIds = $this->extractIdArray($ID,$em->getRepository('NewBundle:IsParent')->loadParents($ID));
        
        $siblingEntries = $em->getRepository('NewBundle:IsSibling')->loadSiblings($ID);

        for($i = 0; $i < count($siblingEntries); $i++){
            $relativeId = $this->getRelativeId($ID, $siblingEntries[$i]);
            $parentOfSiblingEntries = $em->getRepository('NewBundle:IsParent')->loadParents($siblingEntries[$i]);
            
            for($j = 0; $j < count($parentOfSiblingEntries); $j++){
                $secondRelativeId = $this->getRelativeId($relativeId, $parentOfSiblingEntries[$j]);
                if($secondRelativeId != $ID 
                        && !in_array($secondRelativeId, $parentIds)
                        && !in_array($secondRelativeId, $possibleParents)){
                    $possibleParents[] = $secondRelativeId;
                }
            }
        }
        
        return $possibleParents;
    }
    
   private function searchForPossibleChildren($em, $ID) {
        $possibleChildren = array();
        $childrenIds = $this->extractIdArray($ID,$em->getRepository('NewBundle:IsParent')->loadChildren($ID));

        $partnerEntries = $em->getRepository('NewBundle:Wedding')->loadMarriagePartners($ID);
        
        for($i = 0; $i < count($partnerEntries); $i++){
            $relativeId = $this->getRelativeId($ID, $partnerEntries[$i]);
            $childrenOfParentEntries = $em->getRepository('NewBundle:IsParent')->loadChildren($relativeId);
            
            for($j = 0; $j < count($childrenOfParentEntries); $j++){
                $secondRelativeId = $this->getRelativeId($relativeId, $childrenOfParentEntries[$j]);
                
                if($secondRelativeId != $ID 
                        && !in_array($secondRelativeId, $childrenIds)
                        && !in_array($secondRelativeId, $possibleChildren)){
                    $possibleChildren[] = $secondRelativeId;
                }
            }
        }
        
        return $possibleChildren;
    }
    
    private function searchForPossiblePartners($em, $ID) {
        $possiblePartners = array();
        $partnerIds = $this->extractIdArray($ID,$em->getRepository('NewBundle:Wedding')->loadMarriagePartners($ID));
        
        $childrenEntries = $em->getRepository('NewBundle:IsParent')->loadChildren($ID);

        for($i = 0; $i < count($childrenEntries); $i++){
            $relativeId = $this->getRelativeId($ID, $childrenEntries[$i]);
            $parentOfSiblingEntries = $em->getRepository('NewBundle:IsParent')->loadParents($childrenEntries[$i]);
            
            for($j = 0; $j < count($parentOfSiblingEntries); $j++){
                $secondRelativeId = $this->getRelativeId($relativeId, $parentOfSiblingEntries[$j]);
                if($secondRelativeId != $ID 
                        && !in_array($secondRelativeId, $partnerIds)
                        && !in_array($secondRelativeId, $possiblePartners)){
                    $possiblePartners[] = $secondRelativeId;
                }
            }
        }
        
        return $possiblePartners;
    }
    
    private function extractIdArray($ID, $relationsShipArray){
        $idArray = array();
        
        for($i = 0; $i < count($relationsShipArray); $i++){
            $idArray[] = $this->getRelativeId($ID, $relationsShipArray[$i]);
        }
        
        return $idArray;
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