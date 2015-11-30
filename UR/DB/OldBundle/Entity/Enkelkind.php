<?php

namespace UR\DB\OldBundle\Entity;

/**
 * Enkelkind
 */
class Enkelkind
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
    private $geschlecht;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $vornamen;

    /**
     * @var string
     */
    private $russVornamen;

    /**
     * @var string
     */
    private $enkelIdNr;

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
    private $besitz;

    /**
     * @var string
     */
    private $geburtsort;

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
    private $rufnamen;

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
    private $rang;

    /**
     * @var string
     */
    private $bildungsabschluss;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return Enkelkind
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
     * @return Enkelkind
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
     * @return Enkelkind
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
     * @return Enkelkind
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
     * @return Enkelkind
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
     * Set geschlecht
     *
     * @param string $geschlecht
     *
     * @return Enkelkind
     */
    public function setGeschlecht($geschlecht)
    {
        $this->geschlecht = $geschlecht;

        return $this;
    }

    /**
     * Get geschlecht
     *
     * @return string
     */
    public function getGeschlecht()
    {
        return $this->geschlecht;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Enkelkind
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
     * Set vornamen
     *
     * @param string $vornamen
     *
     * @return Enkelkind
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
     * Set russVornamen
     *
     * @param string $russVornamen
     *
     * @return Enkelkind
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
     * Set enkelIdNr
     *
     * @param string $enkelIdNr
     *
     * @return Enkelkind
     */
    public function setEnkelIdNr($enkelIdNr)
    {
        $this->enkelIdNr = $enkelIdNr;

        return $this;
    }

    /**
     * Get enkelIdNr
     *
     * @return string
     */
    public function getEnkelIdNr()
    {
        return $this->enkelIdNr;
    }

    /**
     * Set geboren
     *
     * @param string $geboren
     *
     * @return Enkelkind
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
     * @return Enkelkind
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
     * Set besitz
     *
     * @param string $besitz
     *
     * @return Enkelkind
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
     * @return Enkelkind
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
     * Set beruf
     *
     * @param string $beruf
     *
     * @return Enkelkind
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
     * @return Enkelkind
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
     * Set rufnamen
     *
     * @param string $rufnamen
     *
     * @return Enkelkind
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
     * Set stand
     *
     * @param string $stand
     *
     * @return Enkelkind
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
     * @return Enkelkind
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
     * Set rang
     *
     * @param string $rang
     *
     * @return Enkelkind
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
     * @return Enkelkind
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
}

