<?php

namespace UR\DB\NewBundle\Utils;

/**
 * Description of PersonMerger
 *
 * 
 */
class PersonMerger {
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
    //Its not only necessary to finish the method but to call it from MigrateController
    public function mergePersons(\UR\DB\NewBundle\Entity\BasePerson $personOne,\UR\DB\NewBundle\Entity\BasePerson $personTwo){
        $this->LOGGER->info("Request for fusing two persons.");
        $this->LOGGER->info("Person 1: ".$personOne);
        $this->LOGGER->info("Person 2: ".$personTwo);
        
        if($personOne->getGender() != $personTwo->getGender()
                && $personOne->getGender() != Gender::UNKNOWN && $personTwo != Gender::UNKNOWN){
            $this->LOGGER->warn("Trying to merge a man with a woman, is this really right?");
        }
        
        $dataMaster = $this->determineDatamaster($personOne, $personTwo);
        $toBeDeleted = $this->determineToBeRemoved($personOne, $personTwo);
        
        $this->LOGGER->info("The data will be combined in: ".$dataMaster);
        $this->LOGGER->info("The object '".$dataMaster."' will be removed.");
       
        if(get_class($personOne) == PersonClasses::PERSON_CLASS
                && get_class($personTwo) == PersonClasses::PERSON_CLASS){
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
    public function __clone() {
        ;
    }
    private function determineDatamaster(\UR\DB\NewBundle\Entity\BasePerson $personOne,\UR\DB\NewBundle\Entity\BasePerson $personTwo){
        if(get_class($personTwo) == PersonClasses::PERSON_CLASS
                && get_class($personOne) != PersonClasses::PERSON_CLASS){
            return $personTwo;
        }
        
        return $personOne;
    }
    
   private function determineToBeRemoved(\UR\DB\NewBundle\Entity\BasePerson $personOne,\UR\DB\NewBundle\Entity\BasePerson $personTwo){
        if(get_class($personTwo) == PersonClasses::PERSON_CLASS
                && get_class($personOne) != PersonClasses::PERSON_CLASS){
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
         
        $dataMaster->setFirstName($this->mergeStrings($dataMaster->getFirstName(), $toBeDeleted->getFirstName()));
        $dataMaster->setPatronym($this->mergeStrings($dataMaster->getPatronym(), $toBeDeleted->getPatronym()));
        $dataMaster->setLastName($this->mergeStrings($dataMaster->getLastName(), $toBeDeleted->getLastName()));
        $dataMaster->setForeName($this->mergeStrings($dataMaster->getForeName(), $toBeDeleted->getForeName()));
        $dataMaster->setBirthName($this->mergeStrings($dataMaster->getBirthName(), $toBeDeleted->getBirthName()));
        
        $this->mergeGender($dataMaster, $toBeDeleted);
        $dataMaster->setComment($this->mergeComment($dataMaster->getComment(), $toBeDeleted->getComment()));
        $dataMaster->setBornInMarriage($this->mergeStrings($dataMaster->getBornInMarriage(), $toBeDeleted->getBornInMarriage()));

        //now the references :(
        $this->mergeBirth($dataMaster, $toBeDeleted);
        $this->mergeDeath($dataMaster, $toBeDeleted);
        $this->mergeBaptism($dataMaster, $toBeDeleted);
        $this->mergeReligion($dataMaster, $toBeDeleted);
        $this->mergeNation($dataMaster, $toBeDeleted);
        $this->mergeWorks($dataMaster, $toBeDeleted);
        $this->mergeStatus($dataMaster, $toBeDeleted);
        $this->mergeRoadOfLife($dataMaster, $toBeDeleted);
        $this->mergeRank($dataMaster, $toBeDeleted);
        $this->mergeProperty($dataMaster, $toBeDeleted);
        $this->mergeHonour($dataMaster, $toBeDeleted);
        $this->mergeEducation($dataMaster, $toBeDeleted);
        $this->mergeJobClass($dataMaster, $toBeDeleted);
        $this->mergeResidence($dataMaster, $toBeDeleted);
         
        //@TODO: Reenable
        /*
        $this->mergeSource($dataMaster, $toBeDeleted);
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
        
        $dataMasterReligionArray = $this->toArray($dataMasterReligionCollection);
        $toBeDeletedReligionArray = $this->toArray($toBeDeletedReligionCollection);
        
        $this->fuseArrays($dataMaster, $toBeDeleted, $dataMasterReligionArray, $toBeDeletedReligionArray, PersonInformation::RELIGION);
    }
    
    private function fuseArrays(\UR\DB\NewBundle\Entity\BasePerson $dataMaster,\UR\DB\NewBundle\Entity\BasePerson $toBeDeleted, $dataMasterArray, 
            $toBeDeletedArray, $type){
        $this->LOGGER->info("Fusing Arrays of type:'".$type."' of '".$toBeDeleted . 
                "' with size '".count($toBeDeletedArray)."' into "
                . "'".$dataMaster."' with size '".count($dataMasterArray)."'");
        
        //@TODO: Merge based on order or on best matching?
        //Generell Algorithm:
        //1. Find matching entries and merge them?
        //2. Find all entries which were not merged
        //3. Build list of merged entries and unmerged entries?
        
        $listOfMatchingEntriesOfDatamaster = [];
        $listOfMatchingEntriesOfToBeDeleted = [];
        
        for($i = 0; $i < count($dataMasterArray); $i++){
            $dataMasterEntry = $dataMasterArray[$i];
            
            
            for($j = 0; $j < count($toBeDeletedArray); $j++){
                $toBeDeletedEntry = $toBeDeletedArray[$j];
                
                //check if this element already found a match
                if(!in_array($toBeDeletedEntry, $listOfMatchingEntriesOfToBeDeleted)){
                    
                    //check if the two elements are similar
                    if($this->compareEntries($dataMasterEntry, $toBeDeletedEntry, $type)){
                        $listOfMatchingEntriesOfDatamaster[] = $dataMasterEntry;
                        $listOfMatchingEntriesOfToBeDeleted[] = $toBeDeletedEntry;
                        continue;
                    }
                }
            }
        }
        
        $this->LOGGER->debug("Size of matching DataMaster matching Entries ".count($listOfMatchingEntriesOfDatamaster));
        $this->LOGGER->debug("Size of matching toBeDeleted matching Entries  ".count($listOfMatchingEntriesOfToBeDeleted));
        
        //fuse all matching entries and add the fused to the datamaster
        for($i = 0; $i < count($listOfMatchingEntriesOfDatamaster); $i++){
            //do nothing with the fused religino since its already a datamaster religion
            $fusedEntry = $this->mergeEntries($listOfMatchingEntriesOfDatamaster[$i], $listOfMatchingEntriesOfToBeDeleted[$i], $type);
            
            /*
            //add new fused religion
            $dataMaster->addReligion($fusedReligion);
            
            //remove old religion from datamaster (and database?)
            $dataMaster->removeReligion($listOfMatchingEntriesOfDatamaster[$i]);
            */
        }
        
        //find missing entries
        
        //do nothing with datamasterentries they are already
        $unmatchedDataMasterEntries = array_diff($dataMasterArray, $listOfMatchingEntriesOfDatamaster);
        
        //move unmatched entries from toBeDeleted to Datamaster
        $unmatchedToBeDeletedEntries = array_diff($toBeDeletedArray, $listOfMatchingEntriesOfToBeDeleted);
        
        $this->LOGGER->debug("Size of unmatching DataMaster entries ".count($unmatchedDataMasterEntries));
        $this->LOGGER->debug("Size of unmatching toBeDeleted entries ".count($unmatchedToBeDeletedEntries));
        
        for($i = 0; $i < count($unmatchedToBeDeletedEntries); $i++){
            //add to datamaster
            //$dataMaster->addReligion($unmatchedToBeDeletedReligions[$i]);
            
            $unmatchedToBeDeletedEntries->setPerson($dataMaster);
            
            //remove religion from toBeDeleted
            //@TODO: Check if necessary or if the doctrine handles it correctly already
            //$toBeDeleted->removeReligion($unmatchedToBeDeletedEntries[$i]);
        }
        
        //fix orders?
    }
    
    private function compareEntries($dataMasterEntry, $toBeDeletedEntry, $type){
        switch($type){
            /*
             * Not needed
            case PersonInformation::BIRTH:
                return $this->compareService->matchingBirth($dataMasterEntry, $toBeDeletedEntry);
            case PersonInformation::BAPTISM:
                return $this->compareService->matchingBaptism($dataMasterEntry, $toBeDeletedEntry);
            case PersonInformation::DEATH:
                return $this->compareService->matchingDeath($dataMasterEntry, $toBeDeletedEntry);
             */
            case PersonInformation::EDUCATION:
                return $this->compareService->matchingEducation($dataMasterEntry, $toBeDeletedEntry);
            case PersonInformation::HONOUR:
                return $this->compareService->matchingHonour($dataMasterEntry, $toBeDeletedEntry);
            case PersonInformation::PROPERTY:
                return $this->compareService->matchingProperty($dataMasterEntry, $toBeDeletedEntry);
            case PersonInformation::RANK:
                return $this->compareService->matchingRank($dataMasterEntry, $toBeDeletedEntry);
            case PersonInformation::RELIGION:
                return $this->compareService->matchingReligion($dataMasterEntry, $toBeDeletedEntry);
            case PersonInformation::RESIDENCE:
                return $this->compareService->matchingResidence($dataMasterEntry, $toBeDeletedEntry);
            case PersonInformation::ROAD_OF_LIFE:
                return $this->compareService->matchingRoadOfLife($dataMasterEntry, $toBeDeletedEntry);
            case PersonInformation::STATUS:
                return $this->compareService->matchingStatus($dataMasterEntry, $toBeDeletedEntry);
            case PersonInformation::WORK:
                return $this->compareService->matchingWork($dataMasterEntry, $toBeDeletedEntry);
            case PersonInformation::DATE:
                return $this->compareService->matchingDates($dataMasterEntry, $toBeDeletedEntry);
            default:
                $this->LOGGER->error("Unknown Type: ".$type);
                return $dataMasterEntry == $toBeDeletedEntry;
        }
    }
    
    private function mergeEntries($dataMasterEntry, $toBeDeletedEntry, $type){
        switch($type){
            /*
             * Not needed
            case PersonInformation::BIRTH:
                return $this->matchingBirth($dataMasterEntry, $toBeDeletedEntry);
            case PersonInformation::BAPTISM:
                return $this->compareService->matchingBaptism($dataMasterEntry, $toBeDeletedEntry);
            case PersonInformation::DEATH:
                return $this->compareService->matchingDeath($dataMasterEntry, $toBeDeletedEntry);
             */
            case PersonInformation::EDUCATION:
                return $this->mergeEducationObjects($dataMasterEntry, $toBeDeletedEntry);
            case PersonInformation::HONOUR:
                return $this->mergeHonourObjects($dataMasterEntry, $toBeDeletedEntry);
            case PersonInformation::PROPERTY:
                return $this->mergePropertyObjects($dataMasterEntry, $toBeDeletedEntry);
            case PersonInformation::RANK:
                return $this->mergeRankObjects($dataMasterEntry, $toBeDeletedEntry);
            case PersonInformation::RELIGION:
                return $this->mergeReligionObjects($dataMasterEntry, $toBeDeletedEntry);
            case PersonInformation::RESIDENCE:
                return $this->mergeResidenceObjects($dataMasterEntry, $toBeDeletedEntry);
            case PersonInformation::ROAD_OF_LIFE:
                return $this->mergeRoadOfLifeObjects($dataMasterEntry, $toBeDeletedEntry);
            case PersonInformation::STATUS:
                return $this->mergeStatusObjects($dataMasterEntry, $toBeDeletedEntry);
            case PersonInformation::WORK:
                return $this->mergeWorkObjects($dataMasterEntry, $toBeDeletedEntry);
            case PersonInformation::DATE:
                return $this->mergeDateObjects($dataMasterEntry, $toBeDeletedEntry);
            default:
                $this->LOGGER->error("Unknown Type: ".$type);
                return $dataMasterEntry == $toBeDeletedEntry;
        }
    }
    
    private function mergeReligionObjects(\UR\DB\NewBundle\Entity\Religion $dataMasterReligion, \UR\DB\NewBundle\Entity\Religion $toBeDeletedReligion){
        $dataMasterReligion->setName($this->mergeStrings($dataMasterReligion->getName(), $toBeDeletedReligion->getName()));
        $dataMasterReligion->setReligionOrder($this->mergeStrings($dataMasterReligion->getReligionOrder(), $toBeDeletedReligion->getReligionOrder()));
        $dataMasterReligion->setChangeOfReligion($this->mergeStrings($dataMasterReligion->getChangeOfReligion(), $toBeDeletedReligion->getChangeOfReligion()));
        $dataMasterReligion->setFromDate($this->mergeDateReference($dataMasterReligion->getFromDate(), $toBeDeletedReligion->getFromDate()));
        $dataMasterReligion->setToDate($this->mergeDateReference($dataMasterReligion->getToDate(), $toBeDeletedReligion->getToDate()));
        $dataMasterReligion->setProvenDate($this->mergeDateReference($dataMasterReligion->getProvenDate(), $toBeDeletedReligion->getProvenDate()));
        $dataMasterReligion->setComment($this->mergeStrings($dataMasterReligion->getComment(), $toBeDeletedReligion->getComment()));
        
        return $dataMasterReligion;
    }
    
    private function mergeNation(\UR\DB\NewBundle\Entity\BasePerson $dataMaster,\UR\DB\NewBundle\Entity\BasePerson $toBeDeleted){
        
        $dataMasterNation = $this->getNation($dataMaster);
        $toBeDeletedNation = $this->getNation($toBeDeleted);
        
        if($dataMasterNation != null || $toBeDeletedNation != null){
            $this->setNation($dataMaster, $this->mergeNationObject($dataMasterNation, $toBeDeletedNation));
        }
    }
    
    private function getNation(\UR\DB\NewBundle\Entity\BasePerson $person){
        if(get_class($person) == PersonClasses::RELATIVE_CLASS){
            return $person->getNation();
        }
        
        return $person->getOriginalNation();
    }
    
    private function setNation(\UR\DB\NewBundle\Entity\BasePerson $person, \UR\DB\NewBundle\Entity\Nation $nation){
        if(get_class($person) == PersonClasses::RELATIVE_CLASS){
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
        
        $dataMasterWorksArray = $this->toArray($dataMasterWorksCollection);
        $toBeDeletedWorksArray = $this->toArray($toBeDeletedWorksCollection);
        
        $this->fuseArrays($dataMaster, $toBeDeleted, $dataMasterWorksArray, $toBeDeletedWorksArray, PersonInformation::WORK);
    }
    
    private function mergeWorkObjects(\UR\DB\NewBundle\Entity\Work $dataMasterWork, \UR\DB\NewBundle\Entity\Work $toBeDeletedWork){

        return $dataMasterWork;
    }
    
    private function mergeStatus(\UR\DB\NewBundle\Entity\BasePerson $dataMaster,\UR\DB\NewBundle\Entity\BasePerson $toBeDeleted){ 
        $this->LOGGER->info("Fusing Stati of '".$toBeDeleted . "' into ".$dataMaster);
        $dataMasterStatiCollection = $dataMaster->getStati();
        $toBeDeletedStatiCollection = $toBeDeleted->getStati();
        
        $this->LOGGER->debug("Size of dataMaster StatiCollection ".count($dataMasterStatiCollection));
        $this->LOGGER->debug("Size of toBeDeleted StatiCollection ".count($toBeDeletedStatiCollection));
        
        $dataMasterStatiArray = $this->toArray($dataMasterStatiCollection);
        $toBeDeletedStatiArray = $this->toArray($toBeDeletedStatiCollection);
        
        $this->fuseArrays($dataMaster, $toBeDeleted, $dataMasterStatiArray, $toBeDeletedStatiArray, PersonInformation::STATUS);
    }
    
    private function mergeStatusObjects(\UR\DB\NewBundle\Entity\Status $dataMasterStatus, \UR\DB\NewBundle\Entity\Status $toBeDeletedStatus){

        return $dataMasterStatus;
    }
    
    //@TODO: Merge source?
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
    
    private function mergeRoadOfLife(\UR\DB\NewBundle\Entity\BasePerson$dataMaster,\UR\DB\NewBundle\Entity\BasePerson $toBeDeleted){
        $this->LOGGER->info("Fusing RoadOfLife of '".$toBeDeleted . "' into ".$dataMaster);
        $dataMasterRoadOfLifeCollection = $dataMaster->getRoadOfLife();
        $toBeDeletedRoadOfLifeCollection = $toBeDeleted->getRoadOfLife();
        
        $this->LOGGER->debug("Size of dataMaster RoadOfLifeCollection ".count($dataMasterRoadOfLifeCollection));
        $this->LOGGER->debug("Size of toBeDeleted RoadOfLifeCollection ".count($toBeDeletedRoadOfLifeCollection));
        
        $dataMasterRoadOfLifeArray = $this->toArray($dataMasterRoadOfLifeCollection);
        $toBeDeletedRoadOfLifeArray = $this->toArray($toBeDeletedRoadOfLifeCollection);
        
        $this->fuseArrays($dataMaster, $toBeDeleted, $dataMasterRoadOfLifeArray, $toBeDeletedRoadOfLifeArray, PersonInformation::ROAD_OF_LIFE);
    }
    
    private function mergeRoadOfLifeObjects(\UR\DB\NewBundle\Entity\RoadOfLife $dataMasterRoadOfLife, \UR\DB\NewBundle\Entity\RoadOfLife $toBeDeletedRoadOfLife){

        return $dataMasterRoadOfLife;
    }
    
    private function mergeRank(\UR\DB\NewBundle\Entity\BasePerson $dataMaster,\UR\DB\NewBundle\Entity\BasePerson $toBeDeleted){
        $this->LOGGER->info("Fusing Ranks of '".$toBeDeleted . "' into ".$dataMaster);
        $dataMasterRankCollection = $dataMaster->getRanks();
        $toBeDeletedRankCollection = $toBeDeleted->getRanks();
        
        $this->LOGGER->debug("Size of dataMaster RankCollection ".count($dataMasterRankCollection));
        $this->LOGGER->debug("Size of toBeDeleted RankCollection ".count($toBeDeletedRankCollection));
        
        $dataMasterRankArray = $this->toArray($dataMasterRankCollection);
        $toBeDeletedRankArray = $this->toArray($toBeDeletedRankCollection);
        
        $this->fuseArrays($dataMaster, $toBeDeleted, $dataMasterRankArray, $toBeDeletedRankArray, PersonInformation::RANK);
    }
    
    private function mergeRankObjects(\UR\DB\NewBundle\Entity\Rank $dataMasterRank, \UR\DB\NewBundle\Entity\Rank $toBeDeletedRank){

        return $dataMasterRank;
    }
    
    private function mergeProperty(\UR\DB\NewBundle\Entity\BasePerson $dataMaster,\UR\DB\NewBundle\Entity\BasePerson $toBeDeleted){
        $this->LOGGER->info("Fusing Properties of '".$toBeDeleted . "' into ".$dataMaster);
        $dataMasterPropertyCollection = $dataMaster->getProperties();
        $toBeDeletedPropertyCollection = $toBeDeleted->getProperties();
        
        $this->LOGGER->debug("Size of dataMaster PropertyCollection ".count($dataMasterPropertyCollection));
        $this->LOGGER->debug("Size of toBeDeleted PropertyCollection ".count($toBeDeletedPropertyCollection));
        
        $dataMasterPropertyArray = $this->toArray($dataMasterPropertyCollection);
        $toBeDeletedPropertyArray = $this->toArray($toBeDeletedPropertyCollection);
        
        $this->fuseArrays($dataMaster, $toBeDeleted, $dataMasterPropertyArray, $toBeDeletedPropertyArray, PersonInformation::PROPERTY);
    }
    
    private function mergePropertyObjects(\UR\DB\NewBundle\Entity\Property $dataMasterProperty, \UR\DB\NewBundle\Entity\Property $toBeDeletedProperty){

        return $dataMasterProperty;
    }
    
    private function mergeHonour(\UR\DB\NewBundle\Entity\BasePerson $dataMaster,\UR\DB\NewBundle\Entity\BasePerson $toBeDeleted){
        $this->LOGGER->info("Fusing Honours of '".$toBeDeleted . "' into ".$dataMaster);
        $dataMasterHonourCollection = $dataMaster->getHonours();
        $toBeDeletedHonourCollection = $toBeDeleted->getHonours();
        
        $this->LOGGER->debug("Size of dataMaster HonourCollection ".count($dataMasterHonourCollection));
        $this->LOGGER->debug("Size of toBeDeleted HonourCollection ".count($toBeDeletedHonourCollection));
        
        $dataMasterHonourArray = $this->toArray($dataMasterHonourCollection);
        $toBeDeletedHonourArray = $this->toArray($toBeDeletedHonourCollection);
        
        $this->fuseArrays($dataMaster, $toBeDeleted, $dataMasterHonourArray, $toBeDeletedHonourArray, PersonInformation::HONOUR);
    }
    
    private function mergeHonourObjects(\UR\DB\NewBundle\Entity\Honour $dataMasterHonour, \UR\DB\NewBundle\Entity\Honour $toBeDeletedHonour){

        return $dataMasterHonour;
    }
    
    private function mergeEducation(\UR\DB\NewBundle\Entity\BasePerson $dataMaster,\UR\DB\NewBundle\Entity\BasePerson $toBeDeleted){
        $this->LOGGER->info("Fusing Educations of '".$toBeDeleted . "' into ".$dataMaster);
        $dataMasterEducationCollection = $dataMaster->getEducations();
        $toBeDeletedEducationCollection = $toBeDeleted->getEducations();
        
        $this->LOGGER->debug("Size of dataMaster EducationCollection ".count($dataMasterEducationCollection));
        $this->LOGGER->debug("Size of toBeDeleted EducationCollection ".count($toBeDeletedEducationCollection));
        
        $dataMasterEducationArray = $this->toArray($dataMasterEducationCollection);
        $toBeDeletedEducationArray = $this->toArray($toBeDeletedEducationCollection);
        
        $this->fuseArrays($dataMaster, $toBeDeleted, $dataMasterEducationArray, $toBeDeletedEducationArray, PersonInformation::EDUCATION);
    }
    
    private function mergeEducationObjects(\UR\DB\NewBundle\Entity\Education $dataMasterEducation, \UR\DB\NewBundle\Entity\Education $toBeDeletedEducation){

        return $dataMasterEducation;
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
        $this->LOGGER->info("Fusing Residences of '".$toBeDeleted . "' into ".$dataMaster);
        $dataMasterResidenceCollection = $dataMaster->getResidences();
        $toBeDeletedResidenceCollection = $toBeDeleted->getResidences();
        
        $this->LOGGER->debug("Size of dataMaster ResidenceCollection ".count($dataMasterResidenceCollection));
        $this->LOGGER->debug("Size of toBeDeleted ResidenceCollection ".count($toBeDeletedResidenceCollection));
        
        $dataMasterResidenceArray = $this->toArray($dataMasterResidenceCollection);
        $toBeDeletedResidenceArray = $this->toArray($toBeDeletedResidenceCollection);
        
        $this->fuseArrays($dataMaster, $toBeDeleted, $dataMasterResidenceArray, $toBeDeletedResidenceArray, PersonInformation::RESIDENCE);
    }
    
    private function mergeResidenceObjects(\UR\DB\NewBundle\Entity\Residence $dataMasterResidence, \UR\DB\NewBundle\Entity\Residence $toBeDeletedResidence){

        return $dataMasterResidence;
    }
    
    private function mergeStrings($dataMasterString, $toBeDeletedString){
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
                && $dataMaster->getGender() == Gender::UNKNOWN){
                $dataMaster->setGender($toBeDeleted->getGender());
        }
    }
    
    private function mergeComment($dataMasterComment, $toBeDeletedComment){
        return $this->mergeStrings($dataMasterComment, $toBeDeletedComment);
    }
    
    //@TODO: Enhance merge job, jobclass, country, location, territory to reuse 
    // existing entries and not create new ones...
    
    private function mergeJobObject($dataMasterJob,$toBeDeletedJob){
        $this->LOGGER->debug("Fusing Jobs... '".$dataMasterJob."' with '".$toBeDeletedJob."'");
        
        $result = $dataMasterJob;
        if(is_null($toBeDeletedJob) || $toBeDeletedJob == ""){
            //do nothing, since the default is the dataMasterstring
        }else if(is_null($dataMasterJob) || $dataMasterJob == ""){
            $result = $toBeDeletedJob;
        } else if(strcasecmp($dataMasterJob->getLabel(), $toBeDeletedJob->getLabel()) != 0
                || strcasecmp($dataMasterJob->getComment(), $toBeDeletedJob->getComment()) != 0){
            $resultLabel = $this->mergeStrings($dataMasterJob->getLabel(),$toBeDeletedJob->getLabel());
            $resultComment = $this->mergeComment($dataMasterJob->getComment(),$toBeDeletedJob->getComment());
            $newJob = new \UR\DB\NewBundle\Entity\Job();
            $newJob->setLabel($resultLabel);
            $newJob->setComment($resultComment);
            $result = $newJob;
        }
        
        $this->LOGGER->debug("Merged to '".$result."'");
        
        return $result;
    }
    
    private function mergeJobClassObject($dataMasterJobClass, $toBeDeletedJobClass){
        $this->LOGGER->debug("Fusing JobClass... '".$dataMasterJobClass."' with '".$toBeDeletedJobClass."'");
        
        $result = $dataMasterJobClass;
        if(is_null($toBeDeletedJobClass) || $toBeDeletedJobClass == ""){
            //do nothing, since the default is the dataMasterstring
        }else if(is_null($dataMasterJobClass) || $dataMasterJobClass == ""){
            $result = $toBeDeletedJobClass;
        } else if(strcasecmp($dataMasterJobClass->getLabel(), $toBeDeletedJobClass->getLabel()) != 0){
            $resultLabel = $this->mergeStrings($dataMasterJobClass->getLabel(),$toBeDeletedJobClass->getLabel());
            $newJobClass = new \UR\DB\NewBundle\Entity\JobClass();
            $newJobClass->setLabel($resultLabel);
            $result = $newJobClass;
        }
        
        $this->LOGGER->debug("Merged to '".$result."'");
        
        return $result;
    }
    
    private function mergeNationObject($dataMasterNation,$toBeDeletedNation){
        $this->LOGGER->debug("Fusing Nation... '".$dataMasterNation."' with '".$toBeDeletedNation."'");
        
        $result = $dataMasterNation;
        if(is_null($toBeDeletedNation) || $toBeDeletedNation == ""){
            //do nothing, since the default is the dataMasterstring
        }else if(is_null($dataMasterNation) || $dataMasterNation == ""){
            $result = $toBeDeletedNation;
        } else if(strcasecmp($dataMasterNation->getName(), $toBeDeletedNation->getName()) != 0
                || strcasecmp($dataMasterNation->getComment(), $toBeDeletedNation->getComment()) != 0){
            $resultName = $dataMasterNation->getName() . " ODER ".$toBeDeletedNation->getName();
            $resultComment = $dataMasterNation->getComment() . " ODER ".$toBeDeletedNation->getComment();
            $newNation = new \UR\DB\NewBundle\Entity\Nation();
            $newNation->setLabel($resultName);
            $newNation->setComment($resultComment);
            $result = $newNation;
        }
        
        $this->LOGGER->debug("Merged to '".$result."'");
        
        return $result;
    }
    
    private function mergeCountryObject($dataMasterCountry,$toBeDeletedCountry){
        $this->LOGGER->debug("Fusing Country... '".$dataMasterCountry."' with '".$toBeDeletedCountry."'");
        
        $result = $dataMasterCountry;
        if(is_null($toBeDeletedCountry) || $toBeDeletedCountry == ""){
            //do nothing, since the default is the dataMasterstring
        }else if(is_null($dataMasterCountry) || $dataMasterCountry == ""){
            $result = $toBeDeletedCountry;
        } else if(strcasecmp($dataMasterCountry->getName(), $toBeDeletedCountry->getName()) != 0
                || strcasecmp($dataMasterCountry->getComment(), $toBeDeletedCountry->getComment()) != 0){
            $resultName = $this->mergeStrings($dataMasterCountry->getName(),$toBeDeletedCountry->getName());
            $resultComment = $this->mergeComment($dataMasterCountry->getComment(),$toBeDeletedCountry->getComment());
            $newCountry = new \UR\DB\NewBundle\Entity\Country();
            $newCountry->setName($resultName);
            $newCountry->setComment($resultComment);
            $result = $newCountry;
        }
        
        $this->LOGGER->debug("Merged to '".$result."'");
        
        return $result;
    }
    
    private function mergeTerritoryObject($dataMasterTerritory,$toBeDeletedTerritory){
        $this->LOGGER->debug("Fusing Territory... '".$dataMasterTerritory."' with '".$toBeDeletedTerritory."'");
        
        $result = $dataMasterTerritory;
        if(is_null($toBeDeletedTerritory) || $toBeDeletedTerritory == ""){
            //do nothing, since the default is the dataMasterstring
        }else if(is_null($dataMasterTerritory) || $dataMasterTerritory == ""){
            $result = $toBeDeletedTerritory;
        } else if(strcasecmp($dataMasterTerritory->getName(), $toBeDeletedTerritory->getName()) != 0
                || strcasecmp($dataMasterTerritory->getComment(), $toBeDeletedTerritory->getComment()) != 0){
            $resultName = $this->mergeStrings($dataMasterTerritory->getName(),$toBeDeletedTerritory->getName());
            $resultComment = $this->mergeComment($dataMasterTerritory->getComment(),$toBeDeletedTerritory->getComment());
            $newTerritory = new \UR\DB\NewBundle\Entity\Territory();
            $newTerritory->setName($resultName);
            $newTerritory->setComment($resultComment);
            $result = $newTerritory;
        }
        
        $this->LOGGER->debug("Merged to '".$result."'");
        
        return $result;
    }
    
    private function mergeLocationObject($dataMasterLocation,$toBeDeletedLocation){
        $this->LOGGER->debug("Fusing Location... '".$dataMasterLocation."' with '".$toBeDeletedLocation."'");
        
        $result = $dataMasterLocation;
        if(is_null($toBeDeletedLocation) || $toBeDeletedLocation == ""){
            //do nothing, since the default is the dataMasterstring
        }else if(is_null($dataMasterLocation) || $dataMasterLocation == ""){
            $result = $toBeDeletedLocation;
        } else if(strcasecmp($dataMasterLocation->getName(), $toBeDeletedLocation->getName()) != 0
                || strcasecmp($dataMasterLocation->getComment(), $toBeDeletedLocation->getComment()) != 0){
            $resultName = $this->mergeStrings($dataMasterLocation->getName(),$toBeDeletedLocation->getName());
            $resultComment = $this->mergeComment($dataMasterLocation->getComment(),$toBeDeletedLocation->getComment());
            $newLocation = new \UR\DB\NewBundle\Entity\Location();
            $newLocation->setName($resultName);
            $newLocation->setComment($resultComment);
            $result = $newLocation;
        }
        
        $this->LOGGER->debug("Merged to '".$result."'");
        
        return $result;
    }
    
    //@TODO: Implement Data Reference merge!
    private function mergeDateReference($dataMasterDateArray,$toBeDeletedDateArray){
        //merge date!
        //and mind 0.0.1800 and similar dates!
        //if one date is "genauer" als the other, use it!
        //btw. ignore comments just merge them if necessary!
        
        return $dataMasterDateArray;
    }
    
    private function mergeDateObjects($dataMasterDate,$toBeDeletedDate){
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
    
    private function toArray($collection){
        $array = [];
        
        for($i = 0; $i < count($collection); $i++){
            $array[] = $collection->get($i);
        }
        
        return $array;
    }
}
