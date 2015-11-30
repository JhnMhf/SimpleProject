<?php

namespace UR\DB\OldBundle\Entity;

/**
 * HerkunftDesKindes
 */
class HerkunftDesKindes
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
    private $geburtsort;

    /**
     * @var string
     */
    private $geboren;

    /**
     * @var string
     */
    private $taufort;

    /**
     * @var string
     */
    private $getauft;

    /**
     * @var string
     */
    private $belegt;

    /**
     * @var string
     */
    private $geburtsterritorium;

    /**
     * @var string
     */
    private $kommentar;

    /**
     * @var string
     */
    private $geburtsland;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return HerkunftDesKindes
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
     * @return HerkunftDesKindes
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
     * @return HerkunftDesKindes
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
     * @return HerkunftDesKindes
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
     * Set geburtsort
     *
     * @param string $geburtsort
     *
     * @return HerkunftDesKindes
     */
    public function setGeburtsort($geburtsort)
    {
        $this->geburtsort = $geburtsort;

        return $this;
    }

    /**
     * Get geburtsort
     *
     * @return string
     */
    public function getGeburtsort()
    {
        return $this->geburtsort;
    }

    /**
     * Set geboren
     *
     * @param string $geboren
     *
     * @return HerkunftDesKindes
     */
    public function setGeboren($geboren)
    {
        $this->geboren = $geboren;

        return $this;
    }

    /**
     * Get geboren
     *
     * @return string
     */
    public function getGeboren()
    {
        return $this->geboren;
    }

    /**
     * Set taufort
     *
     * @param string $taufort
     *
     * @return HerkunftDesKindes
     */
    public function setTaufort($taufort)
    {
        $this->taufort = $taufort;

        return $this;
    }

    /**
     * Get taufort
     *
     * @return string
     */
    public function getTaufort()
    {
        return $this->taufort;
    }

    /**
     * Set getauft
     *
     * @param string $getauft
     *
     * @return HerkunftDesKindes
     */
    public function setGetauft($getauft)
    {
        $this->getauft = $getauft;

        return $this;
    }

    /**
     * Get getauft
     *
     * @return string
     */
    public function getGetauft()
    {
        return $this->getauft;
    }

    /**
     * Set belegt
     *
     * @param string $belegt
     *
     * @return HerkunftDesKindes
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
     * Set geburtsterritorium
     *
     * @param string $geburtsterritorium
     *
     * @return HerkunftDesKindes
     */
    public function setGeburtsterritorium($geburtsterritorium)
    {
        $this->geburtsterritorium = $geburtsterritorium;

        return $this;
    }

    /**
     * Get geburtsterritorium
     *
     * @return string
     */
    public function getGeburtsterritorium()
    {
        return $this->geburtsterritorium;
    }

    /**
     * Set kommentar
     *
     * @param string $kommentar
     *
     * @return HerkunftDesKindes
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
     * Set geburtsland
     *
     * @param string $geburtsland
     *
     * @return HerkunftDesKindes
     */
    public function setGeburtsland($geburtsland)
    {
        $this->geburtsland = $geburtsland;

        return $this;
    }

    /**
     * Get geburtsland
     *
     * @return string
     */
    public function getGeburtsland()
    {
        return $this->geburtsland;
    }
}

