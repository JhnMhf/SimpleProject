<?php

namespace UR\DB\OldBundle\Entity;

/**
 * PartnerinDesVaters
 */
class PartnerinDesVaters
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
    private $verheiratet;

    /**
     * @var string
     */
    private $vornamen;

    /**
     * @var string
     */
    private $hochzeitstag;

    /**
     * @var string
     */
    private $gestorben;

    /**
     * @var string
     */
    private $hochzeitsort;

    /**
     * @var string
     */
    private $geboren;

    /**
     * @var string
     */
    private $stand;

    /**
     * @var string
     */
    private $vorherNachher;

    /**
     * @var string
     */
    private $kommentar;

    /**
     * @var string
     */
    private $beruf;

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
    private $geburtsort;

    /**
     * @var string
     */
    private $todesort;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return PartnerinDesVaters
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
     * @return PartnerinDesVaters
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
     * @return PartnerinDesVaters
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
     * @return PartnerinDesVaters
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
     * Set verheiratet
     *
     * @param string $verheiratet
     *
     * @return PartnerinDesVaters
     */
    public function setVerheiratet($verheiratet)
    {
        $this->verheiratet = $verheiratet;

        return $this;
    }

    /**
     * Get verheiratet
     *
     * @return string
     */
    public function getVerheiratet()
    {
        return $this->verheiratet;
    }

    /**
     * Set vornamen
     *
     * @param string $vornamen
     *
     * @return PartnerinDesVaters
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
     * Set hochzeitstag
     *
     * @param string $hochzeitstag
     *
     * @return PartnerinDesVaters
     */
    public function setHochzeitstag($hochzeitstag)
    {
        $this->hochzeitstag = $hochzeitstag;

        return $this;
    }

    /**
     * Get hochzeitstag
     *
     * @return string
     */
    public function getHochzeitstag()
    {
        return $this->hochzeitstag;
    }

    /**
     * Set gestorben
     *
     * @param string $gestorben
     *
     * @return PartnerinDesVaters
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
     * Set hochzeitsort
     *
     * @param string $hochzeitsort
     *
     * @return PartnerinDesVaters
     */
    public function setHochzeitsort($hochzeitsort)
    {
        $this->hochzeitsort = $hochzeitsort;

        return $this;
    }

    /**
     * Get hochzeitsort
     *
     * @return string
     */
    public function getHochzeitsort()
    {
        return $this->hochzeitsort;
    }

    /**
     * Set geboren
     *
     * @param string $geboren
     *
     * @return PartnerinDesVaters
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
     * Set stand
     *
     * @param string $stand
     *
     * @return PartnerinDesVaters
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
     * Set vorherNachher
     *
     * @param string $vorherNachher
     *
     * @return PartnerinDesVaters
     */
    public function setVorherNachher($vorherNachher)
    {
        $this->vorherNachher = $vorherNachher;

        return $this;
    }

    /**
     * Get vorherNachher
     *
     * @return string
     */
    public function getVorherNachher()
    {
        return $this->vorherNachher;
    }

    /**
     * Set kommentar
     *
     * @param string $kommentar
     *
     * @return PartnerinDesVaters
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
     * Set beruf
     *
     * @param string $beruf
     *
     * @return PartnerinDesVaters
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
     * Set taufort
     *
     * @param string $taufort
     *
     * @return PartnerinDesVaters
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
     * @return PartnerinDesVaters
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
     * Set geburtsort
     *
     * @param string $geburtsort
     *
     * @return PartnerinDesVaters
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
     * Set todesort
     *
     * @param string $todesort
     *
     * @return PartnerinDesVaters
     */
    public function setTodesort($todesort)
    {
        $this->todesort = $todesort;

        return $this;
    }

    /**
     * Get todesort
     *
     * @return string
     */
    public function getTodesort()
    {
        return $this->todesort;
    }
}

