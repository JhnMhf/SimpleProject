<?php

namespace UR\DB\OldBundle\Entity;

/**
 * Ausbildung
 */
class Ausbildung
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
    private $ausbildung;

    /**
     * @var string
     */
    private $ort;

    /**
     * @var string
     */
    private $bildungsabschluss;

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
    private $bildungsabschlussdatum;

    /**
     * @var string
     */
    private $belegt;

    /**
     * @var string
     */
    private $bildungsabschlussort;

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
     * @return Ausbildung
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
     * @return Ausbildung
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
     * @return Ausbildung
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
     * Set ausbildung
     *
     * @param string $ausbildung
     *
     * @return Ausbildung
     */
    public function setAusbildung($ausbildung)
    {
        $this->ausbildung = $ausbildung;

        return $this;
    }

    /**
     * Get ausbildung
     *
     * @return string
     */
    public function getAusbildung()
    {
        return $this->ausbildung;
    }

    /**
     * Set ort
     *
     * @param string $ort
     *
     * @return Ausbildung
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
     * Set bildungsabschluss
     *
     * @param string $bildungsabschluss
     *
     * @return Ausbildung
     */
    public function setBildungsabschluss($bildungsabschluss)
    {
        $this->bildungsabschluss = $bildungsabschluss;

        return $this;
    }

    /**
     * Get bildungsabschluss
     *
     * @return string
     */
    public function getBildungsabschluss()
    {
        return $this->bildungsabschluss;
    }

    /**
     * Set vonAb
     *
     * @param string $vonAb
     *
     * @return Ausbildung
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
     * @return Ausbildung
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
     * Set bildungsabschlussdatum
     *
     * @param string $bildungsabschlussdatum
     *
     * @return Ausbildung
     */
    public function setBildungsabschlussdatum($bildungsabschlussdatum)
    {
        $this->bildungsabschlussdatum = $bildungsabschlussdatum;

        return $this;
    }

    /**
     * Get bildungsabschlussdatum
     *
     * @return string
     */
    public function getBildungsabschlussdatum()
    {
        return $this->bildungsabschlussdatum;
    }

    /**
     * Set belegt
     *
     * @param string $belegt
     *
     * @return Ausbildung
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
     * Set bildungsabschlussort
     *
     * @param string $bildungsabschlussort
     *
     * @return Ausbildung
     */
    public function setBildungsabschlussort($bildungsabschlussort)
    {
        $this->bildungsabschlussort = $bildungsabschlussort;

        return $this;
    }

    /**
     * Get bildungsabschlussort
     *
     * @return string
     */
    public function getBildungsabschlussort()
    {
        return $this->bildungsabschlussort;
    }

    /**
     * Set territorium
     *
     * @param string $territorium
     *
     * @return Ausbildung
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
     * @return Ausbildung
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

