<?php

namespace UR\DB\OldBundle\Entity;

/**
 * Werke
 */
class Werke
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
    private $land;

    /**
     * @var string
     */
    private $werke;

    /**
     * @var string
     */
    private $ort;

    /**
     * @var string
     */
    private $vonAb;

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
    private $kommentar;

    /**
     * @var string
     */
    private $territorium;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return Werke
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
     * @return Werke
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
     * Set land
     *
     * @param string $land
     *
     * @return Werke
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
     * Set werke
     *
     * @param string $werke
     *
     * @return Werke
     */
    public function setWerke($werke)
    {
        $this->werke = $werke;

        return $this;
    }

    /**
     * Get werke
     *
     * @return string
     */
    public function getWerke()
    {
        return $this->werke;
    }

    /**
     * Set ort
     *
     * @param string $ort
     *
     * @return Werke
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
     * Set vonAb
     *
     * @param string $vonAb
     *
     * @return Werke
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
     * Set bis
     *
     * @param string $bis
     *
     * @return Werke
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
     * @return Werke
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
     * Set kommentar
     *
     * @param string $kommentar
     *
     * @return Werke
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

    /**
     * Set territorium
     *
     * @param string $territorium
     *
     * @return Werke
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
}

