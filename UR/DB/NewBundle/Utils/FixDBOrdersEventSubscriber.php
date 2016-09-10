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

class FixDBOrdersEventSubscriber implements EventSubscriber {
    
    private $LOGGER;
    private $container;
    
    public function __construct($container)
    {
        $this->container = $container;
        $this->LOGGER = $this->get('monolog.logger.migrateNew');
    } 
    
    private function get($identifier){
        return $this->container->get($identifier);
    }
    
    public function postUpdate(LifecycleEventArgs $event)
    {
        $this->LOGGER->debug("postUpdate called!");
        $this->fixOrders($event);
    }
    
    public function postPersist(LifecycleEventArgs $event)
    {
        $this->LOGGER->debug("postPersist called!");
        $this->fixOrders($event);
    }
    
    private function fixOrders(LifecycleEventArgs $event){
        $entity = $event->getEntity();

        if (!$entity instanceOf \UR\DB\NewBundle\Entity\BasePerson ) { 
            return;
        }
        
        $personId = $entity->getId();
        
        $this->LOGGER->debug("Fixing orders for ID: ".$personId);
        
        $em = $event->getEntityManager();
        
        $education = $em->getRepository('NewBundle:Education')
                ->findBy(array('person' => $personId), array('educationOrder' => 'ASC'));
        
        $this->fixArray($em,$education, PersonInformation::EDUCATION);
        
        $honour = $em->getRepository('NewBundle:Honour')
                ->findBy(array('person' => $personId), array('honourOrder' => 'ASC'));
        
        $this->fixArray($em,$honour, PersonInformation::HONOUR);
        
        $property = $em->getRepository('NewBundle:Property')
                ->findBy(array('person' => $personId), array('propertyOrder' => 'ASC'));
        
        $this->fixArray($em,$property, PersonInformation::PROPERTY);
        
        $rank = $em->getRepository('NewBundle:Rank')
                ->findBy(array('person' => $personId), array('rankOrder' => 'ASC'));
        
        $this->fixArray($em,$rank, PersonInformation::RANK);
        
        $religion = $em->getRepository('NewBundle:Religion')
                ->findBy(array('person' => $personId), array('religionOrder' => 'ASC'));
        
        $this->fixArray($em,$religion, PersonInformation::RELIGION);
        
        $roadOfLife = $em->getRepository('NewBundle:RoadOfLife')
                ->findBy(array('person' => $personId), array('roadOfLifeOrder' => 'ASC'));
        
        $this->fixArray($em,$roadOfLife, PersonInformation::ROAD_OF_LIFE);
        
        $status = $em->getRepository('NewBundle:Status')
                ->findBy(array('person' => $personId), array('statusOrder' => 'ASC'));
        
        $this->fixArray($em,$status, PersonInformation::STATUS);
        
        $works = $em->getRepository('NewBundle:Works')
                ->findBy(array('person' => $personId), array('worksOrder' => 'ASC'));
        
        $this->fixArray($em,$works, PersonInformation::WORK);
        
        
        if ($entity instanceOf \UR\DB\NewBundle\Entity\Person ) { 
            $source = $em->getRepository('NewBundle:Source')
                ->findBy(array('person' => $personId), array('sourceOrder' => 'ASC'));
        
            $this->fixArray($em,$source, PersonInformation::SOURCE);
        }
         $em->flush();
    }

    private function fixArray($em,$array, $type){
        $this->LOGGER->debug("Fixing collection of '".$type. "' with size '".count($array)."'");
        for($i = 0; $i < count($array); $i++){
            $position = $i+1;
            $entity = $array[$i];
            
            //update order only if necessary
            if($this->getOrder($entity, $type) != $position){
                $this->setOrder($entity,$position, $type);
                $em->merge($entity);
            }else {
                $this->LOGGER->debug("No updated needed for '".$type. "' and position '".$position."'");
            }

        }
    }

    private function setOrder($entity,$position, $type){
        $this->LOGGER->debug("Setting order of type '".$type. "' to position '".$position."'");
        switch($type){
            case PersonInformation::EDUCATION:
                $entity->setEducationOrder($position);
            case PersonInformation::HONOUR:
                $entity->setHonourOrder($position);
            case PersonInformation::PROPERTY:
                $entity->setPropertyOrder($position);
            case PersonInformation::RANK:
                $entity->setRankOrder($position);
            case PersonInformation::RELIGION:
                $entity->setReligionOrder($position);
            case PersonInformation::ROAD_OF_LIFE:
                $entity->setRoadOfLifeOrder($position);
            case PersonInformation::STATUS:
                $entity->setStatusOrder($position);
            case PersonInformation::WORK:
                $entity->setWorksOrder($position);
            case PersonInformation::SOURCE:
                $entity->setSourceOrder($position);
            default:
                $this->LOGGER->error("Unknown Type: ".$type);
        }

    }
    
    private function getOrder($entity,$type){
        switch($type){
            case PersonInformation::EDUCATION:
                return $entity->getEducationOrder();
            case PersonInformation::HONOUR:
                return $entity->getHonourOrder();
            case PersonInformation::PROPERTY:
                return $entity->getPropertyOrder();
            case PersonInformation::RANK:
                return $entity->getRankOrder();
            case PersonInformation::RELIGION:
                return $entity->getReligionOrder();
            case PersonInformation::ROAD_OF_LIFE:
                return $entity->getRoadOfLifeOrder();
            case PersonInformation::STATUS:
                return $entity->getStatusOrder();
            case PersonInformation::WORK:
                return $entity->getWorksOrder();
            case PersonInformation::SOURCE:
                return $entity->getSourceOrder();
            default:
                $this->LOGGER->error("Unknown Type: ".$type);
        }
        
        
    }
    
    public function getSubscribedEvents()
    {
        return [Events::postUpdate, Events::postPersist];
    }
 
}