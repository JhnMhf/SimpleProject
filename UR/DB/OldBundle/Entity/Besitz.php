<?php

namespace UR\DB\OldBundle\Entity;

/**
 * Besitz
 */
class Besitz
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
    private $besitz;

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
    private $kommentar;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return Besitz
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
     * @return Besitz
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
     * @return Besitz
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
     * Set besitz
     *
     * @param string $besitz
     *
     * @return Besitz
     */
    public function setBesitz($besitz)
    {
        $this->besitz = $besitz;

        return $this;
    }

    /**
     * Get besitz
     *
     * @return string
     */
    public function getBesitz()
    {
        return $this->besitz;
    }

    /**
     * Set belegt
     *
     * @param string $belegt
     *
     * @return Besitz
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
     * @return Besitz
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
     * Set ort
     *
     * @param string $ort
     *
     * @return Besitz
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
     * @return Besitz
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
     * @return Besitz
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
     * Set kommentar
     *
     * @param string $kommentar
     *
     * @return Besitz
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

