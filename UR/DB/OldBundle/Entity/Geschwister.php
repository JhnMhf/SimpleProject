<?php

namespace UR\DB\OldBundle\Entity;

/**
 * Geschwister
 */
class Geschwister
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
    private $geschwisterIdNr;

    /**
     * @var string
     */
    private $kommentar;

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
    private $rufnamen;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return Geschwister
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
     * @return Geschwister
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
     * Set geschwisterIdNr
     *
     * @param string $geschwisterIdNr
     *
     * @return Geschwister
     */
    public function setGeschwisterIdNr($geschwisterIdNr)
    {
        $this->geschwisterIdNr = $geschwisterIdNr;

        return $this;
    }

    /**
     * Get geschwisterIdNr
     *
     * @return string
     */
    public function getGeschwisterIdNr()
    {
        return $this->geschwisterIdNr;
    }

    /**
     * Set kommentar
     *
     * @param string $kommentar
     *
     * @return Geschwister
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
     * Set geschlecht
     *
     * @param string $geschlecht
     *
     * @return Geschwister
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
     * @return Geschwister
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
     * @return Geschwister
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
     * @return Geschwister
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
     * Set rufnamen
     *
     * @param string $rufnamen
     *
     * @return Geschwister
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
}

