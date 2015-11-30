<?php

namespace UR\DB\OldBundle\Entity;

/**
 * Lebensweg
 */
class Lebensweg
{
    /**
     * @var boolean
     */
    private $order;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $stammland;

    /**
     * @var string
     */
    private $vonAb;

    /**
     * @var string
     */
    private $beruf;

    /**
     * @var string
     */
    private $ort;

    /**
     * @var string
     */
    private $bis;

    /**
     * @var string
     */
    private $belegt;

    /**
     * @var string
     */
    private $territorium;

    /**
     * @var string
     */
    private $stammterritorium;

    /**
     * @var string
     */
    private $land;

    /**
     * @var string
     */
    private $kommentar;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return Lebensweg
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return boolean
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Lebensweg
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

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
     * Set stammland
     *
     * @param string $stammland
     *
     * @return Lebensweg
     */
    public function setStammland($stammland)
    {
        $this->stammland = $stammland;

        return $this;
    }

    /**
     * Get stammland
     *
     * @return string
     */
    public function getStammland()
    {
        return $this->stammland;
    }

    /**
     * Set vonAb
     *
     * @param string $vonAb
     *
     * @return Lebensweg
     */
    public function setVonAb($vonAb)
    {
        $this->vonAb = $vonAb;

        return $this;
    }

    /**
     * Get vonAb
     *
     * @return string
     */
    public function getVonAb()
    {
        return $this->vonAb;
    }

    /**
     * Set beruf
     *
     * @param string $beruf
     *
     * @return Lebensweg
     */
    public function setBeruf($beruf)
    {
        $this->beruf = $beruf;

        return $this;
    }

    /**
     * Get beruf
     *
     * @return string
     */
    public function getBeruf()
    {
        return $this->beruf;
    }

    /**
     * Set ort
     *
     * @param string $ort
     *
     * @return Lebensweg
     */
    public function setOrt($ort)
    {
        $this->ort = $ort;

        return $this;
    }

    /**
     * Get ort
     *
     * @return string
     */
    public function getOrt()
    {
        return $this->ort;
    }

    /**
     * Set bis
     *
     * @param string $bis
     *
     * @return Lebensweg
     */
    public function setBis($bis)
    {
        $this->bis = $bis;

        return $this;
    }

    /**
     * Get bis
     *
     * @return string
     */
    public function getBis()
    {
        return $this->bis;
    }

    /**
     * Set belegt
     *
     * @param string $belegt
     *
     * @return Lebensweg
     */
    public function setBelegt($belegt)
    {
        $this->belegt = $belegt;

        return $this;
    }

    /**
     * Get belegt
     *
     * @return string
     */
    public function getBelegt()
    {
        return $this->belegt;
    }

    /**
     * Set territorium
     *
     * @param string $territorium
     *
     * @return Lebensweg
     */
    public function setTerritorium($territorium)
    {
        $this->territorium = $territorium;

        return $this;
    }

    /**
     * Get territorium
     *
     * @return string
     */
    public function getTerritorium()
    {
        return $this->territorium;
    }

    /**
     * Set stammterritorium
     *
     * @param string $stammterritorium
     *
     * @return Lebensweg
     */
    public function setStammterritorium($stammterritorium)
    {
        $this->stammterritorium = $stammterritorium;

        return $this;
    }

    /**
     * Get stammterritorium
     *
     * @return string
     */
    public function getStammterritorium()
    {
        return $this->stammterritorium;
    }

    /**
     * Set land
     *
     * @param string $land
     *
     * @return Lebensweg
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

    /**
     * Set kommentar
     *
     * @param string $kommentar
     *
     * @return Lebensweg
     */
    public function setKommentar($kommentar)
    {
        $this->kommentar = $kommentar;

        return $this;
    }

    /**
     * Get kommentar
     *
     * @return string
     */
    public function getKommentar()
    {
        return $this->kommentar;
    }
}

