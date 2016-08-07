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

/**
 * Description of FixDBOrdersEventSubscriber
 *
 * @author johanna
 */
//http://jmsyst.com/libs/serializer
//http://jmsyst.com/libs/serializer/master/handlers
//https://github.com/schmittjoh/JMSSerializerBundle/blob/master/DependencyInjection/Compiler/CustomHandlersPass.php

class DateReferenceDeserializationHandler implements SubscribingHandlerInterface {

    private $LOGGER;
    private $container;

    public function __construct($container) {
        $this->container = $container;
        $this->LOGGER = $this->get('monolog.logger.default');
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
        );
    }

    public function deserializeJsonToDateReference(JsonDeserializationVisitor $visitor, $json, array $type, DeserializationContext $context) {
        $result = array();

        for ($i = 0; $i < count($json); $i++) {
            //check for daterange
            if (array_key_exists("from", $json[$i])) {
                $from = $this->createDateObj($json[$i]['from']);
                $to = $this->createDateObj($json[$i]['to']);

                $result[] = new DateRange($from, $to);
            }else {
                //create date
                $result[] = $this->createDateObj($json[$i]);
            }

            
        }

        return $result;
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
