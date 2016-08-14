<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UR\DB\NewBundle\Utils;

/**
 * Description of DateRange
 *
 * @author johanna
 */
class DateRange {
    
    const RANGE_DELIMITER = "-";
    
    private $from;
    private $to;
    
    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }
    
    public function getFrom(){
        return $this->from;
    }
    
    public function setFrom($from){
        $this->from = $from;
    }
    
    public function getTo(){
        return $this->to;
    }
    
    public function setTo($to){
        $this->to = $to;
    }
    
    public function getId(){
        return $this->toDateReferenceString();
    }
    
    public function toDateReferenceString(){
        return $this->from->getId().self::RANGE_DELIMITER.$this->to->getId();
    }
    
    public static function isDateRange($string){
        $strpos = strpos($string , self::RANGE_DELIMITER);
        
        if($strpos === false){
            return false;
        }
        
        return true;
    }
    
    public static function createDateRange($string, $repository){
        $datesArray = explode(self::RANGE_DELIMITER, $string);
        
        $from = $repository->findOneById($datesArray[0]);
        $to = $repository->findOneById($datesArray[1]);
        
        return new DateRange($from, $to);
    }
    
    public function __toString (){
        return "DateRange from Date with ID: ".$this->getFrom(). " to Date with ID: ".$this->getTo();
    }
}
