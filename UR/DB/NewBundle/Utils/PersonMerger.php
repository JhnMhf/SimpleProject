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
        $this->LOGGER = $this->get('monolog.logger.personFusion');
    }

    private function get($identifier){
        return $this->container->get($identifier);
    }
    
    
    public function fusePersons($personOne, $personTwo){
        $this->LOGGER->info("Request for fusing two persons.");
        $this->LOGGER->info("Person 1: ".$personOne);
        $this->LOGGER->info("Person 2: ".$personTwo);
        
        if($personOne->getGender() != $personTwo->getGender()
                && $personOne->getGender() != self::GENDER_UNKNOWN && $personTwo != self::GENDER_UNKNOWN){
            $this->LOGGER->warn("Trying to fuse a man with a woman, is this really right?");
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
        
        
        $this->fuseBasicPerson($dataMaster, $toBeDeleted);
        $this->fuseRelationships($dataMaster, $toBeDeleted);
        
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
    
    private function fuseBasicPerson($dataMaster, $toBeDeleted){
        $this->LOGGER->info("Fusing base person of '".$toBeDeleted . "' into ".$dataMaster);
         
        $dataMaster->setFirstName($this->fuseString($dataMaster->getFirstName(), $toBeDeleted->getFirstName()));
        $dataMaster->setPatronym($this->fuseString($dataMaster->getPatronym(), $toBeDeleted->getPatronym()));
        $dataMaster->setLastName($this->fuseString($dataMaster->getLastName(), $toBeDeleted->getLastName()));
        $dataMaster->setForeName($this->fuseString($dataMaster->getForeName(), $toBeDeleted->getForeName()));
        $dataMaster->setBirthName($this->fuseString($dataMaster->getBirthName(), $toBeDeleted->getBirthName()));
        
        $this->fuseGender($dataMaster, $toBeDeleted);
        $dataMaster->setComment($this->fuseComment($dataMaster->getComment(), $toBeDeleted->getComment()));
        $dataMaster->setBornInMarriage($this->fuseString($dataMaster->getBornInMarriage(), $toBeDeleted->getBornInMarriage()));

        //now the references :(
        $this->fuseBirthId($dataMaster, $toBeDeleted);
        $this->fuseDeathId($dataMaster, $toBeDeleted);
        $this->fuseReligionId($dataMaster, $toBeDeleted);
        $this->fuseNationId($dataMaster, $toBeDeleted);
        $this->fuseBaptismId($dataMaster, $toBeDeleted);
        $this->fuseWorksId($dataMaster, $toBeDeleted);
        $this->fuseStatusId($dataMaster, $toBeDeleted);
        $this->fuseSourceId($dataMaster, $toBeDeleted);
        $this->fuseRoadOfLiveId($dataMaster, $toBeDeleted);
        $this->fuseRankId($dataMaster, $toBeDeleted);
        $this->fusePropertyId($dataMaster, $toBeDeleted);
        $this->fuseHonourId($dataMaster, $toBeDeleted);
        $this->fuseEducationId($dataMaster, $toBeDeleted);
        $this->fuseJobClassId($dataMaster, $toBeDeleted);
        $this->fuseResidenceId($dataMaster, $toBeDeleted);
        
        //weddingid?
        
    }
    
    private function fuseRelationships($dataMaster, $toBeDeleted){
        $this->LOGGER->info("Fusing relationships of '".$toBeDeleted . "' into ".$dataMaster);

    }
    
    private function fuseBirthId($dataMaster, $toBeDeleted){
        $dataMasterReference = $dataMaster->getBirthId();
        $toBeDeletedReference = $toBeDeleted->getBirthId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceFuse($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceFuse($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setBirthId($combinedReference);
    }
    
    private function fuseDeathId($dataMaster, $toBeDeleted){
        $dataMasterReference = $dataMaster->getDeathId();
        $toBeDeletedReference = $toBeDeleted->getDeathId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceFuse($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceFuse($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setDeathId($combinedReference);
    }
    
    private function fuseReligionId($dataMaster, $toBeDeleted){
        $dataMasterReference = $dataMaster->getReligionId();
        $toBeDeletedReference = $toBeDeleted->getReligionId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceFuse($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceFuse($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setReligionId($combinedReference);
    }
    
    private function fuseNationId($dataMaster, $toBeDeleted){
        
        $dataMasterReference = $this->getNation($dataMaster);
        $toBeDeletedReference = $this->getNation($toBeDeleted);
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceFuse($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceFuse($dataMasterReference, $toBeDeletedReference);
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
    
    private function fuseBaptismId($dataMaster, $toBeDeleted){
        $dataMasterReference = $dataMaster->getBaptismId();
        $toBeDeletedReference = $toBeDeleted->getBaptismId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceFuse($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceFuse($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setBaptismId($combinedReference);
    }
    
    private function fuseWorksId($dataMaster, $toBeDeleted){
        $dataMasterReference = $dataMaster->getWorksId();
        $toBeDeletedReference = $toBeDeleted->getWorksId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceFuse($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceFuse($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setWorksId($combinedReference);
    }
    
    private function fuseStatusId($dataMaster, $toBeDeleted){
        $dataMasterReference = $dataMaster->getStatusId();
        $toBeDeletedReference = $toBeDeleted->getStatusId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceFuse($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceFuse($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setStatusId($combinedReference);
    }
       
    private function fuseSourceId($dataMaster, $toBeDeleted){
        $dataMasterReference = $dataMaster->getSourceId();
        $toBeDeletedReference = $toBeDeleted->getSourceId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceFuse($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceFuse($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setSourceId($combinedReference);
    }
    
    private function fuseRoadOfLiveId($dataMaster, $toBeDeleted){
        $dataMasterReference = $dataMaster->getRoadOfLiveId();
        $toBeDeletedReference = $toBeDeleted->getRoadOfLiveId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceFuse($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceFuse($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setRoadOfLiveId($combinedReference);
    }
    
    private function fuseRankId($dataMaster, $toBeDeleted){
        $dataMasterReference = $dataMaster->getRankId();
        $toBeDeletedReference = $toBeDeleted->getRankId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceFuse($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceFuse($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setRankId($combinedReference);
    }
    
    private function fusePropertyId($dataMaster, $toBeDeleted){
        $dataMasterReference = $dataMaster->getPropertyId();
        $toBeDeletedReference = $toBeDeleted->getPropertyId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceFuse($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceFuse($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setPropertyId($combinedReference);
    }
    
    private function fuseHonourId($dataMaster, $toBeDeleted){
        $dataMasterReference = $dataMaster->getHonourId();
        $toBeDeletedReference = $toBeDeleted->getHonourId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceFuse($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceFuse($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setHonourId($combinedReference);
    }
    
    private function fuseEducationId($dataMaster, $toBeDeleted){
        $dataMasterReference = $dataMaster->getEducationId();
        $toBeDeletedReference = $toBeDeleted->getEducationId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceFuse($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceFuse($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setEducationId($combinedReference);
    }
    
    private function fuseJobClassId($dataMaster, $toBeDeleted){
        $dataMasterReference = $dataMaster->getJobClassId();
        $toBeDeletedReference = $toBeDeleted->getJobClassId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceFuse($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceFuse($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setJobClassId($combinedReference);
    }
    
    private function fuseResidenceId($dataMaster, $toBeDeleted){
        $dataMasterReference = $dataMaster->getResidenceId();
        $toBeDeletedReference = $toBeDeleted->getResidenceId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceFuse($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceFuse($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setResidenceId($combinedReference);
    }
    
    private function fuseString($dataMasterString, $toBeDeletedString){
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
        
        $this->LOGGER->debug("Fused to '".$result."'");
        
        return $result;
    }
    
    private function fuseGender($dataMaster, $toBeDeleted){
        if($dataMaster->getGender() != $toBeDeleted->getGender()
                && $dataMaster->getGender() == self::GENDER_UNKNOWN){
                $dataMaster->setGender($toBeDeleted->getGender());
        }
    }
    
    private function fuseComment($dataMasterComment, $toBeDeletedComment){
        return $this->fuseString($dataMasterComment, $toBeDeletedComment);
    }
    
    private function checkForEasyReferenceFuse($dataMasterReference, $toBeDeletedReference){
        $this->LOGGER->debug("Checking for easy reference fuse.");
        if(is_null($toBeDeletedReference) || $toBeDeletedReference == ""){
            return true;
        }else if(is_null($dataMasterReference) || $dataMasterReference == ""){
            return true;
        } 
        
        return false;
    }
    
    private function doEasyReferenceFuse($dataMasterReference, $toBeDeletedReference){
        $this->LOGGER->debug("Doing the easy reference fuse.");
        if(is_null($toBeDeletedReference) || $toBeDeletedReference == ""){
            return $dataMasterReference;
        }else if(is_null($dataMasterReference) || $dataMasterReference == ""){
            return $toBeDeletedReference;
        } 
    }
}
