<?php

namespace UR\AmburgerBundle\Util;

class PersonSaver {
    
    private $LOGGER;
    private $container;
    private $utilObjHandler;
    
    public function __construct($container)
    {
        $this->container = $container;
        $this->LOGGER = $this->get('monolog.logger.default');
        $this->utilObjHandler = $this->get('util_object_handler.service');
    } 
    
    private function get($identifier){
        return $this->container->get($identifier);
    }
    
    public function savePerson($em, $session,$payload, $personEntity){
        $this->LOGGER->info("savePerson called in PersonSaver ".$personEntity->getId());
        $this->preparePerson($em, $personEntity);
        $oldData = null;
        
        //@TODO: Current highest id?
        $existingPerson = $this->loadFinalPersonByOID($personEntity->getOid());
        if(is_null($existingPerson)){
            //@TODO: Necessary only for testing? In the "real" case, the data should already exist and only be updated?
            //first persist if not existant
            $em->persist($personEntity);
            $em->flush();
        } else {
            $serializer = $this->get('serializer');
            $oldData = $serializer->serialize($existingPerson, 'json');
        }
        //merge necessary to set relations right/ update the values

        $em->merge($personEntity);
        $em->flush();
        
        $this->get('correction_change_tracker')->trackChange($personEntity->getOid(), $personEntity->getId(),$session->get('name'),$session->get('userid'), $payload, $oldData);
    }
    
    private function loadFinalPersonByOID($OID){
        
        $finalDBManager = $this->get('doctrine')->getManager('final');
        
        return $finalDBManager->getRepository('NewBundle:Person')->findOneByOid($OID);
    }
    
    private function preparePerson($em, $personEntity){
        
        $this->LOGGER->info("Preparing Person for save: ".$personEntity->getId());
        
        $personEntity->setNation($this->utilObjHandler->getNation($em, $personEntity->getNation()));
        $personEntity->setJob($this->utilObjHandler->getJob($em, $personEntity->getJob()));
        $personEntity->setJobClass($this->utilObjHandler->getJobClass($em, $personEntity->getJobClass()));
        
        if($personEntity->getBaptism() != null){
            $this->prepareBaptism($em, $personEntity->getBaptism());
        }
        
        if($personEntity->getBirth() != null){
            $this->prepareBirth($em, $personEntity->getBirth());
        }
        
        if($personEntity->getDeath() != null){
            $this->prepareDeath($em, $personEntity->getDeath());
        }
        
        $educationsArray = $personEntity->getEducations()->toArray();
        
        for($i = 0; $i < count($educationsArray); $i++){
            $this->prepareEducation($em, $educationsArray[$i]);
            $educationsArray[$i]->setPerson($personEntity);
        }
                
        $honoursArray = $personEntity->getHonours()->toArray();
        
        for($i = 0; $i < count($honoursArray); $i++){
            $this->prepareHonour($em, $honoursArray[$i]);
            $honoursArray[$i]->setPerson($personEntity);
        }
        
                
        $propertyArray = $personEntity->getProperties()->toArray();
        
        for($i = 0; $i < count($propertyArray); $i++){
            $this->prepareProperty($em, $propertyArray[$i]);
            $propertyArray[$i]->setPerson($personEntity);
        }
        
                
        $rankArray = $personEntity->getRanks()->toArray();
        
        for($i = 0; $i < count($rankArray); $i++){
            $this->prepareRank($em, $rankArray[$i]);
            $rankArray[$i]->setPerson($personEntity);
        }
        
        $religionArray = $personEntity->getReligions()->toArray();
        
        for($i = 0; $i < count($religionArray); $i++){
            $religionArray[$i]->setPerson($personEntity);
        }      
        
        
        $residenceArray = $personEntity->getResidences()->toArray();
        
        for($i = 0; $i < count($residenceArray); $i++){
            $this->prepareResidence($em, $residenceArray[$i]);
            $residenceArray[$i]->setPerson($personEntity);
        }
        
                
        $roadOfLifeArray = $personEntity->getRoadOfLife()->toArray();
        
        for($i = 0; $i < count($roadOfLifeArray); $i++){
            $this->prepareRoadOfLife($em, $roadOfLifeArray[$i]);
            $roadOfLifeArray[$i]->setPerson($personEntity);
        }
        
        $sourceArray = $personEntity->getSources()->toArray();
        
        for($i = 0; $i < count($sourceArray); $i++){
            $sourceArray[$i]->setPerson($personEntity);
        }
                
        $statusArray = $personEntity->getStati()->toArray();
        
        for($i = 0; $i < count($statusArray); $i++){
            $this->prepareStatus($em, $statusArray[$i]);
            $statusArray[$i]->setPerson($personEntity);
        }
           
        $worksArray = $personEntity->getWorks()->toArray();
        
        for($i = 0; $i < count($worksArray); $i++){
            $this->prepareWorks($em, $worksArray[$i]);
            $worksArray[$i]->setPerson($personEntity);
        }
    }
    
    private function prepareBaptism($em, $entity){
        $entity->setBaptismLocation($this->getAndEnrichLocation($em, $entity->getBaptismLocation()));
    }
    
    private function prepareBirth($em, $entity){
        $entity->setOriginCountry($this->utilObjHandler->getCountry($em, $entity->getOriginCountry()));
        $entity->setOriginTerritory($this->utilObjHandler->getTerritory($em, $entity->getOriginTerritory()));
        $entity->setOriginLocation($this->getAndEnrichLocation($em, $entity->getOriginLocation()));
        $entity->setBirthCountry($this->utilObjHandler->getCountry($em, $entity->getBirthCountry()));
        $entity->setBirthTerritory($this->utilObjHandler->getTerritory($em, $entity->getBirthTerritory()));
        $entity->setBirthLocation($this->getAndEnrichLocation($em, $entity->getBirthLocation()));
    }
    
    private function prepareDeath($em, $entity){
        $entity->setDeathCountry($this->utilObjHandler->getCountry($em, $entity->getDeathCountry()));
        $entity->setTerritoryOfDeath($this->utilObjHandler->getTerritory($em, $entity->getTerritoryOfDeath()));
        $entity->setDeathLocation($this->getAndEnrichLocation($em, $entity->getDeathLocation()));
    }
    
    private function prepareEducation($em, $entity){
        $entity->setCountry($this->utilObjHandler->getCountry($em, $entity->getCountry()));
        $entity->setTerritory($this->utilObjHandler->getTerritory($em, $entity->getTerritory()));
        $entity->setLocation($this->getAndEnrichLocation($em, $entity->getLocation()));
        $entity->setGraduationLocation($this->getAndEnrichLocation($em, $entity->getGraduationLocation()));
    }
    
    private function prepareHonour($em, $entity){
        $entity->setCountry($this->utilObjHandler->getCountry($em, $entity->getCountry()));
        $entity->setTerritory($this->utilObjHandler->getTerritory($em, $entity->getTerritory()));
        $entity->setLocation($this->getAndEnrichLocation($em, $entity->getLocation()));
    }
    
    private function prepareProperty($em, $entity){
        $entity->setCountry($this->utilObjHandler->getCountry($em, $entity->getCountry()));
        $entity->setTerritory($this->utilObjHandler->getTerritory($em, $entity->getTerritory()));
        $entity->setLocation($this->getAndEnrichLocation($em, $entity->getLocation()));
    }
    
    private function prepareRank($em, $entity){
        $entity->setCountry($this->utilObjHandler->getCountry($em, $entity->getCountry()));
        $entity->setTerritory($this->utilObjHandler->getTerritory($em, $entity->getTerritory()));
        $entity->setLocation($this->getAndEnrichLocation($em, $entity->getLocation()));        
    }
    
    private function prepareResidence($em, $entity){
        $entity->setResidenceCountry($this->utilObjHandler->getCountry($em, $entity->getResidenceCountry()));
        $entity->setResidenceTerritory($this->utilObjHandler->getTerritory($em, $entity->getResidenceTerritory()));
        $entity->setResidenceLocation($this->getAndEnrichLocation($em, $entity->getResidenceLocation()));       
    }
    
    private function prepareRoadOfLife($em, $entity){
        $entity->setJob($this->utilObjHandler->getJob($em, $entity->getJob()));
        $entity->setOriginCountry($this->utilObjHandler->getCountry($em, $entity->getOriginCountry()));
        $entity->setOriginTerritory($this->utilObjHandler->getTerritory($em, $entity->getOriginTerritory()));
        $entity->setCountry($this->utilObjHandler->getCountry($em, $entity->getCountry()));
        $entity->setTerritory($this->utilObjHandler->getTerritory($em, $entity->getTerritory()));
        $entity->setLocation($this->getAndEnrichLocation($em, $entity->getLocation()));
    }
    
    private function prepareStatus($em, $entity){
        $entity->setCountry($this->utilObjHandler->getCountry($em, $entity->getCountry()));
        $entity->setTerritory($this->utilObjHandler->getTerritory($em, $entity->getTerritory()));
        $entity->setLocation($this->getAndEnrichLocation($em, $entity->getLocation()));
    }
    
    private function prepareWorks($em, $entity){
        $entity->setCountry($this->utilObjHandler->getCountry($em, $entity->getCountry()));
        $entity->setTerritory($this->utilObjHandler->getTerritory($em, $entity->getTerritory()));
        $entity->setLocation($this->getAndEnrichLocation($em, $entity->getLocation()));
    }
    
    private function getAndEnrichLocation($em, $location){
        if(is_null($location)){
            return null;
        }
        
        $this->LOGGER->info("Calling getAndEnrichLocation");
        
        $locationFromDB = $this->utilObjHandler->getLocation($em, $location);
        
        if(is_null($locationFromDB)){
            return $location;
        } else if(!is_null($location->getLatitude()) && !is_null($location->getLongitude())
                && $location->getLatitude() != 0 && $location->getLongitude() != 0
                && $location->getLatitude() != $locationFromDB->getLatitude()
                && $location->getLongitude() != $locationFromDB->getLongitude()){
            $this->LOGGER->info("New/changed latitude/ longitude found.");
            $this->LOGGER->debug("Latitude: '".$location->getLatitude()."' Longitude: '".$location->getLongitude()."'");
            
            $locationFromDB->setLatitude($location->getLatitude());
            $locationFromDB->setLongitude($location->getLongitude());
        }
        
        
        return $locationFromDB;
    }
}

