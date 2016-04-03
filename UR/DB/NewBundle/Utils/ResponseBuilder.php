<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UR\DB\NewBundle\Utils;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of JSONPayloadBuilder
 *
 * @author johanna
 */
class ResponseBuilder {
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
        $this->LOGGER = $this->get('monolog.logger.default');
    }

    private function get($identifier){
        return $this->container->get($identifier);
    }
    
    public function getJSONResponse($obj){
        if(get_class($obj) == self::PERSON_CLASS){
            //$this->LOGGER->debug("PERSON: " . $obj . " has Sources: " . count($obj->getSources()));
        }
        
        $serializer = $this->container->get('serializer');
        $json = $serializer->serialize($obj, 'json');
        $response = new JsonResponse();
        $response->setContent($json);
        
        return $response;
    }
    
    public function getXMLResponse($obj){
        if(get_class($obj) == self::PERSON_CLASS){
            //$this->LOGGER->debug("PERSON: " . $obj . " has Sources: " . count($obj->getSources()));
        }
        
        $serializer = $this->container->get('serializer');
        $xml = $serializer->serialize($obj, 'xml');
        $response = new Response($xml);
        $response->headers->set('Content-Type', 'xml');
        
        return $response;
    }
}
