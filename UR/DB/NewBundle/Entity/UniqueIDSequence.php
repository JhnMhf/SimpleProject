<?php

namespace UR\DB\NewBundle\Entity;

/**
 * UniqueIDSequence
 */
class UniqueIDSequence
{
    public function __toString (){
        return "UniqueID Current Highest: ".$this->getCurrentHighestId();
    }
    
    /**
     * @var integer
     */
    private $currentHighestId = 0;


    /**
     * Set currentHighestId
     *
     * @param integer $currentHighestId
     *
     * @return UniqueIDSequence
     */
    public function setCurrentHighestId($currentHighestId)
    {
        $this->currentHighestId = $currentHighestId;

        return $this;
    }

    /**
     * Get currentHighestId
     *
     * @return integer
     */
    public function getCurrentHighestId()
    {
        return $this->currentHighestId;
    }
}
