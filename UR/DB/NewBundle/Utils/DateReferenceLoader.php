<?php

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

class DateReferenceLoader {
    
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
    
    public function loadDateReferenceFromString($em, $dateReference){
        $dateReferenceArray = explode(",",$dateReference);
        
        return $this->loadDateReferenceFromArray($em, $dateReferenceArray);
    }
   
    public function loadDateReferenceFromArray($em, $dateReference){
        $this->LOGGER->debug("Loading DateReference.");
        
        if(count($dateReference) == 0){
            return array();
        }
        
        $repository = $em->getRepository("NewBundle:Date");
       
        
        $objArray = [];

        for($i = 0; $i < count($dateReference); $i++){
            $this->LOGGER->debug("Loading: ".$dateReference[$i]);
            $dateReferenceOjb = null;
            if (DateRange::isDateRange($dateReference[$i])) {
                //date range found
                $dateReferenceOjb = DateRange::createDateRange($dateReference[$i], $repository);
            }else {
                $dateReferenceOjb = $repository->findOneById($dateReference[$i]);
            }
            
            $this->LOGGER->debug("Loading finished: ".$dateReferenceOjb);
            
            if(!is_null($dateReferenceOjb)){
                $objArray[] = $dateReferenceOjb;
            }
        }
        
        $this->LOGGER->debug("Finished loading DateReference.");
        
        return $objArray;
    }
}