<?php

namespace UR\DB\OldBundle\Entity;

/**
 * AusbildungDesKindes
 */
class AusbildungDesKindes
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
    private $land;

    /**
     * @var string
     */
    private $ausbildung;

    /**
     * @var string
     */
    private $bildungsabschluss;

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
    private $bildungsabschlussort;

    /**
     * @var string
     */
    private $kommentar;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return AusbildungDesKindes
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
     * @return AusbildungDesKindes
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
     * @return AusbildungDesKindes
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
     * @return AusbildungDesKindes
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
     * @return AusbildungDesKindes
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
     * @return AusbildungDesKindes
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
     * Set bildungsabschluss
     *
     * @param string $bildungsabschluss
     *
     * @return AusbildungDesKindes
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
     * Set ort
     *
     * @param string $ort
     *
     * @return AusbildungDesKindes
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
     * @return AusbildungDesKindes
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
     * Set vonAb
     *
     * @param string $vonAb
     *
     * @return AusbildungDesKindes
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
     * @return AusbildungDesKindes
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
     * @return AusbildungDesKindes
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
     * Set bildungsabschlussort
     *
     * @param string $bildungsabschlussort
     *
     * @return AusbildungDesKindes
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
     * Set kommentar
     *
     * @param string $kommentar
     *
     * @return AusbildungDesKindes
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

