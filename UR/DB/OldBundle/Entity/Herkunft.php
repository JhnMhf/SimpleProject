<?php

namespace UR\DB\OldBundle\Entity;

/**
 * Herkunft
 */
class Herkunft
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
    private $geburtsort;

    /**
     * @var string
     */
    private $geboren;

    /**
     * @var string
     */
    private $geburtsterritorium;

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
    private $herkunftsort;

    /**
     * @var string
     */
    private $geburtsland;

    /**
     * @var string
     */
    private $herkunftsterritorium;

    /**
     * @var string
     */
    private $kommentar;

    /**
     * @var string
     */
    private $herkunftsland;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return Herkunft
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
     * @return Herkunft
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
     * @return Herkunft
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
     * @return Herkunft
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
     * Set geburtsterritorium
     *
     * @param string $geburtsterritorium
     *
     * @return Herkunft
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
     * Set taufort
     *
     * @param string $taufort
     *
     * @return Herkunft
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
     * @return Herkunft
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
     * Set herkunftsort
     *
     * @param string $herkunftsort
     *
     * @return Herkunft
     */
    public function setHerkunftsort($herkunftsort)
    {
        $this->herkunftsort = $herkunftsort;

        return $this;
    }

    /**
     * Get herkunftsort
     *
     * @return string
     */
    public function getHerkunftsort()
    {
        return $this->herkunftsort;
    }

    /**
     * Set geburtsland
     *
     * @param string $geburtsland
     *
     * @return Herkunft
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

    /**
     * Set herkunftsterritorium
     *
     * @param string $herkunftsterritorium
     *
     * @return Herkunft
     */
    public function setHerkunftsterritorium($herkunftsterritorium)
    {
        $this->herkunftsterritorium = $herkunftsterritorium;

        return $this;
    }

    /**
     * Get herkunftsterritorium
     *
     * @return string
     */
    public function getHerkunftsterritorium()
    {
        return $this->herkunftsterritorium;
    }

    /**
     * Set kommentar
     *
     * @param string $kommentar
     *
     * @return Herkunft
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
     * Set herkunftsland
     *
     * @param string $herkunftsland
     *
     * @return Herkunft
     */
    public function setHerkunftsland($herkunftsland)
    {
        $this->herkunftsland = $herkunftsland;

        return $this;
    }

    /**
     * Get herkunftsland
     *
     * @return string
     */
    public function getHerkunftsland()
    {
        return $this->herkunftsland;
    }
}

