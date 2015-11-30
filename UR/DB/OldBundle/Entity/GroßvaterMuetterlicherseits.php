<?php

namespace UR\DB\OldBundle\Entity;

/**
 * GroßvaterMuetterlicherseits
 */
class GroßvaterMuetterlicherseits
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
    private $name;

    /**
     * @var string
     */
    private $beruf;

    /**
     * @var string
     */
    private $wohnort;

    /**
     * @var string
     */
    private $vornamen;

    /**
     * @var string
     */
    private $mütterlGroßvaterIdNr;

    /**
     * @var string
     */
    private $nation;

    /**
     * @var string
     */
    private $gestorben;

    /**
     * @var string
     */
    private $kommentar;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return GroßvaterMuetterlicherseits
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
     * @return GroßvaterMuetterlicherseits
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
     * @return GroßvaterMuetterlicherseits
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
     * Set name
     *
     * @param string $name
     *
     * @return GroßvaterMuetterlicherseits
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
     * Set beruf
     *
     * @param string $beruf
     *
     * @return GroßvaterMuetterlicherseits
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
     * Set wohnort
     *
     * @param string $wohnort
     *
     * @return GroßvaterMuetterlicherseits
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
     * Set vornamen
     *
     * @param string $vornamen
     *
     * @return GroßvaterMuetterlicherseits
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
     * Set mütterlGroßvaterIdNr
     *
     * @param string $mütterlGroßvaterIdNr
     *
     * @return GroßvaterMuetterlicherseits
     */
    public function setMütterlGroßvaterIdNr($mütterlGroßvaterIdNr)
    {
        $this->mütterlGroßvaterIdNr = $mütterlGroßvaterIdNr;

        return $this;
    }

    /**
     * Get mütterlGroßvaterIdNr
     *
     * @return string
     */
    public function getMütterlGroßvaterIdNr()
    {
        return $this->mütterlGroßvaterIdNr;
    }

    /**
     * Set nation
     *
     * @param string $nation
     *
     * @return GroßvaterMuetterlicherseits
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
     * Set gestorben
     *
     * @param string $gestorben
     *
     * @return GroßvaterMuetterlicherseits
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
     * @return GroßvaterMuetterlicherseits
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

