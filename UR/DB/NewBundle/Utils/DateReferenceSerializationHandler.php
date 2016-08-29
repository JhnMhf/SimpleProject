<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UR\DB\NewBundle\Utils;

use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\SerializationContext;

/**
 * Description of FixDBOrdersEventSubscriber
 *
 * @author johanna
 */
//http://jmsyst.com/libs/serializer
//http://jmsyst.com/libs/serializer/master/handlers
//https://github.com/schmittjoh/JMSSerializerBundle/blob/master/DependencyInjection/Compiler/CustomHandlersPass.php
//http://blog.spdionis.com/symfony/jmsserializer/2014/11/27/add-data-to-serialized-entity.html
//http://stackoverflow.com/questions/15007281/add-extra-fields-using-jms-serializer-bundle/16675627#16675627
//http://blog.logicexception.com/2012/06/creating-custom-jmsserializerbundle.html

//Alternative: http://thomas.jarrand.fr/blog/serialization/

class DateReferenceSerializationHandler implements SubscribingHandlerInterface {

    private $LOGGER;
    private $container;
    private $serializer;

    public function __construct($container) {
        $this->container = $container;
        $this->LOGGER = $this->get('monolog.logger.default');
        $this->serializer = $this->get('serializer');
    }

    private function get($identifier) {
        return $this->container->get($identifier);
    }

    public static function getSubscribingMethods() {

        return array(
            array(
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => 'json',
                'type' => 'UR\DB\NewBundle\Types\DateReference',
                'method' => 'deserializeJsonToDateReference',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => 'UR\DB\NewBundle\Types\DateReference',
                'method' => 'serializeDateReferenceToJson',
            ),
        );
    }

    public function deserializeJsonToDateReference(JsonDeserializationVisitor $visitor, $json, array $type, DeserializationContext $context) {
        if (array_key_exists("from", $json)) {
            $from = $this->createDateObj($json['from']);
            $to = $this->createDateObj($json['to']);

            return new DateRange($from, $to);
        } else {
            //create date
            return $this->createDateObj($json);
        }
    }

    //Example: https://github.com/schmittjoh/serializer/blob/master/src/JMS/Serializer/Handler/ArrayCollectionHandler.php
    public function serializeDateReferenceToJson(JsonSerializationVisitor $visitor, $obj, array $type, SerializationContext $context) {
        $this->LOGGER->debug("Serializing DateReference: " . print_r($obj, true));
        
        $type['name'] = 'array';

        if (get_class($obj) == "UR\DB\NewBundle\Utils\DateRange") {
            //date range found
            $dateRangeArray = array();

            $dateRangeArray['from'] = $this->dateToArray($obj->getFrom());
            $dateRangeArray['to'] = $this->dateToArray($obj->getTo());
            
            return $visitor->visitArray($dateRangeArray, $type, $context);
        } else {
            $dateArray = $this->dateToArray($obj);
            
            return $visitor->visitArray($dateArray, $type, $context);
        }
    }

    private function dateToArray($date) {
        $dateArray = array();

        $dateArray["id"] = $date->getId();
        if (!is_null($date->getDay())) {
            $dateArray["day"] = $date->getDay();
        }

        if (!is_null($date->getMonth())) {
            $dateArray["month"] = $date->getMonth();
        }

        if (!is_null($date->getYear())) {
            $dateArray["year"] = $date->getYear();
        }
        if (!is_null($date->getWeekday())) {
            $dateArray["weekday"] = $date->getWeekday();
        }

        if (!is_null($date->getComment())) {
            $dateArray["comment"] = $date->getComment();
        }

        $dateArray["before_date"] = $date->getBeforeDate();
        $dateArray["after_date"] = $date->getAfterDate();


        return $dateArray;
    }

    private function createDateObj($dateArrayFromJson) {
        $date = new \UR\DB\NewBundle\Entity\Date();
        if (!array_key_exists("id", $dateArrayFromJson)) {
            print_r($dateArrayFromJson);
        }
        $date->setId($dateArrayFromJson['id']);

        if (array_key_exists("day", $dateArrayFromJson)) {
            $date->setDay($dateArrayFromJson['day']);
        }

        if (array_key_exists("month", $dateArrayFromJson)) {
            $date->setMonth($dateArrayFromJson['month']);
        }

        if (array_key_exists("year", $dateArrayFromJson)) {
            $date->setYear($dateArrayFromJson['year']);
        }

        if (array_key_exists("weekday", $dateArrayFromJson)) {
            $date->setWeekday($dateArrayFromJson['weekday']);
        }

        $date->setBeforeDate($dateArrayFromJson['before_date']);
        $date->setAfterDate($dateArrayFromJson['after_date']);

        if (array_key_exists("comment", $dateArrayFromJson)) {
            $date->setComment($dateArrayFromJson['comment']);
        }

        return $date;
    }

}
