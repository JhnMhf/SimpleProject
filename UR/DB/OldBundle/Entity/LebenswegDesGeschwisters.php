<?php

namespace UR\DB\OldBundle\Entity;

/**
 * LebenswegDesGeschwisters
 */
class LebenswegDesGeschwisters
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
    private $belegt;

    /**
     * @var string
     */
    private $ort;

    /**
     * @var string
     */
    private $beruf;

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
     * @return LebenswegDesGeschwisters
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
     * @return LebenswegDesGeschwisters
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
     * Set id
     *
     * @param integer $id
     *
     * @return LebenswegDesGeschwisters
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
     * @return LebenswegDesGeschwisters
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
     * Set belegt
     *
     * @param string $belegt
     *
     * @return LebenswegDesGeschwisters
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
     * @return LebenswegDesGeschwisters
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
     * Set beruf
     *
     * @param string $beruf
     *
     * @return LebenswegDesGeschwisters
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
     * Set vonAb
     *
     * @param string $vonAb
     *
     * @return LebenswegDesGeschwisters
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
     * @return LebenswegDesGeschwisters
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
     * @return LebenswegDesGeschwisters
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
     * @return LebenswegDesGeschwisters
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

