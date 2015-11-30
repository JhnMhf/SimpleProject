<?php

namespace UR\DB\OldBundle\Entity;

/**
 * Rang
 */
class Rang
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
    private $vonAb;

    /**
     * @var string
     */
    private $rang;

    /**
     * @var string
     */
    private $rangklasse;

    /**
     * @var string
     */
    private $belegt;

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
    private $territorium;

    /**
     * @var string
     */
    private $kommentar;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return Rang
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
     * @return Rang
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
     * @return Rang
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
     * Set vonAb
     *
     * @param string $vonAb
     *
     * @return Rang
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
     * Set rang
     *
     * @param string $rang
     *
     * @return Rang
     */
    public function setRang($rang)
    {
        $this->rang = $rang;

        return $this;
    }

    /**
     * Get rang
     *
     * @return string
     */
    public function getRang()
    {
        return $this->rang;
    }

    /**
     * Set rangklasse
     *
     * @param string $rangklasse
     *
     * @return Rang
     */
    public function setRangklasse($rangklasse)
    {
        $this->rangklasse = $rangklasse;

        return $this;
    }

    /**
     * Get rangklasse
     *
     * @return string
     */
    public function getRangklasse()
    {
        return $this->rangklasse;
    }

    /**
     * Set belegt
     *
     * @param string $belegt
     *
     * @return Rang
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
     * Set ort
     *
     * @param string $ort
     *
     * @return Rang
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
     * @return Rang
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
     * Set territorium
     *
     * @param string $territorium
     *
     * @return Rang
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
     * Set kommentar
     *
     * @param string $kommentar
     *
     * @return Rang
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

