<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UR\DB\NewBundle\Types;


use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Description of DateReference
 *
 * @author johanna
 */
//http://php-and-symfony.matthiasnoback.nl/2012/09/symfony2-mongodb-odm-creating-custom-types-with-dependencies/
//https://groups.google.com/forum/#!topic/doctrine-user/caLWxvR1mXA
//http://doctrine-orm.readthedocs.org/projects/doctrine-dbal/en/latest/reference/types.html
//http://symfony.com/doc/2.0/cookbook/doctrine/dbal.html#registering-custom-mapping-types-in-the-schematool
//http://symfony2.ylly.fr/add-new-data-type-in-doctrine-2-in-symfony-2-jordscream/
//http://doctrine-orm.readthedocs.org/projects/doctrine-orm/en/latest/cookbook/custom-mapping-types.html
class DateReference extends Type
{
    const DATE_REFERENCE = 'date_reference';
    
    private $newDBManager;

    public function setEntityManager($em){
        $this->newDBManager = $em;
    }
 
    public function getName()
    {
        return self::DATE_REFERENCE;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "VARCHAR(200)";
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        //expects list of date objects
        //returns comma separated string
        
        $dateIdArray = [];

        for($i = 0; $i < count($value); $i++){
            $dateId = $value[$i]->getId();
            $dateIdArray[] = $dateId;
        }

        return $this->createStringFromIdArray($dateIdArray);
    }
    
    private function createStringFromIdArray($idArray){

        $uniqueArray = array_unique($idArray);

        return implode(",", $uniqueArray);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        //expects comma separated string
        //returns list of csv objects
        
        $datesArray = explode(",", $value);
        
        $objArray = [];
        
        for($i = 0; $i < count($datesArray); $i++){
            $objArray[] = $this->newDBManager->getRepository("NewBundle:Date")->findOneById($datesArray[$i]);
        }
        
        return $objArray;
    }
}