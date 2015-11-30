<?php

namespace UR\DB\OldBundle\Entity;

/**
 * IndexOrte
 */
class IndexOrte
{
    /**
     * @var integer
     */
    private $id = '0';

    /**
     * @var string
     */
    private $orte;

    /**
     * @var string
     */
    private $territorien;

    /**
     * @var string
     */
    private $land;


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
     * Set orte
     *
     * @param string $orte
     *
     * @return IndexOrte
     */
    public function setOrte($orte)
    {
        $this->orte = $orte;

        return $this;
    }

    /**
     * Get orte
     *
     * @return string
     */
    public function getOrte()
    {
        return $this->orte;
    }

    /**
     * Set territorien
     *
     * @param string $territorien
     *
     * @return IndexOrte
     */
    public function setTerritorien($territorien)
    {
        $this->territorien = $territorien;

        return $this;
    }

    /**
     * Get territorien
     *
     * @return string
     */
    public function getTerritorien()
    {
        return $this->territorien;
    }

    /**
     * Set land
     *
     * @param string $land
     *
     * @return IndexOrte
     */
    public function setLand($land)
    {
        $this->land = $land;

        return $this;
    }

    /**
     * Get land
     *
     * @return string
     */
    public function getLand()
    {
        return $this->land;
    }
}

