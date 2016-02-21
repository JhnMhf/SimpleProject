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
     
    }
}
