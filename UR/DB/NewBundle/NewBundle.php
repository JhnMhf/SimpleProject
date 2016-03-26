<?php

namespace UR\DB\NewBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Doctrine\DBAL\Types\Type;
use UR\DB\NewBundle\Types\DateReference;

class NewBundle extends Bundle
{
    public function __construct()
    {
        if(!Type::hasType('date_reference')){
            Type::addType('date_reference', 'UR\DB\NewBundle\Types\DateReference');
        }
    }

    public function boot()
    {
        $entityManager = $this->container->get('doctrine')->getManager('new');

        $customType = Type::getType('date_reference');

        $customType->setEntityManager($entityManager);
    }
}
