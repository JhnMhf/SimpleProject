<?php

namespace UR\DB\NewBundle\Utils;

/**
 * Description of PersonMerger
 *
 * 
 */
class PersonMerger {
    const PERSON_CLASS = "UR\DB\NewBundle\Entity\Person";
    const RELATIVE_CLASS = "UR\DB\NewBundle\Entity\Relative";
    const PARTNER_CLASS = "UR\DB\NewBundle\Entity\Partner";
    
    const GENDER_UNKNOWN = 0;
    const GENDER_MAN = 1;
    const GENDER_WOMAN = 2;

    private $LOGGER;

    private $container;
    private $newDBManager;

    public function __construct($container)
    {
        $this->container = $container;
        $this->newDBManager = $this->get('doctrine')->getManager('new');
        $this->LOGGER = $this->get('monolog.logger.personMerging');
    }

    private function get($identifier){
        return $this->container->get($identifier);
    }
    
    
    public function mergePersons($personOne, $personTwo){
        $this->LOGGER->info("Request for fusing two persons.");
        $this->LOGGER->info("Person 1: ".$personOne);
        $this->LOGGER->info("Person 2: ".$personTwo);
        
        if($personOne->getGender() != $personTwo->getGender()
                && $personOne->getGender() != self::GENDER_UNKNOWN && $personTwo != self::GENDER_UNKNOWN){
            $this->LOGGER->warn("Trying to merge a man with a woman, is this really right?");
        }
        
        $dataMaster = $this->determineDatamaster($personOne, $personTwo);
        $toBeDeleted = $this->determineToBeRemoved($personOne, $personTwo);
        
        $this->LOGGER->info("The data will be combined in: ".$dataMaster);
        $this->LOGGER->info("The object '".$dataMaster."' will be removed.");
       
        if(get_class($personOne) == self::PERSON_CLASS
                && get_class($personTwo) == self::PERSON_CLASS){
            //what to do with the oid?
             $this->LOGGER->info("Found two PersonObjects. The oid, control and complete fields must be handled");
        }
        
        
        $this->mergeBasicPerson($dataMaster, $toBeDeleted);
        $this->mergeRelationships($dataMaster, $toBeDeleted);
        
        //save new combined person
        //and delete old
        $this->newDBManager->persist($dataMaster);
        $this->removeObject($toBeDeleted);
        $this->newDBManager->flush();
        
        return $dataMaster;
    }
    
    private function determineDatamaster($personOne, $personTwo){
        if(get_class($personTwo) == self::PERSON_CLASS
                && get_class($personOne) != self::PERSON_CLASS){
            return $personTwo;
        }
        
        return $personOne;
    }
    
   private function determineToBeRemoved($personOne, $personTwo){
        if(get_class($personTwo) == self::PERSON_CLASS
                && get_class($personOne) != self::PERSON_CLASS){
            return $personOne;
        }
        
        return $personTwo;
    }
    
    private function removeObject($toBeDeleted){
        $this->LOGGER->info("Now removing: ".$toBeDeleted);
        //remove obj itself
        $this->newDBManager->remove($toBeDeleted);
        
        //remove all the references birth etc.
        
        //remove relationsships?
        
    }
    
    private function mergeBasicPerson($dataMaster, $toBeDeleted){
        $this->LOGGER->info("Fusing base person of '".$toBeDeleted . "' into ".$dataMaster);
         
        $dataMaster->setFirstName($this->mergeString($dataMaster->getFirstName(), $toBeDeleted->getFirstName()));
        $dataMaster->setPatronym($this->mergeString($dataMaster->getPatronym(), $toBeDeleted->getPatronym()));
        $dataMaster->setLastName($this->mergeString($dataMaster->getLastName(), $toBeDeleted->getLastName()));
        $dataMaster->setForeName($this->mergeString($dataMaster->getForeName(), $toBeDeleted->getForeName()));
        $dataMaster->setBirthName($this->mergeString($dataMaster->getBirthName(), $toBeDeleted->getBirthName()));
        
        $this->mergeGender($dataMaster, $toBeDeleted);
        $dataMaster->setComment($this->mergeComment($dataMaster->getComment(), $toBeDeleted->getComment()));
        $dataMaster->setBornInMarriage($this->mergeString($dataMaster->getBornInMarriage(), $toBeDeleted->getBornInMarriage()));

        //now the references :(
        $this->mergeBirthId($dataMaster, $toBeDeleted);
        $this->mergeDeathId($dataMaster, $toBeDeleted);
        $this->mergeReligionId($dataMaster, $toBeDeleted);
        $this->mergeNationId($dataMaster, $toBeDeleted);
        $this->mergeBaptismId($dataMaster, $toBeDeleted);
        $this->mergeWorksId($dataMaster, $toBeDeleted);
        $this->mergeStatusId($dataMaster, $toBeDeleted);
        $this->mergeSourceId($dataMaster, $toBeDeleted);
        $this->mergeRoadOfLiveId($dataMaster, $toBeDeleted);
        $this->mergeRankId($dataMaster, $toBeDeleted);
        $this->mergePropertyId($dataMaster, $toBeDeleted);
        $this->mergeHonourId($dataMaster, $toBeDeleted);
        $this->mergeEducationId($dataMaster, $toBeDeleted);
        $this->mergeJobClassId($dataMaster, $toBeDeleted);
        $this->mergeResidenceId($dataMaster, $toBeDeleted);
        
        //weddingid?
        
    }
    
    private function mergeRelationships($dataMaster, $toBeDeleted){
        $this->LOGGER->info("Fusing relationships of '".$toBeDeleted . "' into ".$dataMaster);

        /*
         * it is necessary to migrate nonexistant relationships from 
         * toBeDeleted into Master.
         * But it is also necessary to merge duplicate relationsships, 
         * and check their relationships...
         * In this case we must be remember to ignore the relationship
         * to the "calling" person or we will have a cycle
         */

    }
    
    private function mergeBirthId($dataMaster, $toBeDeleted){
        $dataMasterReference = $dataMaster->getBirthId();
        $toBeDeletedReference = $toBeDeleted->getBirthId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceMerge($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceMerge($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setBirthId($combinedReference);
    }
    
    private function mergeDeathId($dataMaster, $toBeDeleted){
        $dataMasterReference = $dataMaster->getDeathId();
        $toBeDeletedReference = $toBeDeleted->getDeathId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceMerge($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceMerge($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setDeathId($combinedReference);
    }
    
    private function mergeReligionId($dataMaster, $toBeDeleted){
        $dataMasterReference = $dataMaster->getReligionId();
        $toBeDeletedReference = $toBeDeleted->getReligionId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceMerge($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceMerge($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setReligionId($combinedReference);
    }
    
    private function mergeNationId($dataMaster, $toBeDeleted){
        
        $dataMasterReference = $this->getNation($dataMaster);
        $toBeDeletedReference = $this->getNation($toBeDeleted);
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceMerge($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceMerge($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setOriginalNationId($combinedReference);
    }
    
    private function getNation($person){
        switch(get_class($person)){
            case self::PERSON_CLASS:
                return $person->getOriginalNationId();
            default:
                return $person->getNationId();
        }
    }
    
    private function mergeBaptismId($dataMaster, $toBeDeleted){
        $dataMasterReference = $dataMaster->getBaptismId();
        $toBeDeletedReference = $toBeDeleted->getBaptismId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceMerge($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceMerge($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setBaptismId($combinedReference);
    }
    
    private function mergeWorksId($dataMaster, $toBeDeleted){
        $dataMasterReference = $dataMaster->getWorksId();
        $toBeDeletedReference = $toBeDeleted->getWorksId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceMerge($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceMerge($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setWorksId($combinedReference);
    }
    
    private function mergeStatusId($dataMaster, $toBeDeleted){
        $dataMasterReference = $dataMaster->getStatusId();
        $toBeDeletedReference = $toBeDeleted->getStatusId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceMerge($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceMerge($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setStatusId($combinedReference);
    }
       
    private function mergeSourceId($dataMaster, $toBeDeleted){
        $dataMasterReference = $dataMaster->getSourceId();
        $toBeDeletedReference = $toBeDeleted->getSourceId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceMerge($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceMerge($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setSourceId($combinedReference);
    }
    
    private function mergeRoadOfLiveId($dataMaster, $toBeDeleted){
        $dataMasterReference = $dataMaster->getRoadOfLiveId();
        $toBeDeletedReference = $toBeDeleted->getRoadOfLiveId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceMerge($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceMerge($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setRoadOfLiveId($combinedReference);
    }
    
    private function mergeRankId($dataMaster, $toBeDeleted){
        $dataMasterReference = $dataMaster->getRankId();
        $toBeDeletedReference = $toBeDeleted->getRankId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceMerge($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceMerge($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setRankId($combinedReference);
    }
    
    private function mergePropertyId($dataMaster, $toBeDeleted){
        $dataMasterReference = $dataMaster->getPropertyId();
        $toBeDeletedReference = $toBeDeleted->getPropertyId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceMerge($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceMerge($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setPropertyId($combinedReference);
    }
    
    private function mergeHonourId($dataMaster, $toBeDeleted){
        $dataMasterReference = $dataMaster->getHonourId();
        $toBeDeletedReference = $toBeDeleted->getHonourId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceMerge($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceMerge($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setHonourId($combinedReference);
    }
    
    private function mergeEducationId($dataMaster, $toBeDeleted){
        $dataMasterReference = $dataMaster->getEducationId();
        $toBeDeletedReference = $toBeDeleted->getEducationId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceMerge($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceMerge($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setEducationId($combinedReference);
    }
    
    private function mergeJobClassId($dataMaster, $toBeDeleted){
        $dataMasterReference = $dataMaster->getJobClassId();
        $toBeDeletedReference = $toBeDeleted->getJobClassId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceMerge($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceMerge($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setJobClassId($combinedReference);
    }
    
    private function mergeResidenceId($dataMaster, $toBeDeleted){
        $dataMasterReference = $dataMaster->getResidenceId();
        $toBeDeletedReference = $toBeDeleted->getResidenceId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceMerge($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceMerge($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setResidenceId($combinedReference);
    }
    
    private function mergeString($dataMasterString, $toBeDeletedString){
        $this->LOGGER->debug("Fusing Strings... '".$dataMasterString."' with '".$toBeDeletedString."'");
        $result = $dataMasterString;
        if(is_null($toBeDeletedString) || $toBeDeletedString == ""){
            //do nothing, since the default is the dataMasterstring
        }else if(is_null($dataMasterString) || $dataMasterString == ""){
            $result = $toBeDeletedString;
        } else if(strcasecmp($dataMasterString, $toBeDeletedString) != 0){
            $result = $dataMasterString . " ODER ".$toBeDeletedString;
        }
        //else ==> they are the same, do nothing just use the dataMasterString
        
        $this->LOGGER->debug("Merged to '".$result."'");
        
        return $result;
    }
    
    private function mergeGender($dataMaster, $toBeDeleted){
        if($dataMaster->getGender() != $toBeDeleted->getGender()
                && $dataMaster->getGender() == self::GENDER_UNKNOWN){
                $dataMaster->setGender($toBeDeleted->getGender());
        }
    }
    
    private function mergeComment($dataMasterComment, $toBeDeletedComment){
        return $this->mergeString($dataMasterComment, $toBeDeletedComment);
    }
    
    private function checkForEasyReferenceMerge($dataMasterReference, $toBeDeletedReference){
        $this->LOGGER->debug("Checking for easy reference merge.");
        if(is_null($toBeDeletedReference) || $toBeDeletedReference == ""){
            return true;
        }else if(is_null($dataMasterReference) || $dataMasterReference == ""){
            return true;
        } 
        
        return false;
    }
    
    private function doEasyReferenceMerge($dataMasterReference, $toBeDeletedReference){
        $this->LOGGER->debug("Doing the easy reference merge.");
        if(is_null($toBeDeletedReference) || $toBeDeletedReference == ""){
            return $dataMasterReference;
        }else if(is_null($dataMasterReference) || $dataMasterReference == ""){
            return $toBeDeletedReference;
        } 
    }
}
