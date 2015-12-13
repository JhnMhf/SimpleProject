<?php

namespace UR\DB\NewBundle\Entity;

use Doctrine\ORM\EntityRepository;
use UR\DB\NewBundle\Entity\Date;
/**
 * BirthRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BirthRepository extends EntityRepository
{
	public function setBirthDates($birthObj, $dateIdArray){

        if($dateIdArray != null){
    		$uniqueArray = array_unique($dateIdArray);

    		$newDateString = implode(",", $uniqueArray);

            $birthObj->setBirthDateId($newDateString);
        }
	}
   
    public function addBirthDate($birthObj, $dateId){
        $currentDateString = $birthObj->getBirthDateId();

        //expected input: id1,id5,id6
        $dateIdArray = explode(",", $currentDateString);

        foreach($dateIdArray as $currdateId){
        	if($dateId == trim($currdateId)){
        		//date in string ==> no work to do
        		return;
        	}
        }

        //date not in string
        $currentDateString .= ",".$dateId;

        $birthObj->setBirthDateId($currentDateString);
    }

    public function removeBirthDate($birthObj, $dateId){
        $currentDateString = $birthObj->getDeathDateId();

        //expected input: id1,id5,id6
        $dateIdArray = explode(",", $currentDateString);

        $indexFound = -1;

        for($i = 0; $i < count($dateIdArray); $i++){
        	if($dateId == trim($dateIdArray[$i])){
        		//date in string... need to remove it!
        		$indexFound = $i;
        	}
        }

        unset($dateIdArray[$i]);
        $newIdArray = array_values($dateIdArray);

        $newDateString = implode(",", $newIdArray);

        $birthObj->setBirthDateId($newDateString);
    }
}