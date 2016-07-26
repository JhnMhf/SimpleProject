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
class NormalizationService {
   
    const CSV_FILE = "@NewBundle/Resources/files/abbreviations.csv";
    const DELIMITER = "=";
    
    private $LOGGER;
    private $container;
    
    private $abbreviationKeys;
    private $abbreviationValues;
    
    public function __construct($container)
    {
        $this->container = $container;
        $this->LOGGER = $this->get('monolog.logger.migrateNew');
        $this->createAbbreviationsMap();
    }
    
    //@TODO: Add unification for "?", "keine Angabe", "keine An gabe", "Unbekannt", leer
    private function createAbbreviationsMap(){
        $abbreviationsMap = [];
        
        $path = $this->get('kernel')->locateResource(self::CSV_FILE);
       
        $lines = file($path);

        foreach($lines as $line)
        {
            $splittedLine = explode(self::DELIMITER,$line);
            $abbreviationsMap[trim($splittedLine[0])] = trim($splittedLine[1]);
        }
        
        $keys = array_map('strlen', array_keys($abbreviationsMap));
        array_multisort($keys, SORT_DESC, $abbreviationsMap);
        
        $this->abbreviationKeys = array_keys($abbreviationsMap);
        $this->abbreviationValues = array_values($abbreviationsMap);
    }
    
    private function get($identifier){
        return $this->container->get($identifier);
    }
    
    public function writeOutAbbreviations($string){
        //@TODO: Andreas Michaußeretatmäßig should not happen.
        return  str_replace($this->abbreviationKeys, $this->abbreviationValues,$string);
    }
    
    //@TODO: Add GND Database?
}
