<?php

namespace UR\DB\OldBundle\Entity;

/**
 * Vater
 */
class Vater
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
    private $vornamen;

    /**
     * @var string
     */
    private $beruf;

    /**
     * @var string
     */
    private $stand;

    /**
     * @var string
     */
    private $bildungsabschluss;

    /**
     * @var string
     */
    private $wohnort;

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
    private $gestorben;

    /**
     * @var string
     */
    private $vaterIdNr;

    /**
     * @var string
     */
    private $herkunftsort;

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
    private $name;

    /**
     * @var string
     */
    private $wohnterritorium;

    /**
     * @var string
     */
    private $geburtsterritorium;

    /**
     * @var string
     */
    private $rang;

    /**
     * @var string
     */
    private $geburtsort;

    /**
     * @var string
     */
    private $besitz;

    /**
     * @var string
     */
    private $ehren;

    /**
     * @var string
     */
    private $ehelich;

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
    private $nation;

    /**
     * @var string
     */
    private $todesterritorium;

    /**
     * @var string
     */
    private $geburtsland;

    /**
     * @var string
     */
    private $konfession;

    /**
     * @var string
     */
    private $wohnland;

    /**
     * @var string
     */
    private $herkunftsland;

    /**
     * @var string
     */
    private $begraben;

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
    private $begräbnisort;

    /**
     * @var string
     */
    private $hochzeitstag;

    /**
     * @var string
     */
    private $ausbildung;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return Vater
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
     * @return Vater
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
     * @return Vater
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
     * Set beruf
     *
     * @param string $beruf
     *
     * @return Vater
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
     * Set stand
     *
     * @param string $stand
     *
     * @return Vater
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
     * Set bildungsabschluss
     *
     * @param string $bildungsabschluss
     *
     * @return Vater
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
     * Set wohnort
     *
     * @param string $wohnort
     *
     * @return Vater
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
     * Set russVornamen
     *
     * @param string $russVornamen
     *
     * @return Vater
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
     * @return Vater
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
     * @return Vater
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
     * Set vaterIdNr
     *
     * @param string $vaterIdNr
     *
     * @return Vater
     */
    public function setVaterIdNr($vaterIdNr)
    {
        $this->vaterIdNr = $vaterIdNr;

        return $this;
    }

    /**
     * Get vaterIdNr
     *
     * @return string
     */
    public function getVaterIdNr()
    {
        return $this->vaterIdNr;
    }

    /**
     * Set herkunftsort
     *
     * @param string $herkunftsort
     *
     * @return Vater
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
     * Set herkunftsterritorium
     *
     * @param string $herkunftsterritorium
     *
     * @return Vater
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
     * @return Vater
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
     * Set name
     *
     * @param string $name
     *
     * @return Vater
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
     * Set wohnterritorium
     *
     * @param string $wohnterritorium
     *
     * @return Vater
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

    /**
     * Set geburtsterritorium
     *
     * @param string $geburtsterritorium
     *
     * @return Vater
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
     * Set rang
     *
     * @param string $rang
     *
     * @return Vater
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
     * Set geburtsort
     *
     * @param string $geburtsort
     *
     * @return Vater
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
     * Set besitz
     *
     * @param string $besitz
     *
     * @return Vater
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
     * Set ehren
     *
     * @param string $ehren
     *
     * @return Vater
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
     * Set ehelich
     *
     * @param string $ehelich
     *
     * @return Vater
     */
    public function setEhelich($ehelich)
    {
        $this->ehelich = $ehelich;

        return $this;
    }

    /**
     * Get ehelich
     *
     * @return string
     */
    public function getEhelich()
    {
        return $this->ehelich;
    }

    /**
     * Set kommentar
     *
     * @param string $kommentar
     *
     * @return Vater
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
     * @return Vater
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
     * Set nation
     *
     * @param string $nation
     *
     * @return Vater
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
     * Set todesterritorium
     *
     * @param string $todesterritorium
     *
     * @return Vater
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
     * Set geburtsland
     *
     * @param string $geburtsland
     *
     * @return Vater
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
     * Set konfession
     *
     * @param string $konfession
     *
     * @return Vater
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
     * Set wohnland
     *
     * @param string $wohnland
     *
     * @return Vater
     */
    public function setWohnland($wohnland)
    {
        $this->wohnland = $wohnland;

        return $this;
    }

    /**
     * Get wohnland
     *
     * @return string
     */
    public function getWohnland()
    {
        return $this->wohnland;
    }

    /**
     * Set herkunftsland
     *
     * @param string $herkunftsland
     *
     * @return Vater
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
     * Set begraben
     *
     * @param string $begraben
     *
     * @return Vater
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
     * Set getauft
     *
     * @param string $getauft
     *
     * @return Vater
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
     * @return Vater
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
     * Set begräbnisort
     *
     * @param string $begräbnisort
     *
     * @return Vater
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
     * Set hochzeitstag
     *
     * @param string $hochzeitstag
     *
     * @return Vater
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
     * Set ausbildung
     *
     * @param string $ausbildung
     *
     * @return Vater
     */
    public function setAusbildung($ausbildung)
    {
        $this->ausbildung = $ausbildung;

        return $this;
    }

    /**
     * Get ausbildung
     *
     * @return string
     */
    public function getAusbildung()
    {
        return $this->ausbildung;
    }
}

