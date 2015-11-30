<?php

namespace UR\DB\OldBundle\Entity;

/**
 * Kind
 */
class Kind
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
    private $kindIdNr;

    /**
     * @var string
     */
    private $geschlecht;

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
    private $name;

    /**
     * @var string
     */
    private $rufnamen;

    /**
     * @var string
     */
    private $kommentar;

    /**
     * @var string
     */
    private $geboren;

    /**
     * @var string
     */
    private $geburtsort;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return Kind
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
     * @return Kind
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
     * @return Kind
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
     * Set kindIdNr
     *
     * @param string $kindIdNr
     *
     * @return Kind
     */
    public function setKindIdNr($kindIdNr)
    {
        $this->kindIdNr = $kindIdNr;

        return $this;
    }

    /**
     * Get kindIdNr
     *
     * @return string
     */
    public function getKindIdNr()
    {
        return $this->kindIdNr;
    }

    /**
     * Set geschlecht
     *
     * @param string $geschlecht
     *
     * @return Kind
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
     * Set vornamen
     *
     * @param string $vornamen
     *
     * @return Kind
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
     * @return Kind
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
     * Set name
     *
     * @param string $name
     *
     * @return Kind
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
     * Set rufnamen
     *
     * @param string $rufnamen
     *
     * @return Kind
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
     * Set kommentar
     *
     * @param string $kommentar
     *
     * @return Kind
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
     * Set geboren
     *
     * @param string $geboren
     *
     * @return Kind
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
     * Set geburtsort
     *
     * @param string $geburtsort
     *
     * @return Kind
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
}

