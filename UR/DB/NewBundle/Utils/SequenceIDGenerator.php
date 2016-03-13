<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UR\DB\NewBundle\Utils;

use Doctrine\ORM\Id\AbstractIdGenerator;

// http://stackoverflow.com/questions/14022374/the-differences-between-generatedvalue-strategies
// http://stackoverflow.com/questions/9087585/generating-next-sequence-value-manually-in-doctrine-2/28561017#28561017


/**
 * Description of SequenceIDGenerator
 *
 * @author johanna
 */
class SequenceIDGenerator extends AbstractIdGenerator {
    
    public function generate(\Doctrine\ORM\EntityManager $em, $entity)
    {
        // Create id here
        $id = $em->getRepository("NewBundle:UniqueIDSequence")->nextId();
        return $id;
    }
}
