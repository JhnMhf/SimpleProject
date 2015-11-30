<?php

namespace UR\DB\OldBundle\Entity;

/**
 * Schwiegervater
 */
class Schwiegervater
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
    private $beruf;

    /**
     * @var string
     */
    private $rang;

    /**
     * @var string
     */
    private $bildungsabschluss;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $ehelich;

    /**
     * @var string
     */
    private $wohnort;

    /**
     * @var string
     */
    private $wohnterritorium;

    /**
     * @var string
     */
    private $ehren;

    /**
     * @var string
     */
    private $gestorben;

    /**
     * @var string
     */
    private $geboren;

    /**
     * @var string
     */
    private $herkunftsort;

    /**
     * @var string
     */
    private $todesort;

    /**
     * @var string
     */
    private $wohnland;

    /**
     * @var string
     */
    private $russVornamen;

    /**
     * @var string
     */
    private $herkunftsterritorium;

    /**
     * @var string
     */
    private $stand;

    /**
     * @var string
     */
    private $kommentar;

    /**
     * @var string
     */
    private $nation;

    /**
     * @var string
     */
    private $taufort;

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
    private $schwiegervaterIdNr;

    /**
     * @var string
     */
    private $besitz;

    /**
     * @var string
     */
    private $hochzeitsort;

    /**
     * @var string
     */
    private $begräbnisort;

    /**
     * @var string
     */
    private $getauft;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return Schwiegervater
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
     * @return Schwiegervater
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
     * @return Schwiegervater
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
     * @return Schwiegervater
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
     * @return Schwiegervater
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
     * @return Schwiegervater
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
     * Set bildungsabschluss
     *
     * @param string $bildungsabschluss
     *
     * @return Schwiegervater
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
     * Set name
     *
     * @param string $name
     *
     * @return Schwiegervater
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
     * Set ehelich
     *
     * @param string $ehelich
     *
     * @return Schwiegervater
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
     * Set wohnort
     *
     * @param string $wohnort
     *
     * @return Schwiegervater
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
     * Set wohnterritorium
     *
     * @param string $wohnterritorium
     *
     * @return Schwiegervater
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
     * Set ehren
     *
     * @param string $ehren
     *
     * @return Schwiegervater
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
     * Set gestorben
     *
     * @param string $gestorben
     *
     * @return Schwiegervater
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
     * Set geboren
     *
     * @param string $geboren
     *
     * @return Schwiegervater
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
     * Set herkunftsort
     *
     * @param string $herkunftsort
     *
     * @return Schwiegervater
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
     * Set todesort
     *
     * @param string $todesort
     *
     * @return Schwiegervater
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
     * Set wohnland
     *
     * @param string $wohnland
     *
     * @return Schwiegervater
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
     * Set russVornamen
     *
     * @param string $russVornamen
     *
     * @return Schwiegervater
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
     * Set herkunftsterritorium
     *
     * @param string $herkunftsterritorium
     *
     * @return Schwiegervater
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
     * Set stand
     *
     * @param string $stand
     *
     * @return Schwiegervater
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
     * Set kommentar
     *
     * @param string $kommentar
     *
     * @return Schwiegervater
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
     * Set nation
     *
     * @param string $nation
     *
     * @return Schwiegervater
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
     * Set taufort
     *
     * @param string $taufort
     *
     * @return Schwiegervater
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
     * Set konfession
     *
     * @param string $konfession
     *
     * @return Schwiegervater
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
     * @return Schwiegervater
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
     * Set schwiegervaterIdNr
     *
     * @param string $schwiegervaterIdNr
     *
     * @return Schwiegervater
     */
    public function setSchwiegervaterIdNr($schwiegervaterIdNr)
    {
        $this->schwiegervaterIdNr = $schwiegervaterIdNr;

        return $this;
    }

    /**
     * Get schwiegervaterIdNr
     *
     * @return string
     */
    public function getSchwiegervaterIdNr()
    {
        return $this->schwiegervaterIdNr;
    }

    /**
     * Set besitz
     *
     * @param string $besitz
     *
     * @return Schwiegervater
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
     * Set hochzeitsort
     *
     * @param string $hochzeitsort
     *
     * @return Schwiegervater
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
     * Set begräbnisort
     *
     * @param string $begräbnisort
     *
     * @return Schwiegervater
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
     * @return Schwiegervater
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
}

