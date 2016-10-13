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
    const NAME_CSV_FILE = "@NewBundle/Resources/files/name_abbreviations.csv";
    const PATRONYM_CSV_FILE = "@NewBundle/Resources/files/patronym_abbreviations.csv";
    const FIRSTNAME_CSV_FILE = "@NewBundle/Resources/files/firstname_abbreviations.csv";
    const DELIMITER = "=";
    
    private $LOGGER;
    private $container;
    
    private $abbreviationKeys;
    private $abbreviationValues;
    
    private $nameAbbreviationKeys;
    private $nameAbbreviationValues;
    
    private $patronymAbbreviationKeys;
    private $patronymAbbreviationValues;
    
    private $firstnameAbbreviationKeys;
    private $firstnameAbbreviationValues;
    
    public function __construct($container)
    {
        $this->container = $container;
        $this->LOGGER = $this->get('monolog.logger.migrateNew');
        $this->createAbbreviationsMap();
        $this->createNameAbbreviationsMap();
        $this->createFirstnameAbbreviationsMap();
        $this->createPatronymAbbreviationsMap();
    }
    
    private function createAbbreviationsMap(){
        $abbreviationsMap = [];
        
        $path = $this->get('kernel')->locateResource(self::CSV_FILE);
       
        $lines = file($path);

        foreach($lines as $line)
        {
            $splittedLine = explode(self::DELIMITER,$line);
            //first trim, to remove more than one empty space, then add one at 
            //the start and the end, to prevent replacing inside of an word
            $abbreviationsMap[" ".trim($splittedLine[0])." "] = " ".trim($splittedLine[1])." ";
            if(strlen(trim($splittedLine[1])) <= 3 && strpos($splittedLine[1], ".")){
                $abbreviationsMap[" ".trim($splittedLine[0])] = " ".trim($splittedLine[1]);
                $abbreviationsMap[trim($splittedLine[0])." "] = trim($splittedLine[1])." ";
            } else {
                $abbreviationsMap[trim($splittedLine[0])] = trim($splittedLine[1]);
            }
        }
        
        $keys = array_map('strlen', array_keys($abbreviationsMap));
        array_multisort($keys, SORT_DESC, $abbreviationsMap);
        
        $this->abbreviationKeys = array_keys($abbreviationsMap);
        $this->abbreviationValues = array_values($abbreviationsMap);
    }
    
    private function createNameAbbreviationsMap(){
        $abbreviationsMap = [];
        
        $path = $this->get('kernel')->locateResource(self::NAME_CSV_FILE);
       
        $lines = file($path);

        foreach($lines as $line)
        {
            $splittedLine = explode(self::DELIMITER,$line);
            //first trim, to remove more than one empty space, then add one at 
            //the start and the end, to prevent replacing inside of an word
            $abbreviationsMap[" ".trim($splittedLine[0])." "] = " ".trim($splittedLine[1])." ";
            if(strlen(trim($splittedLine[1])) <= 3 && strpos($splittedLine[1], ".")){
                $abbreviationsMap[" ".trim($splittedLine[0])] = " ".trim($splittedLine[1]);
                $abbreviationsMap[trim($splittedLine[0])." "] = trim($splittedLine[1])." ";
            } else {
                $abbreviationsMap[trim($splittedLine[0])] = trim($splittedLine[1]);
            }
        }
        
        $keys = array_map('strlen', array_keys($abbreviationsMap));
        array_multisort($keys, SORT_DESC, $abbreviationsMap);
        
        $this->nameAbbreviationKeys = array_keys($abbreviationsMap);
        $this->nameAbbreviationValues = array_values($abbreviationsMap);
    }
    
    private function createPatronymAbbreviationsMap(){
        $abbreviationsMap = [];
        
        $path = $this->get('kernel')->locateResource(self::PATRONYM_CSV_FILE);
       
        $lines = file($path);

        foreach($lines as $line)
        {
            $splittedLine = explode(self::DELIMITER,$line);
            //first trim, to remove more than one empty space, then add one at 
            //the start and the end, to prevent replacing inside of an word
            $abbreviationsMap[" ".trim($splittedLine[0])." "] = " ".trim($splittedLine[1])." ";
            if(strlen(trim($splittedLine[1])) <= 3 && strpos($splittedLine[1], ".")){
                $abbreviationsMap[" ".trim($splittedLine[0])] = " ".trim($splittedLine[1]);
                $abbreviationsMap[trim($splittedLine[0])." "] = trim($splittedLine[1])." ";
            } else {
                $abbreviationsMap[trim($splittedLine[0])] = trim($splittedLine[1]);
            }
        }
        
        $keys = array_map('strlen', array_keys($abbreviationsMap));
        array_multisort($keys, SORT_DESC, $abbreviationsMap);
        
        $this->patronymAbbreviationKeys = array_keys($abbreviationsMap);
        $this->patronymAbbreviationValues = array_values($abbreviationsMap);
    }
    
    private function createFirstnameAbbreviationsMap(){
        $abbreviationsMap = [];
        
        $path = $this->get('kernel')->locateResource(self::FIRSTNAME_CSV_FILE);
       
        $lines = file($path);

        foreach($lines as $line)
        {
            $splittedLine = explode(self::DELIMITER,$line);
            //first trim, to remove more than one empty space, then add one at 
            //the start and the end, to prevent replacing inside of an word
            $abbreviationsMap[" ".trim($splittedLine[0])." "] = " ".trim($splittedLine[1])." ";
            if(strlen(trim($splittedLine[1])) <= 3 && strpos($splittedLine[1], ".")){
                $abbreviationsMap[" ".trim($splittedLine[0])] = " ".trim($splittedLine[1]);
                $abbreviationsMap[trim($splittedLine[0])." "] = trim($splittedLine[1])." ";
            } else {
                $abbreviationsMap[trim($splittedLine[0])] = trim($splittedLine[1]);
            }
        }
        
        $keys = array_map('strlen', array_keys($abbreviationsMap));
        array_multisort($keys, SORT_DESC, $abbreviationsMap);
        
        $this->firstnameAbbreviationKeys = array_keys($abbreviationsMap);
        $this->firstnameAbbreviationValues = array_values($abbreviationsMap);
    }
    
    private function get($identifier){
        return $this->container->get($identifier);
    }
    
    public function writeOutAbbreviations($string){
        if ($string == "" || $string == null) {
            return null;
        }
        
        $lowerCaseString = strtolower($string);
        
        $containsAnmerkung = strpos($string, strtolower("- Anmerkung:"));
        $containsImOriginal = strpos($string, strtolower("- im Original"));
        
        if($containsAnmerkung || $containsImOriginal){
            return $string;
        }
        
        if($string == "keine Angaben" || $string == "keine An gabe" || $string == "Unbekannt"){
            return "keine Angabe";
        } else if($string == "?"){
            return null;
        }
        
        $trimmedString = trim($string);
        
        $newString = $string;
        
        if(in_array($trimmedString, $this->abbreviationKeys)){
            $this->LOGGER->debug("Found in keys");
            $newString = $this->abbreviationValues[array_search($trimmedString, $this->abbreviationKeys)];
        } else {
            $this->LOGGER->debug("Using str_replace");
            $newString = str_replace($this->abbreviationKeys, $this->abbreviationValues,$newString);
        }

        if($newString != $string){
            $this->LOGGER->debug("Normalized ".$string." to ".$newString);
            
        } else {
            $this->LOGGER->debug("Could not/ Didn't need to normalize string: ".$string);
        }
        
        return $newString;
    }
    
    public function writeOutNameAbbreviations($string){
        if ($string == "" || $string == null) {
            return null;
        }
        
        $lowerCaseString = strtolower($string);
        
        $containsAnmerkung = strpos($string, strtolower("- Anmerkung:"));
        $containsImOriginal = strpos($string, strtolower("- im Original"));
        
        if($containsAnmerkung || $containsImOriginal){
            return $string;
        }
        
        if($string == "keine Angaben" || $string == "keine An gabe" || $string == "Unbekannt"){
            return "keine Angabe";
        } else if($string == "?"){
            return null;
        }
        
        return str_replace($this->nameAbbreviationKeys, $this->nameAbbreviationValues,$string);
    }
    
    public function writeOutPatronymAbbreviations($string){
        if ($string == "" || $string == null) {
            return null;
        }
        
        $lowerCaseString = strtolower($string);
        
        $containsAnmerkung = strpos($string, strtolower("- Anmerkung:"));
        $containsImOriginal = strpos($string, strtolower("- im Original"));
        
        if($containsAnmerkung || $containsImOriginal){
            return $string;
        }
        
        if($string == "keine Angaben" || $string == "keine An gabe" || $string == "Unbekannt"){
            return "keine Angabe";
        } else if($string == "?"){
            return null;
        }
        
        return  str_replace($this->patronymAbbreviationKeys, $this->patronymAbbreviationValues,$string);
    }
    
    public function writeOutFirstnameAbbreviations($string){
        if ($string == "" || $string == null) {
            return null;
        }
        
        $lowerCaseString = strtolower($string);
        
        $containsAnmerkung = strpos($string, strtolower("- Anmerkung:"));
        $containsImOriginal = strpos($string, strtolower("- im Original"));
        
        if($containsAnmerkung || $containsImOriginal){
            return $string;
        }
        
        if($string == "keine Angaben" || $string == "keine An gabe" || $string == "Unbekannt"){
            return "keine Angabe";
        } else if($string == "?"){
            return null;
        }
        
        return  str_replace($this->firstnameAbbreviationKeys, $this->firstnameAbbreviationValues,$string);
    }
}
