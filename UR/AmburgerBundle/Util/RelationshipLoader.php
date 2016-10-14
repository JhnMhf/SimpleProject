<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UR\AmburgerBundle\Util;

use UR\DB\NewBundle\Utils\PersonClasses;

/**
 * Description of RelationshipLoader
 *
 * @author johanna
 */
class RelationshipLoader {

    const MAX_TIMES_OF_RECURSION = 30;
    
    private $LOGGER;
    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    private function get($identifier) {
        return $this->container->get($identifier);
    }

    private function getLogger() {
        if (is_null($this->LOGGER)) {
            $this->LOGGER = $this->get('monolog.logger.default');
        }

        return $this->LOGGER;
    }
    
    //load all relatives
    //and load for all relatives the information of their marriage partners and parents
    //the reference id is enough
    //the structure should be:
    // person: {fullperson}
    // marriage partners: [referenceOneId, referenceTwoId]
    // parents: [referenceOneId, referenceTwoId]
    
    public function loadDataForFamilyTree($em, $ID){
        $this->getLogger()->info("loadDataForFamilyTree: " . $ID);
        $familyTreeData = array();
        $familyTreeData[] = $this->generateFamilyTreeEntry($em, $ID);
        
        $alreadyLoaded = array();
        $alreadyLoaded[] = $ID;
        
        $familyTreeData = $this->internalLoadFamilyTree($em, $ID, $alreadyLoaded, $familyTreeData);
        
        $this->getLogger()->info("loadDataForFamilyTree end: " . $ID);
            
        return $familyTreeData;   
    }
    
    private function internalLoadFamilyTree($em, $ID, &$alreadyLoaded, $familiyTreeData) {
        $this->getLogger()->info("Internally loading family tree for ID: " . $ID);

        $parentEntries = $em->getRepository('NewBundle:IsParent')->loadParents($ID);
        
        for($i =0; $i < count($parentEntries); $i++){
            $relativeId = $this->getRelativeId($ID, $parentEntries[$i]);
            if(!in_array($relativeId, $alreadyLoaded)){
                $alreadyLoaded[] = $relativeId;
                $familiyTreeData[] = $this->generateFamilyTreeEntry($em, $relativeId);
                $familiyTreeData = $this->internalLoadFamilyTree($em, $relativeId, $alreadyLoaded, $familiyTreeData);
            }
        }
        
        $partnerEntries = $em->getRepository('NewBundle:Wedding')->loadMarriagePartners($ID);
        
        for($i =0; $i < count($partnerEntries); $i++){
            $relativeId = $this->getRelativeId($ID, $partnerEntries[$i]);
            if(!in_array($relativeId, $alreadyLoaded)){
                $alreadyLoaded[] = $relativeId;
                $familiyTreeData[] = $this->generateFamilyTreeEntry($em, $relativeId);
                $familiyTreeData = $this->internalLoadFamilyTree($em, $relativeId, $alreadyLoaded, $familiyTreeData);
            }
        }
        
        
        $childEntries= $em->getRepository('NewBundle:IsParent')->loadChildren($ID);
        
        for($i =0; $i < count($childEntries); $i++){
            $relativeId = $this->getRelativeId($ID, $childEntries[$i]);
            if(!in_array($relativeId, $alreadyLoaded)){
                $alreadyLoaded[] = $relativeId;
                $familiyTreeData[] = $this->generateFamilyTreeEntry($em, $relativeId);
                $familiyTreeData = $this->internalLoadFamilyTree($em, $relativeId, $alreadyLoaded, $familiyTreeData);
            }
        }
        
        $siblingEntries = $em->getRepository('NewBundle:IsSibling')->loadSiblings($ID);
        
        for($i =0; $i < count($siblingEntries); $i++){
            $relativeId = $this->getRelativeId($ID, $siblingEntries[$i]);
            if(!in_array($relativeId, $alreadyLoaded)){
                $alreadyLoaded[] = $relativeId;
                $familiyTreeData[] = $this->generateFamilyTreeEntry($em, $relativeId);
                $familiyTreeData = $this->internalLoadFamilyTree($em, $relativeId, $alreadyLoaded, $familiyTreeData);
            }
        }

        return $familiyTreeData;
    }
    
    private function generateFamilyTreeEntry($em, $ID) {
        $entry = array();
        
        $entry['id'] = $ID;
        $entry['person'] = $this->loadPersonByID($em, $ID);
        $entry['partners'] =  array();
        $partnerEntries = $em->getRepository('NewBundle:Wedding')->loadMarriagePartners($ID);
        
        for($i =0; $i < count($partnerEntries); $i++){
            $entry['partners'][] = $this->getRelativeId($ID, $partnerEntries[$i]);
        }
        
        
        $entry['parents'] =  array();
        $parentEntries = $em->getRepository('NewBundle:IsParent')->loadParents($ID);
        
        for($i =0; $i < count($parentEntries); $i++){
            $entry['parents'][] = $this->getRelativeId($ID, $parentEntries[$i]);
        }
        
        return $entry;
    }

    
    public function loadOnlyDirectRelatives($em, $id){
        $this->getLogger()->info("Loading direct relatives for ID: " . $id);
        
        $relatives = array();

        $relatives["parents"] = $this->loadParents($em, $id);
        $relatives["children"] = $this->loadChildren($em, $id);
        $relatives["siblings"] = $this->loadSiblings($em, $id);
        $relatives["marriagePartners"] = $this->loadMarriagePartners($em, $id);

        return $relatives;
    }
    
    //Passing the arrays as reference is necessary, since they will be updated!

    public function loadRelatives($em, $id,$loadOnlyDirectRelatives=true, $skipRelativesForPersons = true) {
        $this->getLogger()->info("loadRelatives: " . $id);
        $alreadyLoaded = array();
        $relatives = $this->internalLoadRelatives($em, $id, $alreadyLoaded,$loadOnlyDirectRelatives);

        $this->loadClosestRelatives($em, $relatives, $alreadyLoaded,$loadOnlyDirectRelatives, $skipRelativesForPersons);

        $numberOfCurrentlyLoadedRelatives = count($alreadyLoaded);

        $i = 0;
        do {
            $numberOfCurrentlyLoadedRelatives = count($alreadyLoaded);
            $this->loadRelativesWhichWereNotLoadedYet($em, $relatives, $alreadyLoaded,$loadOnlyDirectRelatives, $skipRelativesForPersons);
            $i++;
        } while ($numberOfCurrentlyLoadedRelatives < count($alreadyLoaded) && $i < MAX_TIMES_OF_RECURSION);

        $this->getLogger()->info("loadRelatives end: " . $id);
            
        return $relatives;
    }
    
    public function loadOnlyRelativeIds($em, $id, $loadOnlyDirectRelatives=true, $skipRelativesForPersons = true) {
        $this->getLogger()->info("loadOnlyRelativeIds: " . $id);
        $alreadyLoaded = array();
        $relatives = $this->internalLoadRelatives($em, $id, $alreadyLoaded, $loadOnlyDirectRelatives);

        $this->loadClosestRelatives($em, $relatives, $alreadyLoaded,$loadOnlyDirectRelatives, $skipRelativesForPersons);

        $numberOfCurrentlyLoadedRelatives = count($alreadyLoaded);

        $i = 0;
        do {
            $numberOfCurrentlyLoadedRelatives = count($alreadyLoaded);
            $this->loadRelativesWhichWereNotLoadedYet($em, $relatives, $alreadyLoaded,$loadOnlyDirectRelatives, $skipRelativesForPersons);
            $i++;
        } while ($numberOfCurrentlyLoadedRelatives < count($alreadyLoaded)  && $i < MAX_TIMES_OF_RECURSION);

        $this->getLogger()->info("loadOnlyRelativeIds end: " . $id);

        return $alreadyLoaded;
    }

    private function internalLoadRelatives($em, $id, &$alreadyLoaded,$loadOnlyDirectRelatives) {
        $this->getLogger()->info("Internally loading relatives for ID: " . $id);
        if (!in_array($id, $alreadyLoaded)) {
            $alreadyLoaded[] = $id;
        }

        $relatives = array();

        $relatives["parents"] = $this->loadParents($em, $id, $alreadyLoaded);
        $relatives["children"] = $this->loadChildren($em, $id, $alreadyLoaded);
        $relatives["siblings"] = $this->loadSiblings($em, $id, $alreadyLoaded);
        $relatives["marriagePartners"] = $this->loadMarriagePartners($em, $id, $alreadyLoaded);
        
        if($loadOnlyDirectRelatives){
            $relatives["grandparents"] = $this->loadGrandparents($em, $id, $alreadyLoaded);
            $relatives["grandchildren"] = $this->loadGrandchildren($em, $id, $alreadyLoaded);
            $relatives["parentsInLaw"] = $this->loadParentsInLaw($em, $id, $alreadyLoaded);
            $relatives["childrenInLaw"] = $this->loadChildrenInLaw($em, $id, $alreadyLoaded); 
        }

        return $relatives;
    }

    private function loadParents($em, $id, &$alreadyLoaded = null) {
        $this->getLogger()->info("Loading parents for id: " . $id);

        $parentIds = $em->getRepository('NewBundle:IsParent')->loadParents($id);

        $this->getLogger()->info("Found  " . count($parentIds) . " parents");

        return $this->generateRelativesEntry($em, $id, $parentIds, $alreadyLoaded);
    }

    private function loadChildren($em, $id, &$alreadyLoaded = null) {
        $this->getLogger()->info("Loading children for id: " . $id);

        $childrenIds = $em->getRepository('NewBundle:IsParent')->loadChildren($id);
        
        $this->getLogger()->info("Found  " . count($childrenIds) . " children");

        return $this->generateRelativesEntry($em, $id, $childrenIds, $alreadyLoaded);
    }

    private function loadGrandparents($em, $id, &$alreadyLoaded = null) {
        $this->getLogger()->info("Loading grandparents for id: " . $id);

        $grandparentIds = $em->getRepository('NewBundle:IsGrandparent')->loadGrandparents($id);

        $this->getLogger()->info("Found  " . count($grandparentIds) . " grandparents");

        return $this->generateRelativesEntry($em, $id, $grandparentIds, $alreadyLoaded);
    }

    private function loadGrandchildren($em, $id, &$alreadyLoaded = null) {
        $this->getLogger()->info("Loading grandchildren for id: " . $id);

        $grandchildrenIds = $em->getRepository('NewBundle:IsGrandparent')->loadGrandchildren($id);

        $this->getLogger()->info("Found  " . count($grandchildrenIds) . " grandchildren");

        return $this->generateRelativesEntry($em, $id, $grandchildrenIds, $alreadyLoaded);
    }

    private function loadSiblings($em, $id, &$alreadyLoaded = null) {
        $this->getLogger()->info("Loading siblings for id: " . $id);

        $siblingIds = $em->getRepository('NewBundle:IsSibling')->loadSiblings($id);

        $this->getLogger()->info("Found  " . count($siblingIds) . " siblings");

        return $this->generateRelativesEntry($em, $id, $siblingIds, $alreadyLoaded);
    }

    private function loadMarriagePartners($em, $id, &$alreadyLoaded = null) {
        $this->getLogger()->info("Loading marriage partners for id: " . $id);
        
        $marriagePartnerIds = $em->getRepository('NewBundle:Wedding')->loadMarriagePartners($id);

        $this->getLogger()->info("Found  " . count($marriagePartnerIds) . " marriage partners");

        return $this->generateRelativesEntry($em, $id, $marriagePartnerIds, $alreadyLoaded);
    }

    private function loadParentsInLaw($em, $id, &$alreadyLoaded = null) {
        $this->getLogger()->info("Loading parentInLaw for id: " . $id);

        $parentsInLawIds = $em->getRepository('NewBundle:IsParentInLaw')->loadParentsInLaw($id);
        
        $this->getLogger()->info("Found  " . count($parentsInLawIds) . " parentInLaw");

        return $this->generateRelativesEntry($em, $id, $parentsInLawIds, $alreadyLoaded);
    }

    private function loadChildrenInLaw($em, $id, &$alreadyLoaded = null) {
        $this->getLogger()->info("Loading childrenInLaw for id: " . $id);
        
        $childrenInLawIds = $em->getRepository('NewBundle:IsParentInLaw')->loadChildrenInLaw($id);

        $this->getLogger()->info("Found  " . count($childrenInLawIds) . " childrenInLaw");

        return $this->generateRelativesEntry($em, $id, $childrenInLawIds, $alreadyLoaded);
    }

    private function generateRelativesEntry($em, $id, $relativeEntries, &$alreadyLoaded = null) {
        $relativesArray = array();

        for ($i = 0; $i < count($relativeEntries); $i++) {
            $idOfRelative = $this->getRelativeId($id, $relativeEntries[$i]);
            $entry = array();
            $entry["id"] = $idOfRelative;
            $entry["relation"] = $relativeEntries[$i];

            if(is_null($alreadyLoaded)){
                $this->getLogger()->info("Loading Id " . $idOfRelative);
                $person = $this->loadPersonByID($em, $idOfRelative);

                if ($person != null) {
                    $entry["class"] = get_class($person);
                    $entry["person"] = $person;
                }
            }else  if (!in_array($idOfRelative, $alreadyLoaded)) {
                $this->getLogger()->info("Loading Id " . $idOfRelative);
                $alreadyLoaded[] = $idOfRelative;
                $person = $this->loadPersonByID($em, $idOfRelative);

                if ($person != null) {
                    $entry["class"] = get_class($person);
                    $entry["person"] = $person;
                }
            } else {
                $this->getLogger()->info("ID " . $idOfRelative . " was already loaded.");
            }

            $relativesArray[] = $entry;
        }

        return $relativesArray;
    }
    
    private function loadClosestRelatives($em, &$relatives, &$alreadyLoaded,$loadOnlyDirectRelatives, $skipRelativesForPersons) {
        $this->getLogger()->info("Loading Closest Relatives");
        foreach ($relatives as $key => $value) {
            $this->getLogger()->info("Loading Closest Relatives for " . $key);
            for ($i = 0; $i < count($value); $i++) {
                $idOfRelative = $value[$i]["id"];

                if (array_key_exists("person", $value[$i])) {
                    $person = $value[$i]["person"];

                    //if the person is no "person" or skipRelativesForPersons is deactivated
                    if (get_class($person) != PersonClasses::PERSON_CLASS || !$skipRelativesForPersons) {
                        $this->getLogger()->info("Loading relatives of " . $person);
                        $relatives[$key][$i]["relatives"] = $this->internalLoadRelatives($em, $idOfRelative, $alreadyLoaded,$loadOnlyDirectRelatives, $skipRelativesForPersons);
                    } else {
                        $this->getLogger()->info("Skipping loading relatives of " . $person);
                    }
                }
            }
        }
    }

    private function loadRelativesWhichWereNotLoadedYet($em, &$relatives, &$alreadyLoaded,$loadOnlyDirectRelatives, $skipRelativesForPersons) {
        $this->getLogger()->info("Called loadRelativesWhichWereNotLoadedYet");
        foreach ($relatives as $key => $value) {
            for ($i = 0; $i < count($value); $i++) {
                $idOfRelative = $value[$i]["id"];

                if (array_key_exists("person", $value[$i])) {
                    $person = $value[$i]["person"];

                    if (array_key_exists("relatives", $value[$i])) {
                        $this->getLogger()->debug("Going one step deeper");
                        $this->loadRelativesWhichWereNotLoadedYet($em, $value[$i]["relatives"], $alreadyLoaded,$loadOnlyDirectRelatives, $skipRelativesForPersons);
                    } else {
                        //if the person is no "person" or skipRelativesForPersons is deactivated
                        if (get_class($person) != PersonClasses::PERSON_CLASS || !$skipRelativesForPersons) {
                            $this->getLogger()->info("Loading relatives of " . $person);
                            $relatives[$key][$i]["relatives"] = $this->internalLoadRelatives($em, $idOfRelative, $alreadyLoaded,$loadOnlyDirectRelatives, $skipRelativesForPersons);
                        } else {
                            $this->getLogger()->info("Skipping loading relatives of " . $person);
                        }
                    }
                }
            }
        }
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

    private function loadPersonByID($em, $ID, $type = "id") {
        if ($type == 'id') {
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
        } else if ($type == 'oid') {
            $person = $em->getRepository('NewBundle:Person')->findOneByOid($ID);

            if (is_null($person)) {
                //throw exception
            }

            return $person;
        }

        return null;
    }

}
