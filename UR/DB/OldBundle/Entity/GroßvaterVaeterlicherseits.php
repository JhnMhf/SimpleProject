<?php

namespace UR\DB\OldBundle\Entity;

/**
 * GroßvaterVaeterlicherseits
 */
class GroßvaterVaeterlicherseits
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
    private $vornamen;

    /**
     * @var string
     */
    private $vätGroßvaterIdNr;

    /**
     * @var string
     */
    private $beruf;

    /**
     * @var string
     */
    private $geburtsort;

    /**
     * @var string
     */
    private $geburtsland;

    /**
     * @var string
     */
    private $geboren;

    /**
     * @var string
     */
    private $rang;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $wohnort;

    /**
     * @var string
     */
    private $gestorben;

    /**
     * @var string
     */
    private $kommentar;

    /**
     * @var string
     */
    private $stand;

    /**
     * @var string
     */
    private $nation;

    /**
     * @var string
     */
    private $geburtsterritorium;

    /**
     * @var string
     */
    private $wohnterritorium;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return GroßvaterVaeterlicherseits
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
     * @return GroßvaterVaeterlicherseits
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
     * @return GroßvaterVaeterlicherseits
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
     * Set vornamen
     *
     * @param string $vornamen
     *
     * @return GroßvaterVaeterlicherseits
     */
    public function setVornamen($vornamen)
    {
        $this->vornamen = $vornamen;

        return $this;
    }

    /**
     * Get vornamen
     *
     * @return string
     */
    public function getVornamen()
    {
        return $this->vornamen;
    }

    /**
     * Set vätGroßvaterIdNr
     *
     * @param string $vätGroßvaterIdNr
     *
     * @return GroßvaterVaeterlicherseits
     */
    public function setVätGroßvaterIdNr($vätGroßvaterIdNr)
    {
        $this->vätGroßvaterIdNr = $vätGroßvaterIdNr;

        return $this;
    }

    /**
     * Get vätGroßvaterIdNr
     *
     * @return string
     */
    public function getVätGroßvaterIdNr()
    {
        return $this->vätGroßvaterIdNr;
    }

    /**
     * Set beruf
     *
     * @param string $beruf
     *
     * @return GroßvaterVaeterlicherseits
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
     * Set geburtsort
     *
     * @param string $geburtsort
     *
     * @return GroßvaterVaeterlicherseits
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
     * Set geburtsland
     *
     * @param string $geburtsland
     *
     * @return GroßvaterVaeterlicherseits
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
     * Set geboren
     *
     * @param string $geboren
     *
     * @return GroßvaterVaeterlicherseits
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
     * Set rang
     *
     * @param string $rang
     *
     * @return GroßvaterVaeterlicherseits
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
     * Set name
     *
     * @param string $name
     *
     * @return GroßvaterVaeterlicherseits
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set wohnort
     *
     * @param string $wohnort
     *
     * @return GroßvaterVaeterlicherseits
     */
    public function setWohnort($wohnort)
    {
        $this->wohnort = $wohnort;

        return $this;
    }

    /**
     * Get wohnort
     *
     * @return string
     */
    public function getWohnort()
    {
        return $this->wohnort;
    }

    /**
     * Set gestorben
     *
     * @param string $gestorben
     *
     * @return GroßvaterVaeterlicherseits
     */
    public function setGestorben($gestorben)
    {
        $this->gestorben = $gestorben;

        return $this;
    }

    /**
     * Get gestorben
     *
     * @return string
     */
    public function getGestorben()
    {
        return $this->gestorben;
    }

    /**
     * Set kommentar
     *
     * @param string $kommentar
     *
     * @return GroßvaterVaeterlicherseits
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
     * Set stand
     *
     * @param string $stand
     *
     * @return GroßvaterVaeterlicherseits
     */
    public function setStand($stand)
    {
        $this->stand = $stand;

        return $this;
    }

    /**
     * Get stand
     *
     * @return string
     */
    public function getStand()
    {
        return $this->stand;
    }

    /**
     * Set nation
     *
     * @param string $nation
     *
     * @return GroßvaterVaeterlicherseits
     */
    public function setNation($nation)
    {
        $this->nation = $nation;

        return $this;
    }

    /**
     * Get nation
     *
     * @return string
     */
    public function getNation()
    {
        return $this->nation;
    }

    /**
     * Set geburtsterritorium
     *
     * @param string $geburtsterritorium
     *
     * @return GroßvaterVaeterlicherseits
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
     * Set wohnterritorium
     *
     * @param string $wohnterritorium
     *
     * @return GroßvaterVaeterlicherseits
     */
    public function setWohnterritorium($wohnterritorium)
    {
        $this->wohnterritorium = $wohnterritorium;

        return $this;
    }

    /**
     * Get wohnterritorium
     *
     * @return string
     */
    public function getWohnterritorium()
    {
        return $this->wohnterritorium;
    }
}

