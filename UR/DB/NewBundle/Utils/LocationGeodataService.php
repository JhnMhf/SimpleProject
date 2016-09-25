<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UR\DB\NewBundle\Utils;

/**
 * Description of NormalizationService
 *
 * @author johanna
 */
class LocationGeodataService {
   
    const CSV_FILE = "@NewBundle/Resources/files/locationGeodata.csv";
    const DELIMITER = "=";
    const INTERNAL_DELIMITER = ";";
    
    private $LOGGER;
    private $container;
    
    private $mapping = [];
    
    public function __construct($container)
    {
        $this->container = $container;
        $this->LOGGER = $this->get('monolog.logger.migrateNew');
        $this->loadMapping();
    }
    
    private function loadMapping(){
        $path = $this->get('kernel')->locateResource(self::CSV_FILE);
       
        $lines = file($path);

        foreach($lines as $line)
        {
            $splittedLine = explode(self::DELIMITER,$line);
            $data = explode(self::INTERNAL_DELIMITER,trim($splittedLine[1]));
            $this->mapping[trim($splittedLine[0])] = $data;
        }
    }
    
    private function get($identifier){
        return $this->container->get($identifier);
    }
    
    public function getGeodataForLocation($location){
        if(array_key_exists($location,$this->mapping)){
            return $this->mapping[$location];
        }
        
        return null;
    }
}
