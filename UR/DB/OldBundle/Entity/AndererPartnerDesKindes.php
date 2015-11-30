<?php

namespace UR\DB\OldBundle\Entity;

/**
 * AndererPartnerDesKindes
 */
class AndererPartnerDesKindes
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
     * @var boolean
     */
    private $order4;

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
    private $vorherNachher;

    /**
     * @var string
     */
    private $auflösung;

    /**
     * @var string
     */
    private $vornamen;

    /**
     * @var string
     */
    private $rang;

    /**
     * @var string
     */
    private $hochzeitsort;

    /**
     * @var string
     */
    private $hochzeitstag;

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
    private $todesort;

    /**
     * @var string
     */
    private $gestorben;

    /**
     * @var string
     */
    private $verheiratet;

    /**
     * @var string
     */
    private $kommentar;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return AndererPartnerDesKindes
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
     * @return AndererPartnerDesKindes
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
     * @return AndererPartnerDesKindes
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
     * Set order4
     *
     * @param boolean $order4
     *
     * @return AndererPartnerDesKindes
     */
    public function setOrder4($order4)
    {
        $this->order4 = $order4;

        return $this;
    }

    /**
     * Get order4
     *
     * @return boolean
     */
    public function getOrder4()
    {
        return $this->order4;
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return AndererPartnerDesKindes
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
     * @return AndererPartnerDesKindes
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
     * Set vorherNachher
     *
     * @param string $vorherNachher
     *
     * @return AndererPartnerDesKindes
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
     * Set auflösung
     *
     * @param string $auflösung
     *
     * @return AndererPartnerDesKindes
     */
    public function setAuflösung($auflösung)
    {
        $this->auflösung = $auflösung;

        return $this;
    }

    /**
     * Get auflösung
     *
     * @return string
     */
    public function getAuflösung()
    {
        return $this->auflösung;
    }

    /**
     * Set vornamen
     *
     * @param string $vornamen
     *
     * @return AndererPartnerDesKindes
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
     * Set rang
     *
     * @param string $rang
     *
     * @return AndererPartnerDesKindes
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
     * Set hochzeitsort
     *
     * @param string $hochzeitsort
     *
     * @return AndererPartnerDesKindes
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
     * Set hochzeitstag
     *
     * @param string $hochzeitstag
     *
     * @return AndererPartnerDesKindes
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
     * Set geburtsort
     *
     * @param string $geburtsort
     *
     * @return AndererPartnerDesKindes
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
     * @return AndererPartnerDesKindes
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
     * Set todesort
     *
     * @param string $todesort
     *
     * @return AndererPartnerDesKindes
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

    /**
     * Set gestorben
     *
     * @param string $gestorben
     *
     * @return AndererPartnerDesKindes
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
     * Set verheiratet
     *
     * @param string $verheiratet
     *
     * @return AndererPartnerDesKindes
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
     * Set kommentar
     *
     * @param string $kommentar
     *
     * @return AndererPartnerDesKindes
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

