<?php

namespace UR\DB\OldBundle\Entity;

/**
 * PartnerDerMutter
 */
class PartnerDerMutter
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
    private $auflösung;

    /**
     * @var string
     */
    private $vorherNachher;

    /**
     * @var string
     */
    private $verheiratet;

    /**
     * @var string
     */
    private $gelöst;

    /**
     * @var string
     */
    private $hochzeitstag;

    /**
     * @var string
     */
    private $rang;

    /**
     * @var string
     */
    private $belegt;

    /**
     * @var string
     */
    private $vornamen;

    /**
     * @var string
     */
    private $hochzeitsort;

    /**
     * @var string
     */
    private $beruf;

    /**
     * @var string
     */
    private $kommentar;

    /**
     * @var string
     */
    private $stand;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return PartnerDerMutter
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
     * @return PartnerDerMutter
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
     * @return PartnerDerMutter
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
     * @return PartnerDerMutter
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
     * Set auflösung
     *
     * @param string $auflösung
     *
     * @return PartnerDerMutter
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
     * Set vorherNachher
     *
     * @param string $vorherNachher
     *
     * @return PartnerDerMutter
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
     * Set verheiratet
     *
     * @param string $verheiratet
     *
     * @return PartnerDerMutter
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
     * Set gelöst
     *
     * @param string $gelöst
     *
     * @return PartnerDerMutter
     */
    public function setGelöst($gelöst)
    {
        $this->gelöst = $gelöst;

        return $this;
    }

    /**
     * Get gelöst
     *
     * @return string
     */
    public function getGelöst()
    {
        return $this->gelöst;
    }

    /**
     * Set hochzeitstag
     *
     * @param string $hochzeitstag
     *
     * @return PartnerDerMutter
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
     * Set rang
     *
     * @param string $rang
     *
     * @return PartnerDerMutter
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
     * Set belegt
     *
     * @param string $belegt
     *
     * @return PartnerDerMutter
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
     * Set vornamen
     *
     * @param string $vornamen
     *
     * @return PartnerDerMutter
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
     * Set hochzeitsort
     *
     * @param string $hochzeitsort
     *
     * @return PartnerDerMutter
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
     * Set beruf
     *
     * @param string $beruf
     *
     * @return PartnerDerMutter
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
     * Set kommentar
     *
     * @param string $kommentar
     *
     * @return PartnerDerMutter
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
     * @return PartnerDerMutter
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
}

