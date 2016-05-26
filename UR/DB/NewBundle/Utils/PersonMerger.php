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
    private $compareService;

    public function __construct($container)
    {
        $this->container = $container;
        $this->newDBManager = $this->get('doctrine')->getManager('new');
        $this->LOGGER = $this->get('monolog.logger.personMerging');
        $this->compareService = $this->get("comparer.service");
    }

    private function get($identifier){
        return $this->container->get($identifier);
    }
    
    
    //@TODO: Finish Personmergin/ fusion
    //Its not only necessary to finish the method but to call it from MigratController
    public function mergePersons(\UR\DB\NewBundle\Entity\BasePerson $personOne,\UR\DB\NewBundle\Entity\BasePerson $personTwo){
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
    
    private function determineDatamaster(\UR\DB\NewBundle\Entity\BasePerson $personOne,\UR\DB\NewBundle\Entity\BasePerson $personTwo){
        if(get_class($personTwo) == self::PERSON_CLASS
                && get_class($personOne) != self::PERSON_CLASS){
            return $personTwo;
        }
        
        return $personOne;
    }
    
   private function determineToBeRemoved(\UR\DB\NewBundle\Entity\BasePerson $personOne,\UR\DB\NewBundle\Entity\BasePerson $personTwo){
        if(get_class($personTwo) == self::PERSON_CLASS
                && get_class($personOne) != self::PERSON_CLASS){
            return $personOne;
        }
        
        return $personTwo;
    }
    
    private function removeObject(\UR\DB\NewBundle\Entity\BasePerson $toBeDeleted){
        $this->LOGGER->info("Now removing: ".$toBeDeleted);
        //remove obj itself
        $this->newDBManager->remove($toBeDeleted);
        
        //remove all the references birth etc.
        
        //remove relationsships?
        
    }
    
    private function mergeBasicPerson(\UR\DB\NewBundle\Entity\BasePerson $dataMaster,\UR\DB\NewBundle\Entity\BasePerson $toBeDeleted){
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
        $this->mergeBirth($dataMaster, $toBeDeleted);
        $this->mergeDeath($dataMaster, $toBeDeleted);
        $this->mergeBaptism($dataMaster, $toBeDeleted);
        $this->mergeReligion($dataMaster, $toBeDeleted);
        //@TODO: Reenable
        /*
        $this->mergeNation($dataMaster, $toBeDeleted);
        $this->mergeWorks($dataMaster, $toBeDeleted);
        $this->mergeStatus($dataMaster, $toBeDeleted);
        $this->mergeSource($dataMaster, $toBeDeleted);
        $this->mergeRoadOfLive($dataMaster, $toBeDeleted);
        $this->mergeRank($dataMaster, $toBeDeleted);
        $this->mergeProperty($dataMaster, $toBeDeleted);
        $this->mergeHonour($dataMaster, $toBeDeleted);
        $this->mergeEducation($dataMaster, $toBeDeleted);
        $this->mergeJobClass($dataMaster, $toBeDeleted);
        $this->mergeResidence($dataMaster, $toBeDeleted);
         */
        
        //weddingid?
        
    }
    
    private function mergeRelationships(\UR\DB\NewBundle\Entity\BasePerson $dataMaster,\UR\DB\NewBundle\Entity\BasePerson $toBeDeleted){
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
    
    private function mergeBirth(\UR\DB\NewBundle\Entity\BasePerson $dataMaster,\UR\DB\NewBundle\Entity\BasePerson $toBeDeleted){
        $this->LOGGER->info("Fusing Birth of '".$toBeDeleted . "' into ".$dataMaster);
        $dataMasterBirth = $dataMaster->getBirth();
        $toBeDeletedBirth = $toBeDeleted->getBirth();
        
        if($dataMasterBirth != null && $toBeDeletedBirth != null){
            $dataMasterBirth->setOriginCountry($this->mergeCountryObject($dataMasterBirth->getOriginCountry(), $toBeDeletedBirth->getOriginCountry()));
            $dataMasterBirth->setOriginTerritory($this->mergeTerritoryObject($dataMasterBirth->getOriginTerritory(), $toBeDeletedBirth->getOriginTerritory()));
            $dataMasterBirth->setOriginLocation($this->mergeLocationObject($dataMasterBirth->getOriginLocation(), $toBeDeletedBirth->getOriginLocation()));
            $dataMasterBirth->setBirthCountry($this->mergeCountryObject($dataMasterBirth->getBirthCountry(), $toBeDeletedBirth->getBirthCountry()));
            $dataMasterBirth->setBirthTerritory($this->mergeTerritoryObject($dataMasterBirth->getBirthTerritory(), $toBeDeletedBirth->getBirthTerritory()));
            $dataMasterBirth->setBirthLocation($this->mergeLocationObject($dataMasterBirth->getBirthLocation(), $toBeDeletedBirth->getBirthLocation()));
            $dataMasterBirth->setBirthDate($this->mergeDateReference($dataMasterBirth->getBirthDate(), $toBeDeletedBirth->getBirthDate()));
            $dataMasterBirth->setComment($this->mergeComment($dataMasterBirth->getComment(), $toBeDeletedBirth->getComment()));

            $dataMaster->setBirth($dataMasterBirth);
        } else if ($toBeDeletedBirth != null){
            $dataMaster->setBirth($toBeDeletedBirth);
        }
    }
    
    private function mergeBaptism(\UR\DB\NewBundle\Entity\BasePerson $dataMaster,\UR\DB\NewBundle\Entity\BasePerson $toBeDeleted){
        $this->LOGGER->info("Fusing Baptism of '".$toBeDeleted . "' into ".$dataMaster);
        $dataMasterBaptism = $dataMaster->getBaptism();
        $toBeDeletedBaptism = $toBeDeleted->getBaptism();
        
        if($dataMasterBaptism != null && $toBeDeletedBaptism != null){
            $dataMasterBaptism->setOriginLocation($this->mergeLocationObject($dataMasterBaptism->getBaptismLocation(), $toBeDeletedBaptism->getBaptismLocation()));
            $dataMasterBaptism->setBirthDate($this->mergeDateReference($dataMasterBaptism->getBaptismDate(), $toBeDeletedBaptism->getBaptismDate()));
        
            $dataMaster->setBirth($dataMasterBaptism);
        } else if ($toBeDeletedBaptism != null){
            $dataMaster->setBirth($toBeDeletedBaptism);
        }
    }
    
    private function mergeDeath(\UR\DB\NewBundle\Entity\BasePerson $dataMaster,\UR\DB\NewBundle\Entity\BasePerson $toBeDeleted){
        $this->LOGGER->info("Fusing Death of '".$toBeDeleted . "' into ".$dataMaster);
        $dataMasterDeath = $dataMaster->getDeath();
        $toBeDeletedDeath = $toBeDeleted->getDeath();
        
        if($dataMasterDeath != null && $toBeDeletedDeath != null){
            $dataMasterDeath->setDeathCountry($this->mergeCountryObject($dataMasterDeath->getDeathCountry(),$toBeDeletedDeath->getDeathCountry()));
            $dataMasterDeath->setTerritoryOfDeath($this->mergeTerritoryObject($dataMasterDeath->getTerritoryOfDeath(),$toBeDeletedDeath->getTerritoryOfDeath()));
            $dataMasterDeath->setDeathLocation($this->mergeLocationObject($dataMasterDeath->getDeathLocation(),$toBeDeletedDeath->getDeathLocation()));
            $dataMasterDeath->setDeathDate($this->mergeDateReference($dataMasterDeath->getDeathDate(),$toBeDeletedDeath->getDeathDate()));
            $dataMasterDeath->setCauseOfDeath($this->mergeStrings($dataMasterDeath->getCauseOfDeath(),$toBeDeletedDeath->getCauseOfDeath()));
            $dataMasterDeath->setGraveyard($this->mergeStrings($dataMasterDeath->getGraveyard(),$toBeDeletedDeath->getGraveyard()));
            $dataMasterDeath->setFuneralLocation($this->mergeLocationObject($dataMasterDeath->getFuneralLocation(),$toBeDeletedDeath->getFuneralLocation()));
            $dataMasterDeath->setFuneralDate($this->mergeDateReference($dataMasterDeath->getFuneralDate(),$toBeDeletedDeath->getFuneralDate()));
            $dataMasterDeath->setComment($this->mergeComment($dataMasterDeath->getComment(), $toBeDeletedDeath->getComment()));

            $dataMaster->setDeath($dataMasterDeath);
        } else if ($toBeDeletedDeath != null){
            $dataMaster->setDeath($toBeDeletedDeath);
        }
    }
    
    private function mergeReligion(\UR\DB\NewBundle\Entity\BasePerson $dataMaster,\UR\DB\NewBundle\Entity\BasePerson $toBeDeleted){
        $this->LOGGER->info("Fusing Religion of '".$toBeDeleted . "' into ".$dataMaster);
        $dataMasterReligionCollection = $dataMaster->getReligions();
        $toBeDeletedReligionCollection = $toBeDeleted->getReligions();
        
        $this->LOGGER->debug("Size of dataMaster ReligionsCollection ".count($dataMasterReligionCollection));
        $this->LOGGER->debug("Size of toBeDeleted ReligionsCollection ".count($toBeDeletedReligionCollection));
        
        $dataMasterReligionArray = $dataMasterReligionCollection->getValues();
        $toBeDeletedReligionArray = $toBeDeletedReligionCollection->getValues();
        
        $this->LOGGER->debug("Size of dataMaster Religions ".count($dataMasterReligionArray));
        $this->LOGGER->debug("Size of toBeDeleted Religions ".count($toBeDeletedReligionArray));
        
        //@TODO: Merge based on order or on best matching?
        //Generell Algorithm:
        //1. Find matching entries and merge them?
        //2. Find all entries which were not merged
        //3. Build list of merged entries and unmerged entries?
        
        $listOfMatchingEntriesOfDatamaster = [];
        $listOfMatchingEntriesOfToBeDeleted = [];
        
        for($i = 0; $i < count($dataMasterReligionArray); $i++){
            $religionOne = $dataMasterReligionArray[$i];
            
            
            for($j = 0; $j < count($toBeDeletedReligionArray); $j++){
                $religionTwo = $toBeDeletedReligionArray[$j];
                
                //check if this element already found a match
                if(!in_array($religionTwo, $listOfMatchingEntriesOfToBeDeleted)){
                    
                    //check if the two elements are similar
                    if($this->compareService->matchingReligion($religionOne, $religionTwo)){
                        $listOfMatchingEntriesOfDatamaster[] = $religionOne;
                        $listOfMatchingEntriesOfToBeDeleted[] = $religionTwo;
                        continue;
                    }
                }
            }
        }
        
        $this->LOGGER->debug("Size of matching DataMaster Religions ".count($listOfMatchingEntriesOfDatamaster));
        $this->LOGGER->debug("Size of matching toBeDeleted Religions ".count($listOfMatchingEntriesOfToBeDeleted));
        
        //fuse all matching religions and add the fused to the datamaster
        for($i = 0; $i < count($listOfMatchingEntriesOfDatamaster); $i++){
            $fusedReligion = $this->mergeReligionObjects($listOfMatchingEntriesOfDatamaster[$i], $listOfMatchingEntriesOfToBeDeleted[$i]);
            
            //add new fused religion
            $dataMaster->addReligion($fusedReligion);
            
            //remove old religion from datamaster (and database?)
            $dataMaster->removeReligion($listOfMatchingEntriesOfDatamaster[$i]);
        }
        
        //find missing religions
        
        //do nothing with datamasterreligions? they are already
        $unmatchedDataMasterReligions = array_diff($dataMasterReligionArray, $listOfMatchingEntriesOfDatamaster);
        
        //move unmatchd religions from toBeDeleted to Datamaster
        $unmatchedToBeDeletedReligions = array_diff($toBeDeletedReligionArray, $listOfMatchingEntriesOfToBeDeleted);
        
        $this->LOGGER->debug("Size of unmatching DataMaster Religions ".count($unmatchedDataMasterReligions));
        $this->LOGGER->debug("Size of unmatching toBeDeleted Religions ".count($unmatchedToBeDeletedReligions));
        
        for($i = 0; $i < count($unmatchedToBeDeletedReligions); $i++){
            //add to datamaster
            $dataMaster->addReligion($unmatchedToBeDeletedReligions[$i]);
            
            //remove religion from toBeDeleted
            $toBeDeleted->removeReligion($unmatchedToBeDeletedReligions[$i]);
        }
        
        //fix orders?
    }
    
    private function mergeReligionObjects(\UR\DB\NewBundle\Entity\Religion $dataMasterReligion, \UR\DB\NewBundle\Entity\Religion $toBeDeletedReligion){
        
        
        return $dataMasterReligion;
    }
    
    private function mergeNation(\UR\DB\NewBundle\Entity\BasePerson $dataMaster,\UR\DB\NewBundle\Entity\BasePerson $toBeDeleted){
        
        $dataMasterNation = $this->getNation($dataMaster);
        $toBeDeletedNation = $this->getNation($toBeDeleted);
        
        $this->setNation($dataMaster, $this->mergeNationObject($dataMasterNation, $toBeDeletedNation));
    }
    
    private function getNation(\UR\DB\NewBundle\Entity\BasePerson $person){
        if(get_class($person) == self::RELATIVE_CLASS){
            return $person->getNation();
        }
        
        return $person->getOriginalNation();
    }
    
    private function setNation(\UR\DB\NewBundle\Entity\BasePerson $person, \UR\DB\NewBundle\Entity\Nation $nation){
        if(get_class($person) == self::RELATIVE_CLASS){
            $person->setNation($nation);
        }
        
        $person->setOriginalNation($nation);
    }
    
    
    
    private function mergeWorks(\UR\DB\NewBundle\Entity\BasePerson $dataMaster,\UR\DB\NewBundle\Entity\BasePerson $toBeDeleted){
        $this->LOGGER->info("Fusing Works of '".$toBeDeleted . "' into ".$dataMaster);
        $dataMasterWorksCollection = $dataMaster->getWorks();
        $toBeDeletedWorksCollection = $toBeDeleted->getWorks();
        
        $this->LOGGER->debug("Size of dataMaster WorksCollection ".count($dataMasterWorksCollection));
        $this->LOGGER->debug("Size of toBeDeleted WorksCollection ".count($toBeDeletedWorksCollection));
        
        $dataMasterWorksArray = $dataMasterWorksCollection->getValues();
        $toBeDeletedWorksArray = $toBeDeletedWorksCollection->getValues();
        
        $this->LOGGER->debug("Size of dataMaster Religions ".count($dataMasterWorksArray));
        $this->LOGGER->debug("Size of toBeDeleted Religions ".count($toBeDeletedWorksArray));
        
        //TODO: Finish implementaiton
    }
    
    private function mergeStatus(\UR\DB\NewBundle\Entity\BasePerson $dataMaster,\UR\DB\NewBundle\Entity\BasePerson $toBeDeleted){
        $dataMasterReference = $dataMaster->getStatusId();
        $toBeDeletedReference = $toBeDeleted->getStatusId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceMerge($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceMerge($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setStatusId($combinedReference);
    }
       
    private function mergeSource(\UR\DB\NewBundle\Entity\BasePerson $dataMaster,\UR\DB\NewBundle\Entity\BasePerson $toBeDeleted){
        $dataMasterReference = $dataMaster->getSourceId();
        $toBeDeletedReference = $toBeDeleted->getSourceId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceMerge($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceMerge($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setSourceId($combinedReference);
    }
    
    private function mergeRoadOfLive(\UR\DB\NewBundle\Entity\BasePerson$dataMaster,\UR\DB\NewBundle\Entity\BasePerson $toBeDeleted){
        $dataMasterReference = $dataMaster->getRoadOfLiveId();
        $toBeDeletedReference = $toBeDeleted->getRoadOfLiveId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceMerge($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceMerge($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setRoadOfLiveId($combinedReference);
    }
    
    private function mergeRank(\UR\DB\NewBundle\Entity\BasePerson $dataMaster,\UR\DB\NewBundle\Entity\BasePerson $toBeDeleted){
        $dataMasterReference = $dataMaster->getRankId();
        $toBeDeletedReference = $toBeDeleted->getRankId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceMerge($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceMerge($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setRankId($combinedReference);
    }
    
    private function mergeProperty(\UR\DB\NewBundle\Entity\BasePerson $dataMaster,\UR\DB\NewBundle\Entity\BasePerson $toBeDeleted){
        $dataMasterReference = $dataMaster->getPropertyId();
        $toBeDeletedReference = $toBeDeleted->getPropertyId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceMerge($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceMerge($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setPropertyId($combinedReference);
    }
    
    private function mergeHonour(\UR\DB\NewBundle\Entity\BasePerson $dataMaster,\UR\DB\NewBundle\Entity\BasePerson $toBeDeleted){
        $dataMasterReference = $dataMaster->getHonourId();
        $toBeDeletedReference = $toBeDeleted->getHonourId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceMerge($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceMerge($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setHonourId($combinedReference);
    }
    
    private function mergeEducation(\UR\DB\NewBundle\Entity\BasePerson $dataMaster,\UR\DB\NewBundle\Entity\BasePerson $toBeDeleted){
        $dataMasterReference = $dataMaster->getEducationId();
        $toBeDeletedReference = $toBeDeleted->getEducationId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceMerge($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceMerge($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setEducationId($combinedReference);
    }
    
    private function mergeJobClass(\UR\DB\NewBundle\Entity\BasePerson $dataMaster,\UR\DB\NewBundle\Entity\BasePerson $toBeDeleted){
        $dataMasterReference = $dataMaster->getJobClassId();
        $toBeDeletedReference = $toBeDeleted->getJobClassId();
        
        $combinedReference = null;
        
        if($this->checkForEasyReferenceMerge($dataMasterReference, $toBeDeletedReference)){
            $combinedReference = $this->doEasyReferenceMerge($dataMasterReference, $toBeDeletedReference);
        }else{
            
        }
        
        $dataMaster->setJobClassId($combinedReference);
    }
    
    private function mergeResidence(\UR\DB\NewBundle\Entity\BasePerson $dataMaster,\UR\DB\NewBundle\Entity\BasePerson $toBeDeleted){
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
    
    private function mergeJobObject(\UR\DB\NewBundle\Entity\Job $dataMasterJob,\UR\DB\NewBundle\Entity\Job $toBeDeletedJob){
        $this->LOGGER->debug("Fusing Jobs... '".$dataMasterJob."' with '".$toBeDeletedJob."'");
        
        $result = $dataMasterJob;
        if(is_null($toBeDeletedJob) || $toBeDeletedJob == ""){
            //do nothing, since the default is the dataMasterstring
        }else if(is_null($dataMasterJob) || $dataMasterJob == ""){
            $result = $toBeDeletedJob;
        } else if(strcasecmp($dataMasterJob->getLabel(), $toBeDeletedJob->getLabel()) != 0
                || strcasecmp($dataMasterJob->getComment(), $toBeDeletedJob->getComment()) != 0){
            $resultLabel = $dataMasterJob->getLabel() . " ODER ".$toBeDeletedJob->getLabel();
            $resultComment = $dataMasterJob->getComment() . " ODER ".$toBeDeletedJob->getComment();
            $newJob = new \UR\DB\NewBundle\Entity\Job();
            $newJob->setLabel($resultLabel);
            $newJob->setComment($resultComment);
            $result = $newJob;
        }
        
        $this->LOGGER->debug("Merged to '".$result."'");
        
        return $result;
    }
    
    private function mergeJobClassObject(\UR\DB\NewBundle\Entity\JobClass $dataMasterJobClass,\UR\DB\NewBundle\Entity\JobClass  $toBeDeletedJobClass){
        $this->LOGGER->debug("Fusing JobClass... '".$dataMasterJobClass."' with '".$toBeDeletedJobClass."'");
        
        $result = $dataMasterJobClass;
        if(is_null($toBeDeletedJobClass) || $toBeDeletedJobClass == ""){
            //do nothing, since the default is the dataMasterstring
        }else if(is_null($dataMasterJobClass) || $dataMasterJobClass == ""){
            $result = $toBeDeletedJobClass;
        } else if(strcasecmp($dataMasterJobClass->getLabel(), $toBeDeletedJobClass->getLabel()) != 0){
            $resultLabel = $dataMasterJobClass->getLabel() . " ODER ".$toBeDeletedJobClass->getLabel();
            $newJobClass = new \UR\DB\NewBundle\Entity\JobClass();
            $newJobClass->setLabel($resultLabel);
            $result = $newJobClass;
        }
        
        $this->LOGGER->debug("Merged to '".$result."'");
        
        return $result;
    }
    
    private function mergeNationObject(\UR\DB\NewBundle\Entity\Nation $dataMasterNation,\UR\DB\NewBundle\Entity\Nation $toBeDeletedNation){
        $this->LOGGER->debug("Fusing Nation... '".$dataMasterNation."' with '".$toBeDeletedNation."'");
        
        $result = $dataMasterNation;
        if(is_null($toBeDeletedNation) || $toBeDeletedNation == ""){
            //do nothing, since the default is the dataMasterstring
        }else if(is_null($dataMasterNation) || $dataMasterNation == ""){
            $result = $toBeDeletedNation;
        } else if(strcasecmp($dataMasterNation->getLabel(), $toBeDeletedNation->getLabel()) != 0
                || strcasecmp($dataMasterNation->getComment(), $toBeDeletedNation->getComment()) != 0){
            $resultLabel = $dataMasterNation->getLabel() . " ODER ".$toBeDeletedNation->getLabel();
            $resultComment = $dataMasterNation->getComment() . " ODER ".$toBeDeletedNation->getComment();
            $newNation = new \UR\DB\NewBundle\Entity\Nation();
            $newNation->setLabel($resultLabel);
            $newNation->setComment($resultComment);
            $result = $newNation;
        }
        
        $this->LOGGER->debug("Merged to '".$result."'");
        
        return $result;
    }
    
    private function mergeCountryObject(\UR\DB\NewBundle\Entity\Country $dataMasterCountry,\UR\DB\NewBundle\Entity\Country $toBeDeletedCountry){
        $this->LOGGER->debug("Fusing Country... '".$dataMasterCountry."' with '".$toBeDeletedCountry."'");
        
        $result = $dataMasterCountry;
        if(is_null($toBeDeletedCountry) || $toBeDeletedCountry == ""){
            //do nothing, since the default is the dataMasterstring
        }else if(is_null($dataMasterCountry) || $dataMasterCountry == ""){
            $result = $toBeDeletedCountry;
        } else if(strcasecmp($dataMasterCountry->getLabel(), $toBeDeletedCountry->getLabel()) != 0
                || strcasecmp($dataMasterCountry->getComment(), $toBeDeletedCountry->getComment()) != 0){
            $resultLabel = $dataMasterCountry->getLabel() . " ODER ".$toBeDeletedCountry->getLabel();
            $resultComment = $dataMasterCountry->getComment() . " ODER ".$toBeDeletedCountry->getComment();
            $newCountry = new \UR\DB\NewBundle\Entity\Country();
            $newCountry->setLabel($resultLabel);
            $newCountry->setComment($resultComment);
            $result = $newCountry;
        }
        
        $this->LOGGER->debug("Merged to '".$result."'");
        
        return $result;
    }
    
    private function mergeTerritoryObject(\UR\DB\NewBundle\Entity\Territory $dataMasterTerritory,\UR\DB\NewBundle\Entity\Territory $toBeDeletedTerritory){
        $this->LOGGER->debug("Fusing Territory... '".$dataMasterTerritory."' with '".$toBeDeletedTerritory."'");
        
        $result = $dataMasterTerritory;
        if(is_null($toBeDeletedTerritory) || $toBeDeletedTerritory == ""){
            //do nothing, since the default is the dataMasterstring
        }else if(is_null($dataMasterTerritory) || $dataMasterTerritory == ""){
            $result = $toBeDeletedTerritory;
        } else if(strcasecmp($dataMasterTerritory->getLabel(), $toBeDeletedTerritory->getLabel()) != 0
                || strcasecmp($dataMasterTerritory->getComment(), $toBeDeletedTerritory->getComment()) != 0){
            $resultLabel = $dataMasterTerritory->getLabel() . " ODER ".$toBeDeletedTerritory->getLabel();
            $resultComment = $dataMasterTerritory->getComment() . " ODER ".$toBeDeletedTerritory->getComment();
            $newTerritory = new \UR\DB\NewBundle\Entity\Territory();
            $newTerritory->setLabel($resultLabel);
            $newTerritory->setComment($resultComment);
            $result = $newTerritory;
        }
        
        $this->LOGGER->debug("Merged to '".$result."'");
        
        return $result;
    }
    
    private function mergeLocationObject(\UR\DB\NewBundle\Entity\Location $dataMasterLocation,\UR\DB\NewBundle\Entity\Location $toBeDeletedLocation){
        $this->LOGGER->debug("Fusing Location... '".$dataMasterLocation."' with '".$toBeDeletedLocation."'");
        
        $result = $dataMasterLocation;
        if(is_null($toBeDeletedLocation) || $toBeDeletedLocation == ""){
            //do nothing, since the default is the dataMasterstring
        }else if(is_null($dataMasterLocation) || $dataMasterLocation == ""){
            $result = $toBeDeletedLocation;
        } else if(strcasecmp($dataMasterLocation->getLabel(), $toBeDeletedLocation->getLabel()) != 0
                || strcasecmp($dataMasterLocation->getComment(), $toBeDeletedLocation->getComment()) != 0){
            $resultLabel = $dataMasterLocation->getLabel() . " ODER ".$toBeDeletedLocation->getLabel();
            $resultComment = $dataMasterLocation->getComment() . " ODER ".$toBeDeletedLocation->getComment();
            $newLocation = new \UR\DB\NewBundle\Entity\Location();
            $newLocation->setLabel($resultLabel);
            $newLocation->setComment($resultComment);
            $result = $newLocation;
        }
        
        $this->LOGGER->debug("Merged to '".$result."'");
        
        return $result;
    }
    
    //@TODO: Implement Data Reference merge!
    private function mergeDateReference(array $dataMasterDate,array $toBeDeletedDate){
        //merge date!
        //and mind 0.0.1800 and similar dates!
        //if one date is "genauer" als the other, use it!
        //btw. ignore comments just merge them if necessary!
        
        return $dataMasterDate;
    }
    
    private function mergeDateObject(\UR\DB\NewBundle\Entity\Date $dataMasterDate,\UR\DB\NewBundle\Entity\Date $toBeDeletedDate){
        //merge date!
        //and mind 0.0.1800 and similar dates!
        //if one date is "genauer" als the other, use it!
        //btw. ignore comments just merge them if necessary!
        
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
        }
        
        return $toBeDeletedReference;
    }
}
