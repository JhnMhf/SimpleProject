<?php

namespace UR\DB\OldBundle\Entity;

/**
 * Ehepartner
 */
class Ehepartner
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
    private $hochzeitstag;

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
    private $name;

    /**
     * @var string
     */
    private $geboren;

    /**
     * @var string
     */
    private $gestorben;

    /**
     * @var string
     */
    private $todesort;

    /**
     * @var string
     */
    private $rufnamen;

    /**
     * @var string
     */
    private $geburtsort;

    /**
     * @var string
     */
    private $kommentar;

    /**
     * @var string
     */
    private $russVornamen;

    /**
     * @var string
     */
    private $stand;

    /**
     * @var string
     */
    private $geburtsterritorium;

    /**
     * @var string
     */
    private $friedhof;

    /**
     * @var string
     */
    private $begräbnisort;

    /**
     * @var string
     */
    private $getauft;

    /**
     * @var string
     */
    private $taufort;

    /**
     * @var string
     */
    private $todesterritorium;

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
    private $verheiratet;

    /**
     * @var string
     */
    private $konfession;

    /**
     * @var string
     */
    private $begraben;

    /**
     * @var string
     */
    private $rang;

    /**
     * @var string
     */
    private $aufgebot;

    /**
     * @var string
     */
    private $beruf;

    /**
     * @var string
     */
    private $nation;

    /**
     * @var string
     */
    private $hochzeitsterritorium;

    /**
     * @var string
     */
    private $herkunftsort;

    /**
     * @var string
     */
    private $besitz;

    /**
     * @var string
     */
    private $ehepartnerIdNr;

    /**
     * @var string
     */
    private $herkunftsland;

    /**
     * @var string
     */
    private $herkunftsterritorium;

    /**
     * @var string
     */
    private $todesland;

    /**
     * @var string
     */
    private $bildungsabschluss;

    /**
     * @var string
     */
    private $geburtsland;

    /**
     * @var string
     */
    private $ehren;

    /**
     * @var string
     */
    private $vorherNachher;

    /**
     * @var string
     */
    private $todesursache;

    /**
     * @var string
     */
    private $partnerpartnerIdNr;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return Ehepartner
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
     * @return Ehepartner
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
     * Set hochzeitstag
     *
     * @param string $hochzeitstag
     *
     * @return Ehepartner
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
     * Set hochzeitsort
     *
     * @param string $hochzeitsort
     *
     * @return Ehepartner
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
     * @return Ehepartner
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
     * Set name
     *
     * @param string $name
     *
     * @return Ehepartner
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
     * Set geboren
     *
     * @param string $geboren
     *
     * @return Ehepartner
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
     * Set gestorben
     *
     * @param string $gestorben
     *
     * @return Ehepartner
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
     * Set todesort
     *
     * @param string $todesort
     *
     * @return Ehepartner
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
     * Set rufnamen
     *
     * @param string $rufnamen
     *
     * @return Ehepartner
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
     * Set geburtsort
     *
     * @param string $geburtsort
     *
     * @return Ehepartner
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
     * Set kommentar
     *
     * @param string $kommentar
     *
     * @return Ehepartner
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
     * Set russVornamen
     *
     * @param string $russVornamen
     *
     * @return Ehepartner
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
     * Set stand
     *
     * @param string $stand
     *
     * @return Ehepartner
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
     * Set geburtsterritorium
     *
     * @param string $geburtsterritorium
     *
     * @return Ehepartner
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
     * Set friedhof
     *
     * @param string $friedhof
     *
     * @return Ehepartner
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
     * Set begräbnisort
     *
     * @param string $begräbnisort
     *
     * @return Ehepartner
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
     * Set getauft
     *
     * @param string $getauft
     *
     * @return Ehepartner
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
     * Set taufort
     *
     * @param string $taufort
     *
     * @return Ehepartner
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
     * Set todesterritorium
     *
     * @param string $todesterritorium
     *
     * @return Ehepartner
     */
    public function setTodesterritorium($todesterritorium)
    {
        $this->todesterritorium = $todesterritorium;

        return $this;
    }

    /**
     * Get todesterritorium
     *
     * @return string
     */
    public function getTodesterritorium()
    {
        return $this->todesterritorium;
    }

    /**
     * Set auflösung
     *
     * @param string $auflösung
     *
     * @return Ehepartner
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
     * @return Ehepartner
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
     * @return Ehepartner
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
     * Set konfession
     *
     * @param string $konfession
     *
     * @return Ehepartner
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

    /**
     * Set begraben
     *
     * @param string $begraben
     *
     * @return Ehepartner
     */
    public function setBegraben($begraben)
    {
        $this->begraben = $begraben;

        return $this;
    }

    /**
     * Get begraben
     *
     * @return string
     */
    public function getBegraben()
    {
        return $this->begraben;
    }

    /**
     * Set rang
     *
     * @param string $rang
     *
     * @return Ehepartner
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
     * Set aufgebot
     *
     * @param string $aufgebot
     *
     * @return Ehepartner
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
     * Set beruf
     *
     * @param string $beruf
     *
     * @return Ehepartner
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
     * Set nation
     *
     * @param string $nation
     *
     * @return Ehepartner
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
     * Set hochzeitsterritorium
     *
     * @param string $hochzeitsterritorium
     *
     * @return Ehepartner
     */
    public function setHochzeitsterritorium($hochzeitsterritorium)
    {
        $this->hochzeitsterritorium = $hochzeitsterritorium;

        return $this;
    }

    /**
     * Get hochzeitsterritorium
     *
     * @return string
     */
    public function getHochzeitsterritorium()
    {
        return $this->hochzeitsterritorium;
    }

    /**
     * Set herkunftsort
     *
     * @param string $herkunftsort
     *
     * @return Ehepartner
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
     * Set besitz
     *
     * @param string $besitz
     *
     * @return Ehepartner
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
     * Set ehepartnerIdNr
     *
     * @param string $ehepartnerIdNr
     *
     * @return Ehepartner
     */
    public function setEhepartnerIdNr($ehepartnerIdNr)
    {
        $this->ehepartnerIdNr = $ehepartnerIdNr;

        return $this;
    }

    /**
     * Get ehepartnerIdNr
     *
     * @return string
     */
    public function getEhepartnerIdNr()
    {
        return $this->ehepartnerIdNr;
    }

    /**
     * Set herkunftsland
     *
     * @param string $herkunftsland
     *
     * @return Ehepartner
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

    /**
     * Set herkunftsterritorium
     *
     * @param string $herkunftsterritorium
     *
     * @return Ehepartner
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
     * Set todesland
     *
     * @param string $todesland
     *
     * @return Ehepartner
     */
    public function setTodesland($todesland)
    {
        $this->todesland = $todesland;

        return $this;
    }

    /**
     * Get todesland
     *
     * @return string
     */
    public function getTodesland()
    {
        return $this->todesland;
    }

    /**
     * Set bildungsabschluss
     *
     * @param string $bildungsabschluss
     *
     * @return Ehepartner
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
     * Set geburtsland
     *
     * @param string $geburtsland
     *
     * @return Ehepartner
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
     * Set ehren
     *
     * @param string $ehren
     *
     * @return Ehepartner
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
     * Set vorherNachher
     *
     * @param string $vorherNachher
     *
     * @return Ehepartner
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
     * Set todesursache
     *
     * @param string $todesursache
     *
     * @return Ehepartner
     */
    public function setTodesursache($todesursache)
    {
        $this->todesursache = $todesursache;

        return $this;
    }

    /**
     * Get todesursache
     *
     * @return string
     */
    public function getTodesursache()
    {
        return $this->todesursache;
    }

    /**
     * Set partnerpartnerIdNr
     *
     * @param string $partnerpartnerIdNr
     *
     * @return Ehepartner
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
}

