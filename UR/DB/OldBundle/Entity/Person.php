<?php

namespace UR\DB\OldBundle\Entity;

/**
 * Person
 */
class Person
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
    private $urspNation;

    /**
     * @var string
     */
    private $berufsklasse;

    /**
     * @var string
     */
    private $rufnamen;

    /**
     * @var string
     */
    private $geburtsname;

    /**
     * @var string
     */
    private $kommentar;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return Person
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
     * @return Person
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
     * @return Person
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
     * @return Person
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
     * @return Person
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
     * @return Person
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
     * Set urspNation
     *
     * @param string $urspNation
     *
     * @return Person
     */
    public function setUrspNation($urspNation)
    {
        $this->urspNation = $urspNation;

        return $this;
    }

    /**
     * Get urspNation
     *
     * @return string
     */
    public function getUrspNation()
    {
        return $this->urspNation;
    }

    /**
     * Set berufsklasse
     *
     * @param string $berufsklasse
     *
     * @return Person
     */
    public function setBerufsklasse($berufsklasse)
    {
        $this->berufsklasse = $berufsklasse;

        return $this;
    }

    /**
     * Get berufsklasse
     *
     * @return string
     */
    public function getBerufsklasse()
    {
        return $this->berufsklasse;
    }

    /**
     * Set rufnamen
     *
     * @param string $rufnamen
     *
     * @return Person
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
     * Set geburtsname
     *
     * @param string $geburtsname
     *
     * @return Person
     */
    public function setGeburtsname($geburtsname)
    {
        $this->geburtsname = $geburtsname;

        return $this;
    }

    /**
     * Get geburtsname
     *
     * @return string
     */
    public function getGeburtsname()
    {
        return $this->geburtsname;
    }

    /**
     * Set kommentar
     *
     * @param string $kommentar
     *
     * @return Person
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

