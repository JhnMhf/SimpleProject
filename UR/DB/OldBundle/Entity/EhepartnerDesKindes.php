<?php

namespace UR\DB\OldBundle\Entity;

/**
 * EhepartnerDesKindes
 */
class EhepartnerDesKindes
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
    private $name;

    /**
     * @var string
     */
    private $hochzeitstag;

    /**
     * @var string
     */
    private $russVornamen;

    /**
     * @var string
     */
    private $hochzeitsort;

    /**
     * @var string
     */
    private $vornamen;

    /**
     * @var string
     */
    private $herkunftsort;

    /**
     * @var string
     */
    private $verheiratet;

    /**
     * @var string
     */
    private $beruf;

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
    private $auflösung;

    /**
     * @var string
     */
    private $gelöst;

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
    private $aufgebot;

    /**
     * @var string
     */
    private $rang;

    /**
     * @var string
     */
    private $stand;

    /**
     * @var string
     */
    private $geburtsort;

    /**
     * @var string
     */
    private $nation;

    /**
     * @var string
     */
    private $bildungsabschluss;

    /**
     * @var string
     */
    private $friedhof;

    /**
     * @var string
     */
    private $kindespartnerIdNr;

    /**
     * @var string
     */
    private $herkunftsterritorium;

    /**
     * @var string
     */
    private $rufnamen;

    /**
     * @var string
     */
    private $geburtsterritorium;

    /**
     * @var string
     */
    private $begräbnisort;

    /**
     * @var string
     */
    private $besitz;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return EhepartnerDesKindes
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
     * @return EhepartnerDesKindes
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
     * @return EhepartnerDesKindes
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
     * @return EhepartnerDesKindes
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
     * @return EhepartnerDesKindes
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
     * Set hochzeitstag
     *
     * @param string $hochzeitstag
     *
     * @return EhepartnerDesKindes
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
     * Set russVornamen
     *
     * @param string $russVornamen
     *
     * @return EhepartnerDesKindes
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
     * Set hochzeitsort
     *
     * @param string $hochzeitsort
     *
     * @return EhepartnerDesKindes
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
     * Set vornamen
     *
     * @param string $vornamen
     *
     * @return EhepartnerDesKindes
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
     * Set herkunftsort
     *
     * @param string $herkunftsort
     *
     * @return EhepartnerDesKindes
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
     * Set verheiratet
     *
     * @param string $verheiratet
     *
     * @return EhepartnerDesKindes
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
     * Set beruf
     *
     * @param string $beruf
     *
     * @return EhepartnerDesKindes
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
     * Set geboren
     *
     * @param string $geboren
     *
     * @return EhepartnerDesKindes
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
     * @return EhepartnerDesKindes
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
     * Set auflösung
     *
     * @param string $auflösung
     *
     * @return EhepartnerDesKindes
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
     * Set gelöst
     *
     * @param string $gelöst
     *
     * @return EhepartnerDesKindes
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
     * Set todesort
     *
     * @param string $todesort
     *
     * @return EhepartnerDesKindes
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
     * @return EhepartnerDesKindes
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
     * Set aufgebot
     *
     * @param string $aufgebot
     *
     * @return EhepartnerDesKindes
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
     * Set rang
     *
     * @param string $rang
     *
     * @return EhepartnerDesKindes
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
     * Set stand
     *
     * @param string $stand
     *
     * @return EhepartnerDesKindes
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
     * Set geburtsort
     *
     * @param string $geburtsort
     *
     * @return EhepartnerDesKindes
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
     * Set nation
     *
     * @param string $nation
     *
     * @return EhepartnerDesKindes
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
     * Set bildungsabschluss
     *
     * @param string $bildungsabschluss
     *
     * @return EhepartnerDesKindes
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
     * Set friedhof
     *
     * @param string $friedhof
     *
     * @return EhepartnerDesKindes
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
     * Set kindespartnerIdNr
     *
     * @param string $kindespartnerIdNr
     *
     * @return EhepartnerDesKindes
     */
    public function setKindespartnerIdNr($kindespartnerIdNr)
    {
        $this->kindespartnerIdNr = $kindespartnerIdNr;

        return $this;
    }

    /**
     * Get kindespartnerIdNr
     *
     * @return string
     */
    public function getKindespartnerIdNr()
    {
        return $this->kindespartnerIdNr;
    }

    /**
     * Set herkunftsterritorium
     *
     * @param string $herkunftsterritorium
     *
     * @return EhepartnerDesKindes
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
     * Set rufnamen
     *
     * @param string $rufnamen
     *
     * @return EhepartnerDesKindes
     */
    public function setRufnamen($rufnamen)
    {
        $this->rufnamen = $rufnamen;

        return $this;
    }

    /**
     * Get rufnamen
     *
     * @return string
     */
    public function getRufnamen()
    {
        return $this->rufnamen;
    }

    /**
     * Set geburtsterritorium
     *
     * @param string $geburtsterritorium
     *
     * @return EhepartnerDesKindes
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
     * Set begräbnisort
     *
     * @param string $begräbnisort
     *
     * @return EhepartnerDesKindes
     */
    public function setBegräbnisort($begräbnisort)
    {
        $this->begräbnisort = $begräbnisort;

        return $this;
    }

    /**
     * Get begräbnisort
     *
     * @return string
     */
    public function getBegräbnisort()
    {
        return $this->begräbnisort;
    }

    /**
     * Set besitz
     *
     * @param string $besitz
     *
     * @return EhepartnerDesKindes
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
}

