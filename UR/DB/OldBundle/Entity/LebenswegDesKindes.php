<?php

namespace UR\DB\OldBundle\Entity;

/**
 * LebenswegDesKindes
 */
class LebenswegDesKindes
{
    /**
     * @var boolean
     */
    private $order;

    /**
     * @var boolean
     */
    private $order2;

    /**
     * @var boolean
     */
    private $order3;

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
    private $beruf;

    /**
     * @var string
     */
    private $ort;

    /**
     * @var string
     */
    private $belegt;

    /**
     * @var string
     */
    private $bis;

    /**
     * @var string
     */
    private $vonAb;

    /**
     * @var string
     */
    private $territorium;

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
     * @return LebenswegDesKindes
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
     * Set order2
     *
     * @param boolean $order2
     *
     * @return LebenswegDesKindes
     */
    public function setOrder2($order2)
    {
        $this->order2 = $order2;

        return $this;
    }

    /**
     * Get order2
     *
     * @return boolean
     */
    public function getOrder2()
    {
        return $this->order2;
    }

    /**
     * Set order3
     *
     * @param boolean $order3
     *
     * @return LebenswegDesKindes
     */
    public function setOrder3($order3)
    {
        $this->order3 = $order3;

        return $this;
    }

    /**
     * Get order3
     *
     * @return boolean
     */
    public function getOrder3()
    {
        return $this->order3;
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return LebenswegDesKindes
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
     * @return LebenswegDesKindes
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
     * Set beruf
     *
     * @param string $beruf
     *
     * @return LebenswegDesKindes
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
     * @return LebenswegDesKindes
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
     * Set belegt
     *
     * @param string $belegt
     *
     * @return LebenswegDesKindes
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
     * Set bis
     *
     * @param string $bis
     *
     * @return LebenswegDesKindes
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
     * Set vonAb
     *
     * @param string $vonAb
     *
     * @return LebenswegDesKindes
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
     * Set territorium
     *
     * @param string $territorium
     *
     * @return LebenswegDesKindes
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
     * Set land
     *
     * @param string $land
     *
     * @return LebenswegDesKindes
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
     * @return LebenswegDesKindes
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

