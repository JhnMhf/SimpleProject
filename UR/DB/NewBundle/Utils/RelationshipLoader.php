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

    private function getLogger() {
        if (is_null($this->LOGGER)) {
            $this->LOGGER = $this->get('monolog.logger.default');
        }

        return $this->LOGGER;
    }

    private function getDBManager() {
        if (is_null($this->newDBManager)) {
            $this->newDBManager = $this->get('doctrine')->getManager('new');
        }

        return $this->newDBManager;
    }
    
    public function loadOnlyDirectRelatives($id){
        $this->getLogger()->info("Loading direct relatives for ID: " . $id);
        
        $relatives = array();

        $relatives["parents"] = $this->loadParents($id);
        $relatives["children"] = $this->loadChildren($id);
        $relatives["siblings"] = $this->loadSiblings($id);
        $relatives["marriagePartners"] = $this->loadMarriagePartners($id);

        return $relatives;
    }
    
    //Passing the arrays as reference is necessary, since they will be updated!

    public function loadRelatives($id, $skipRelativesForPersons = true) {
        $this->getLogger()->info("loadRelatives: " . $id);
        $alreadyLoaded = array();
        $relatives = $this->internalLoadRelatives($id, $alreadyLoaded);

        $this->loadClosestRelatives($relatives, $alreadyLoaded, $skipRelativesForPersons);

        $numberOfCurrentlyLoadedRelatives = count($alreadyLoaded);

        do {
            $numberOfCurrentlyLoadedRelatives = count($alreadyLoaded);
            $this->loadRelativesWhichWereNotLoadedYet($relatives, $alreadyLoaded, $skipRelativesForPersons);
        } while ($numberOfCurrentlyLoadedRelatives < count($alreadyLoaded));

        $this->getLogger()->info("loadRelatives end: " . $id);
            
        return $relatives;
    }
    
    public function loadOnlyRelativeIds($id, $skipRelativesForPersons = true) {
        $this->getLogger()->info("loadOnlyRelativeIds: " . $id);
        $alreadyLoaded = array();
        $relatives = $this->internalLoadRelatives($id, $alreadyLoaded);

        $this->loadClosestRelatives($relatives, $alreadyLoaded, $skipRelativesForPersons);

        $numberOfCurrentlyLoadedRelatives = count($alreadyLoaded);

        do {
            $numberOfCurrentlyLoadedRelatives = count($alreadyLoaded);
            $this->loadRelativesWhichWereNotLoadedYet($relatives, $alreadyLoaded, $skipRelativesForPersons);
        } while ($numberOfCurrentlyLoadedRelatives < count($alreadyLoaded));

        $this->getLogger()->info("loadOnlyRelativeIds end: " . $id);

        return $alreadyLoaded;
    }

    private function internalLoadRelatives($id, &$alreadyLoaded) {
        $this->getLogger()->info("Internally loading relatives for ID: " . $id);
        if (!in_array($id, $alreadyLoaded)) {
            $alreadyLoaded[] = $id;
        }

        $relatives = array();

        $relatives["parents"] = $this->loadParents($id, $alreadyLoaded);
        $relatives["children"] = $this->loadChildren($id, $alreadyLoaded);
        $relatives["grandparents"] = $this->loadGrandparents($id, $alreadyLoaded);
        $relatives["grandchildren"] = $this->loadGrandchildren($id, $alreadyLoaded);
        $relatives["siblings"] = $this->loadSiblings($id, $alreadyLoaded);
        $relatives["marriagePartners"] = $this->loadMarriagePartners($id, $alreadyLoaded);
        $relatives["parentsInLaw"] = $this->loadParentsInLaw($id, $alreadyLoaded);
        $relatives["childrenInLaw"] = $this->loadChildrenInLaw($id, $alreadyLoaded);

        return $relatives;
    }

    private function loadParents($id, &$alreadyLoaded = null) {
        $this->getLogger()->info("Loading parents for id: " . $id);

        $parentIds = $this->getDBManager()->getRepository('NewBundle:IsParent')->loadParents($id);

        $this->getLogger()->info("Found  " . count($parentIds) . " parents");

        return $this->generateRelativesEntry($id, $parentIds, $alreadyLoaded);
    }

    private function loadChildren($id, &$alreadyLoaded = null) {
        $this->getLogger()->info("Loading children for id: " . $id);

        $childrenIds = $this->getDBManager()->getRepository('NewBundle:IsParent')->loadChildren($id);
        
        $this->getLogger()->info("Found  " . count($childrenIds) . " children");

        return $this->generateRelativesEntry($id, $childrenIds, $alreadyLoaded);
    }

    private function loadGrandparents($id, &$alreadyLoaded) {
        $this->getLogger()->info("Loading grandparents for id: " . $id);

        $grandparentIds = $this->getDBManager()->getRepository('NewBundle:IsGrandparent')->loadGrandparents($id);

        $this->getLogger()->info("Found  " . count($grandparentIds) . " grandparents");

        return $this->generateRelativesEntry($id, $grandparentIds, $alreadyLoaded);
    }

    private function loadGrandchildren($id, &$alreadyLoaded) {
        $this->getLogger()->info("Loading grandchildren for id: " . $id);

        $grandchildrenIds = $this->getDBManager()->getRepository('NewBundle:IsGrandparent')->loadGrandchildren($id);

        $this->getLogger()->info("Found  " . count($grandchildrenIds) . " grandchildren");

        return $this->generateRelativesEntry($id, $grandchildrenIds, $alreadyLoaded);
    }

    private function loadSiblings($id, &$alreadyLoaded = null) {
        $this->getLogger()->info("Loading siblings for id: " . $id);

        $siblingIds = $this->getDBManager()->getRepository('NewBundle:IsSibling')->loadSiblings($id);

        $this->getLogger()->info("Found  " . count($siblingIds) . " siblings");

        return $this->generateRelativesEntry($id, $siblingIds, $alreadyLoaded);
    }

    private function loadMarriagePartners($id, &$alreadyLoaded = null) {
        $this->getLogger()->info("Loading marriage partners for id: " . $id);
        
        $marriagePartnerIds = $this->getDBManager()->getRepository('NewBundle:Wedding')->loadMarriagePartners($id);

        $this->getLogger()->info("Found  " . count($marriagePartnerIds) . " marriage partners");

        return $this->generateRelativesEntry($id, $marriagePartnerIds, $alreadyLoaded);
    }

    private function loadParentsInLaw($id, &$alreadyLoaded) {
        $this->getLogger()->info("Loading parentInLaw for id: " . $id);

        $parentsInLawIds = $this->getDBManager()->getRepository('NewBundle:IsParentInLaw')->loadParentsInLaw($id);
        
        $this->getLogger()->info("Found  " . count($parentsInLawIds) . " parentInLaw");

        return $this->generateRelativesEntry($id, $parentsInLawIds, $alreadyLoaded);
    }

    private function loadChildrenInLaw($id, &$alreadyLoaded) {
        $this->getLogger()->info("Loading childrenInLaw for id: " . $id);
        
        $childrenInLawIds = $this->getDBManager()->getRepository('NewBundle:IsParentInLaw')->loadChildrenInLaw($id);

        $this->getLogger()->info("Found  " . count($childrenInLawIds) . " childrenInLaw");

        return $this->generateRelativesEntry($id, $childrenInLawIds, $alreadyLoaded);
    }

    private function generateRelativesEntry($id, $relativesIds, &$alreadyLoaded = null) {
        $relativesArray = array();

        for ($i = 0; $i < count($relativesIds); $i++) {
            $idOfRelative = $this->getRelativeId($id, $relativesIds[$i]);
            $entry = array();
            $entry["id"] = $idOfRelative;
            $entry["relation"] = $relativesIds[$i];

            if(is_null($alreadyLoaded)){
                $this->getLogger()->info("Loading Id " . $idOfRelative);
                $person = $this->loadPersonByID($idOfRelative);

                if ($person != null) {
                    $entry["class"] = get_class($person);
                    $entry["person"] = $person;
                }
            }else  if (!in_array($idOfRelative, $alreadyLoaded)) {
                $this->getLogger()->info("Loading Id " . $idOfRelative);
                $alreadyLoaded[] = $idOfRelative;
                $person = $this->loadPersonByID($idOfRelative);

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

    private function loadClosestRelatives(&$relatives, &$alreadyLoaded, $skipRelativesForPersons) {
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
                        $relatives[$key][$i]["relatives"] = $this->internalLoadRelatives($idOfRelative, $alreadyLoaded, $skipRelativesForPersons);
                    } else {
                        $this->getLogger()->info("Skipping loading relatives of " . $person);
                    }
                }
            }
        }
    }

    private function loadRelativesWhichWereNotLoadedYet(&$relatives, &$alreadyLoaded, $skipRelativesForPersons) {
        $this->getLogger()->info("Called loadRelativesWhichWereNotLoadedYet");
        foreach ($relatives as $key => $value) {
            for ($i = 0; $i < count($value); $i++) {
                $idOfRelative = $value[$i]["id"];

                if (array_key_exists("person", $value[$i])) {
                    $person = $value[$i]["person"];

                    if (array_key_exists("relatives", $value[$i])) {
                        $this->getLogger()->debug("Going one step deeper");
                        $this->loadRelativesWhichWereNotLoadedYet($value[$i]["relatives"], $alreadyLoaded, $skipRelativesForPersons);
                    } else {
                        //if the person is no "person" or skipRelativesForPersons is deactivated
                        if (get_class($person) != PersonClasses::PERSON_CLASS || !$skipRelativesForPersons) {
                            $this->getLogger()->info("Loading relatives of " . $person);
                            $relatives[$key][$i]["relatives"] = $this->internalLoadRelatives($idOfRelative, $alreadyLoaded, $skipRelativesForPersons);
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

    private function loadPersonByID($ID, $type = "id") {
        if ($type == 'id') {
            $person = $this->getDBManager()->getRepository('NewBundle:Person')->findOneById($ID);

            if (is_null($person)) {
                $person = $this->getDBManager()->getRepository('NewBundle:Relative')->findOneById($ID);
            }

            if (is_null($person)) {
                $person = $this->getDBManager()->getRepository('NewBundle:Partner')->findOneById($ID);
            }

            if (is_null($person)) {
                //throw exception
            }

            return $person;
        } else if ($type == 'oid') {
            $person = $this->getDBManager()->getRepository('NewBundle:Person')->findOneByOid($ID);

            if (is_null($person)) {
                //throw exception
            }

            return $person;
        }

        return null;
    }

}
