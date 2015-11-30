<?php

namespace UR\DB\OldBundle\Entity;

/**
 * Mutter
 */
class Mutter
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
    private $name;

    /**
     * @var string
     */
    private $gestorben;

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
    private $russVornamen;

    /**
     * @var string
     */
    private $konfession;

    /**
     * @var string
     */
    private $stand;

    /**
     * @var string
     */
    private $mutterIdNr;

    /**
     * @var string
     */
    private $todesort;

    /**
     * @var string
     */
    private $geboren;

    /**
     * @var string
     */
    private $friedhof;

    /**
     * @var string
     */
    private $herkunftsland;

    /**
     * @var string
     */
    private $wohnort;

    /**
     * @var string
     */
    private $kommentar;

    /**
     * @var string
     */
    private $getauft;

    /**
     * @var string
     */
    private $besitz;

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
    private $nation;

    /**
     * @var string
     */
    private $ehelich;

    /**
     * @var string
     */
    private $rufnamen;

    /**
     * @var string
     */
    private $rang;

    /**
     * @var string
     */
    private $begraben;

    /**
     * @var string
     */
    private $todesterritorium;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return Mutter
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
     * @return Mutter
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
     * @return Mutter
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
     * @return Mutter
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
     * Set gestorben
     *
     * @param string $gestorben
     *
     * @return Mutter
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
     * Set herkunftsort
     *
     * @param string $herkunftsort
     *
     * @return Mutter
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
     * @return Mutter
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
     * Set russVornamen
     *
     * @param string $russVornamen
     *
     * @return Mutter
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
     * Set konfession
     *
     * @param string $konfession
     *
     * @return Mutter
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
     * Set stand
     *
     * @param string $stand
     *
     * @return Mutter
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
     * Set mutterIdNr
     *
     * @param string $mutterIdNr
     *
     * @return Mutter
     */
    public function setMutterIdNr($mutterIdNr)
    {
        $this->mutterIdNr = $mutterIdNr;

        return $this;
    }

    /**
     * Get mutterIdNr
     *
     * @return string
     */
    public function getMutterIdNr()
    {
        return $this->mutterIdNr;
    }

    /**
     * Set todesort
     *
     * @param string $todesort
     *
     * @return Mutter
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
     * Set geboren
     *
     * @param string $geboren
     *
     * @return Mutter
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
     * Set friedhof
     *
     * @param string $friedhof
     *
     * @return Mutter
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
     * Set herkunftsland
     *
     * @param string $herkunftsland
     *
     * @return Mutter
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
     * Set wohnort
     *
     * @param string $wohnort
     *
     * @return Mutter
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
     * Set kommentar
     *
     * @param string $kommentar
     *
     * @return Mutter
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
     * Set getauft
     *
     * @param string $getauft
     *
     * @return Mutter
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
     * Set besitz
     *
     * @param string $besitz
     *
     * @return Mutter
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
     * Set beruf
     *
     * @param string $beruf
     *
     * @return Mutter
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
     * @return Mutter
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
     * @return Mutter
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
     * Set ehelich
     *
     * @param string $ehelich
     *
     * @return Mutter
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
     * Set rufnamen
     *
     * @param string $rufnamen
     *
     * @return Mutter
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
     * Set rang
     *
     * @param string $rang
     *
     * @return Mutter
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
     * Set begraben
     *
     * @param string $begraben
     *
     * @return Mutter
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
     * Set todesterritorium
     *
     * @param string $todesterritorium
     *
     * @return Mutter
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
}

