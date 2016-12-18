<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UR\DB\NewBundle\Utils;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

/**
 * Description of FixDBOrdersEventSubscriber
 *
 * @author johanna
 */
//http://culttt.com/2014/08/04/understanding-doctrine-2-lifecycle-events/
//http://symfony.com/doc/current/cookbook/doctrine/event_listeners_subscribers.html

class DateReferenceSubscriber implements EventSubscriber {
    
    private $LOGGER;
    private $container;
    private $dateReferenceLoader;
    
    public function __construct($container)
    {
        $this->container = $container;
        $this->LOGGER = $this->get('monolog.logger.default');
        $this->dateReferenceLoader = $this->get('date_reference_loader');
    } 
    
    private function get($identifier){
        return $this->container->get($identifier);
    }
    
    public function preUpdate(LifecycleEventArgs $event)
    {
        $this->LOGGER->debug("preUpdate called!");
        $this->saveDateToDB($event);
    }
    
    public function prePersist(LifecycleEventArgs $event)
    {
        $this->LOGGER->debug("prePersist called!");
        $this->saveDateToDB($event);
    }
    
    public function postLoad(LifecycleEventArgs $event)
    {
        $this->LOGGER->debug("postLoad called: ". get_class($event->getEntity()));
        $this->loadDatesFromDB($event);
    }
    
    private function loadDatesFromDB(LifecycleEventArgs $event){
        $entity = $event->getEntity();

        $em = $event->getEntityManager();
        
        $this->LOGGER->debug("Loading Dates from db for: ".get_class($entity));
        
         switch(get_class($entity)){
            case "UR\DB\NewBundle\Entity\Baptism":
                $this->LOGGER->debug("Found baptism entity");
                $entity->setBaptismDate($this->dateReferenceLoader->loadDateReferenceFromArray($em, $entity->getBaptismDate()));
                break;
            case "UR\DB\NewBundle\Entity\Birth":
                $this->LOGGER->debug("Found birth entity");
                $entity->setBirthDate($this->dateReferenceLoader->loadDateReferenceFromArray($em, $entity->getBirthDate()));
                $entity->setProvenDate($this->dateReferenceLoader->loadDateReferenceFromArray($em, $entity->getProvenDate()));
                break;
            case "UR\DB\NewBundle\Entity\Death":
                $this->LOGGER->debug("Found death entity");
                $entity->setDeathDate($this->dateReferenceLoader->loadDateReferenceFromArray($em, $entity->getDeathDate()));
                $entity->setFuneralDate($this->dateReferenceLoader->loadDateReferenceFromArray($em, $entity->getFuneralDate()));
                break;
            case "UR\DB\NewBundle\Entity\Education":
                $this->LOGGER->debug("Found education entity");
                $this->loadFromToProvenDates($em, $entity);
                $entity->setGraduationDate($this->dateReferenceLoader->loadDateReferenceFromArray($em, $entity->getGraduationDate()));
                break;
            case "UR\DB\NewBundle\Entity\Honour":
                $this->LOGGER->debug("Found honour entity");
                $this->loadFromToProvenDates($em, $entity);
                break;
            case "UR\DB\NewBundle\Entity\Property":
                $this->LOGGER->debug("Found property entity");
                $this->loadFromToProvenDates($em, $entity);
                break;
            case "UR\DB\NewBundle\Entity\Rank":
                $this->LOGGER->debug("Found rank entity");
                $this->loadFromToProvenDates($em, $entity);
                break;
            case "UR\DB\NewBundle\Entity\Religion":
                $this->LOGGER->debug("Found religion entity");
                $this->loadFromToProvenDates($em, $entity);
                break;
            case "UR\DB\NewBundle\Entity\RoadOfLife":
                $this->LOGGER->debug("Found roadOfLife entity");
                $this->loadFromToProvenDates($em, $entity);
                break;
            case "UR\DB\NewBundle\Entity\Status":
                $this->LOGGER->debug("Found status entity");
                $this->loadFromToProvenDates($em, $entity);
                break;
            case "UR\DB\NewBundle\Entity\Wedding":
                $this->LOGGER->debug("Found wedding entity");
                $entity->setWeddingDate($this->dateReferenceLoader->loadDateReferenceFromArray($em, $entity->getWeddingDate()));
                $entity->setBannsDate($this->dateReferenceLoader->loadDateReferenceFromArray($em, $entity->getBannsDate()));
                $entity->setBreakupDate($this->dateReferenceLoader->loadDateReferenceFromArray($em, $entity->getBreakupDate()));
                $entity->setProvenDate($this->dateReferenceLoader->loadDateReferenceFromArray($em, $entity->getProvenDate()));
                break;
            case "UR\DB\NewBundle\Entity\Works":
                $this->LOGGER->debug("Found works entity");
                $this->loadFromToProvenDates($em, $entity);        
                break;
            default:
                $this->LOGGER->debug("Nothing to do here");
                break;
        }
        
        
        $this->LOGGER->debug("Finished loading Dates from db.");
    }
    
    private function loadFromToProvenDates($em, $entity){
        $entity->setFromDate($this->dateReferenceLoader->loadDateReferenceFromArray($em, $entity->getFromDate()));
        $entity->setToDate($this->dateReferenceLoader->loadDateReferenceFromArray($em, $entity->getToDate()));
        $entity->setProvenDate($this->dateReferenceLoader->loadDateReferenceFromArray($em, $entity->getProvenDate()));
    }

    private function saveDateToDB(LifecycleEventArgs $event){
        $entity = $event->getEntity();

        $em = $event->getEntityManager();
        
        $this->LOGGER->debug("Saving Dates to db for: ".$entity);
        
        switch(get_class($entity)){
            case "UR\DB\NewBundle\Entity\Date":
                return;
            case "UR\DB\NewBundle\Entity\Partner":
            case "UR\DB\NewBundle\Entity\Person":
            case "UR\DB\NewBundle\Entity\Relative":
                $this->LOGGER->debug("Found 'person' entity: ".$entity);
                //@TODO: Is there sth to do here?
                break;
            case "UR\DB\NewBundle\Entity\Baptism":
                $this->LOGGER->debug("Found baptism entity");
                $this->saveDateReference($em, $entity->getBaptismDate());
                break;
            case "UR\DB\NewBundle\Entity\Birth":
                $this->LOGGER->debug("Found birth entity");
                $this->saveDateReference($em, $entity->getBirthDate());
                $this->saveDateReference($em, $entity->getProvenDate());
                break;
            case "UR\DB\NewBundle\Entity\Death":
                $this->LOGGER->debug("Found death entity");
                $this->saveDateReference($em, $entity->getDeathDate());
                $this->saveDateReference($em, $entity->getFuneralDate());
                break;
            case "UR\DB\NewBundle\Entity\Education":
                $this->LOGGER->debug("Found education entity");
                $this->saveFromToProvenDates($em, $entity);
                $this->saveDateReference($em, $entity->getGraduationDate());
                break;
            case "UR\DB\NewBundle\Entity\Honour":
                $this->LOGGER->debug("Found honour entity");
                $this->saveFromToProvenDates($em, $entity);
                break;
             case "UR\DB\NewBundle\Entity\Property":
                $this->LOGGER->debug("Found property entity");
                $this->saveFromToProvenDates($em, $entity);
                break;
            case "UR\DB\NewBundle\Entity\Rank":
                $this->LOGGER->debug("Found rank entity");
                $this->saveFromToProvenDates($em, $entity);
                break;
            case "UR\DB\NewBundle\Entity\Religion":
                $this->LOGGER->debug("Found religion entity");
                $this->saveFromToProvenDates($em, $entity);
                break;
            case "UR\DB\NewBundle\Entity\RoadOfLife":
                $this->LOGGER->debug("Found roadOfLife entity");
                $this->saveFromToProvenDates($em, $entity);
                break;
            case "UR\DB\NewBundle\Entity\Status":
                $this->LOGGER->debug("Found status entity");
                $this->saveFromToProvenDates($em, $entity);
                break;
            case "UR\DB\NewBundle\Entity\Wedding":
                $this->LOGGER->debug("Found wedding entity");
                $this->saveDateReference($em, $entity->getWeddingDate());
                $this->saveDateReference($em, $entity->getBannsDate());
                $this->saveDateReference($em, $entity->getBreakupDate());
                $this->saveDateReference($em, $entity->getProvenDate());
                break;
            case "UR\DB\NewBundle\Entity\Works":
                $this->LOGGER->debug("Found works entity");
                $this->saveFromToProvenDates($em, $entity);        
                break;
            default:
                $this->LOGGER->debug("Nothing to do here");
                break;
        }
        
        
        $this->LOGGER->debug("Finished saving Dates to db for: ".$entity);
        //NO FLUSH ALLOWED IN PRE UPDATE/ PRE PERSIST!
        //http://stackoverflow.com/questions/30576245/infinite-loop-in-doctrine-event-listener-when-trying-to-save-additional-entity
    }
    
    private function saveFromToProvenDates($em, $entity){
        $this->saveDateReference($em, $entity->getFromDate());
        $this->saveDateReference($em, $entity->getToDate());
        $this->saveDateReference($em, $entity->getProvenDate());
    }
    
    private function saveDateReference($em, $dateReference){
        //$this->LOGGER->debug("Saving dateReference: ".print_r($dateReference, true));
        if(is_null($dateReference) || count($dateReference) == 0){
            return;
        }

        for($i = 0; $i < count($dateReference); $i++){
            //$this->LOGGER->debug("Persisting: ".print_r($dateReference[$i], true));
            
            if(!is_array($dateReference[$i]) && get_class($dateReference[$i]) == "UR\DB\NewBundle\Utils\DateRange"){
                $this->saveDate($em, $dateReference[$i]->getFrom());
                $this->saveDate($em, $dateReference[$i]->getTo());
            } else {
                $this->saveDate($em, $dateReference[$i]);
  
            }
            
            //$this->LOGGER->debug("Persisting finished: ".print_r($dateReference[$i], true));
        }
    }
    
    private function saveDate($em, $date){
            $this->LOGGER->debug("Saving date: ".$date);

            if(!is_null($date->getId()) && $this->dateAlreadyExists($em, $date->getId())){
                $em->merge($date);
            }else {
                $em->persist($date);
            }
            
            $this->LOGGER->debug("Saved date: ".$date);
    }
    
    private function dateAlreadyExists($em, $id){
        return $em->getRepository('NewBundle:Date')->findOneById($id) != null;
    }
  

    
    public function getSubscribedEvents()
    {
        return [Events::preUpdate, Events::prePersist, Events::postLoad];
    }
 
}