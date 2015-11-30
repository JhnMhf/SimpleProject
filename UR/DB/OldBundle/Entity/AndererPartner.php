<?php

namespace UR\DB\OldBundle\Entity;

/**
 * AndererPartner
 */
class AndererPartner
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
    private $vorherNachher;

    /**
     * @var string
     */
    private $auflösung;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $stand;

    /**
     * @var string
     */
    private $gelöst;

    /**
     * @var string
     */
    private $verheiratet;

    /**
     * @var string
     */
    private $hochzeitstag;

    /**
     * @var string
     */
    private $bildungsabschluss;

    /**
     * @var string
     */
    private $vornamen;

    /**
     * @var string
     */
    private $gestorben;

    /**
     * @var string
     */
    private $beruf;

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
    private $herkunftsort;

    /**
     * @var string
     */
    private $partnerpartnerIdNr;

    /**
     * @var string
     */
    private $russVornamen;

    /**
     * @var string
     */
    private $geboren;

    /**
     * @var string
     */
    private $kommentar;

    /**
     * @var string
     */
    private $todesort;

    /**
     * @var string
     */
    private $aufgebot;

    /**
     * @var string
     */
    private $ehren;

    /**
     * @var string
     */
    private $besitz;

    /**
     * @var string
     */
    private $geburtsort;

    /**
     * @var string
     */
    private $friedhof;

    /**
     * @var string
     */
    private $herkunftsterritorium;

    /**
     * @var string
     */
    private $konfession;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return AndererPartner
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
     * @return AndererPartner
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
     * @return AndererPartner
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
     * Set vorherNachher
     *
     * @param string $vorherNachher
     *
     * @return AndererPartner
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
     * @return AndererPartner
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
     * Set name
     *
     * @param string $name
     *
     * @return AndererPartner
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
     * Set stand
     *
     * @param string $stand
     *
     * @return AndererPartner
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
     * Set gelöst
     *
     * @param string $gelöst
     *
     * @return AndererPartner
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
     * Set verheiratet
     *
     * @param string $verheiratet
     *
     * @return AndererPartner
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
     * Set hochzeitstag
     *
     * @param string $hochzeitstag
     *
     * @return AndererPartner
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
     * Set bildungsabschluss
     *
     * @param string $bildungsabschluss
     *
     * @return AndererPartner
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
     * Set vornamen
     *
     * @param string $vornamen
     *
     * @return AndererPartner
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
     * Set gestorben
     *
     * @param string $gestorben
     *
     * @return AndererPartner
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
     * Set beruf
     *
     * @param string $beruf
     *
     * @return AndererPartner
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
     * Set rang
     *
     * @param string $rang
     *
     * @return AndererPartner
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
     * @return AndererPartner
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
     * Set herkunftsort
     *
     * @param string $herkunftsort
     *
     * @return AndererPartner
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
     * Set partnerpartnerIdNr
     *
     * @param string $partnerpartnerIdNr
     *
     * @return AndererPartner
     */
    public function setPartnerpartnerIdNr($partnerpartnerIdNr)
    {
        $this->partnerpartnerIdNr = $partnerpartnerIdNr;

        return $this;
    }

    /**
     * Get partnerpartnerIdNr
     *
     * @return string
     */
    public function getPartnerpartnerIdNr()
    {
        return $this->partnerpartnerIdNr;
    }

    /**
     * Set russVornamen
     *
     * @param string $russVornamen
     *
     * @return AndererPartner
     */
    public function setRussVornamen($russVornamen)
    {
        $this->russVornamen = $russVornamen;

        return $this;
    }

    /**
     * Get russVornamen
     *
     * @return string
     */
    public function getRussVornamen()
    {
        return $this->russVornamen;
    }

    /**
     * Set geboren
     *
     * @param string $geboren
     *
     * @return AndererPartner
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
     * Set kommentar
     *
     * @param string $kommentar
     *
     * @return AndererPartner
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
     * Set todesort
     *
     * @param string $todesort
     *
     * @return AndererPartner
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
     * Set aufgebot
     *
     * @param string $aufgebot
     *
     * @return AndererPartner
     */
    public function setAufgebot($aufgebot)
    {
        $this->aufgebot = $aufgebot;

        return $this;
    }

    /**
     * Get aufgebot
     *
     * @return string
     */
    public function getAufgebot()
    {
        return $this->aufgebot;
    }

    /**
     * Set ehren
     *
     * @param string $ehren
     *
     * @return AndererPartner
     */
    public function setEhren($ehren)
    {
        $this->ehren = $ehren;

        return $this;
    }

    /**
     * Get ehren
     *
     * @return string
     */
    public function getEhren()
    {
        return $this->ehren;
    }

    /**
     * Set besitz
     *
     * @param string $besitz
     *
     * @return AndererPartner
     */
    public function setBesitz($besitz)
    {
        $this->besitz = $besitz;

        return $this;
    }

    /**
     * Get besitz
     *
     * @return string
     */
    public function getBesitz()
    {
        return $this->besitz;
    }

    /**
     * Set geburtsort
     *
     * @param string $geburtsort
     *
     * @return AndererPartner
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
     * Set friedhof
     *
     * @param string $friedhof
     *
     * @return AndererPartner
     */
    public function setFriedhof($friedhof)
    {
        $this->friedhof = $friedhof;

        return $this;
    }

    /**
     * Get friedhof
     *
     * @return string
     */
    public function getFriedhof()
    {
        return $this->friedhof;
    }

    /**
     * Set herkunftsterritorium
     *
     * @param string $herkunftsterritorium
     *
     * @return AndererPartner
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
     * Set konfession
     *
     * @param string $konfession
     *
     * @return AndererPartner
     */
    public function setKonfession($konfession)
    {
        $this->konfession = $konfession;

        return $this;
    }

    /**
     * Get konfession
     *
     * @return string
     */
    public function getKonfession()
    {
        return $this->konfession;
    }
}

