<?php

namespace UR\DB\OldBundle\Entity;

/**
 * IndexNamen
 */
class IndexNamen
{
    /**
     * @var integer
     */
    private $id = '0';

    /**
     * @var string
     */
    private $namen;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set namen
     *
     * @param string $namen
     *
     * @return IndexNamen
     */
    public function setNamen($namen)
    {
        $this->namen = $namen;

        return $this;
    }

    /**
     * Get namen
     *
     * @return string
     */
    public function getNamen()
    {
        return $this->namen;
    }
}

