<?php

namespace UR\DB\NewBundle\Utils;

class UtilObjHandler {
    
    private $LOGGER;
    private $container;
    
    public function __construct($container)
    {
        $this->container = $container;
        $this->LOGGER = $this->get('monolog.logger.default');
    } 
    
    private function get($identifier){
        return $this->container->get($identifier);
    }
    
    public function getCountry($em, $country) {
        if(is_null($country)){
            return $country;
        }
        
        if($country->getComment() != null){
            $existingCountry = $em->getRepository('NewBundle:Country')->findOneBy(array('country_name' => $country->getName(), 'comment' => $country->getComment()));

            if ($existingCountry != null && $country->getId() != $existingCountry->getId()) {
                return $existingCountry;
            }
        } else{

            $existingCountry = $em->getRepository('NewBundle:Country')->findOneByName($country->getName());

            if ($existingCountry != null && $country->getId() != $existingCountry->getId()) {
                return $existingCountry;
            }
        }
        
        $this->LOGGER->debug("Did not find existing country");
        
        
        $em->persist($country);
        $em->flush();
        
        return $country;
    }
    
    public function getTerritory($em, $territory) {
        if(is_null($territory)){
            return $territory;
        }
        
        if($territory->getComment() != null){
            $existingTerritory = $em->getRepository('NewBundle:Territory')->findOneBy(array('territory_name' => $territory->getName(), 'comment' => $territory->getComment()));

            if ($existingTerritory != null && $existingTerritory->getId() != $territory->getId()) {
                return $existingTerritory;
            }
        } else{

            $existingTerritory = $em->getRepository('NewBundle:Territory')->findOneByName($territory->getName());

            if ($existingTerritory != null && $existingTerritory->getId() != $territory->getId()) {
                return $existingTerritory;
            }
        }
            
        $this->LOGGER->debug("Did not find existing territory");
        
        $em->persist($territory);
        $em->flush();
        
        return $territory;
    }
    
    public function getLocation($em, $location) {
        if(is_null($location)){
            return $location;
        }
        
        if($location->getComment() != null){
            $existingLocation = $em->getRepository('NewBundle:Location')->findOneBy(array('location_name' => $location->getName(), 'comment' => $location->getComment()));

            if ($existingLocation != null  && $existingLocation->getId() != $location->getId()) {
                return $existingLocation;
            }
        } else{

            $existingLocation = $em->getRepository('NewBundle:Location')->findOneByName($location->getName());

            if ($existingLocation != null  && $existingLocation->getId() != $location->getId()) {
                return $existingLocation;
            }
        }
        
        $this->LOGGER->debug("Did not find existing location");
            
        $em->persist($location);
        $em->flush();
        
        return $location;
    }

    public function getNation($em, $nation) {
        if(is_null($nation)){
            return $nation;
        }
        
        if($nation->getComment() != null){
            $existingNation = $em->getRepository('NewBundle:Nation')->findOneBy(array('nation_name' => $nation->getName(), 'comment' => $nation->getComment()));

            if ($existingNation != null  && $existingNation->getId() != $nation->getId()) {
                return $existingNation;
            }
        } else{

            $existingNation = $em->getRepository('NewBundle:Nation')->findOneByName($nation->getName());

            if ($existingNation != null  && $existingNation->getId() != $nation->getId()) {
                return $existingNation;
            }
        }
        
        $this->LOGGER->debug("Did not find existing nation");
            
        $em->persist($nation);
        $em->flush();
        
        return $nation;
    }
    
    public function getJob($em, $job) {      
        if(is_null($job)){
            return $job;
        }
        
        if($job->getComment() != null){
            $existingJob = $em->getRepository('NewBundle:Job')->findOneBy(array('label' => $job->getLabel(), 'comment' => $job->getComment()));

            if ($existingJob != null  && $existingJob->getId() != $job->getId()) {
                return $existingJob;
            }
        } else{

            $existingJob = $em->getRepository('NewBundle:Job')->findOneByLabel($job->getLabel());

            if ($existingJob != null  && $existingJob->getId() != $job->getId()) {
                return $existingJob;
            }
        }
        
        $this->LOGGER->debug("Did not find existing job");
            
        $em->persist($job);
        $em->flush();
        
        return $job;
    }
    
    public function getJobClass($em, $jobClass) {
        
        if(is_null($jobClass)){
            return $jobClass;
        }
        
        if($jobClass->getComment() != null){
            $existingJobClass = $em->getRepository('NewBundle:JobClass')->findOneBy(array('label' => $jobClass->getLabel(), 'comment' => $jobClass->getComment()));

            if ($existingJobClass != null  && $existingJobClass->getId() != $jobClass->getId()) {
                return $existingJobClass;
            }
        } else{

            $existingJobClass = $em->getRepository('NewBundle:JobClass')->findOneByLabel($jobClass->getLabel());

            if ($existingJobClass != null  && $existingJobClass->getId() != $jobClass->getId()) {
                return $existingJobClass;
            }
        }
        
        $this->LOGGER->debug("Did not find existing jobclass");
            
        $em->persist($jobClass);
        $em->flush();
        
        return $jobClass;
    }
}