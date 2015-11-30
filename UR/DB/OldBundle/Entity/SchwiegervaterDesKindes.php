<?php

namespace UR\DB\OldBundle\Entity;

/**
 * SchwiegervaterDesKindes
 */
class SchwiegervaterDesKindes
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
    private $ehelich;

    /**
     * @var string
     */
    private $name;

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
    private $vornamen;

    /**
     * @var string
     */
    private $rang;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return SchwiegervaterDesKindes
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
     * @return SchwiegervaterDesKindes
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
     * @return SchwiegervaterDesKindes
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
     * @return SchwiegervaterDesKindes
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
     * @return SchwiegervaterDesKindes
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
     * Set ehelich
     *
     * @param string $ehelich
     *
     * @return SchwiegervaterDesKindes
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
     * Set name
     *
     * @param string $name
     *
     * @return SchwiegervaterDesKindes
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
     * Set beruf
     *
     * @param string $beruf
     *
     * @return SchwiegervaterDesKindes
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
     * @return SchwiegervaterDesKindes
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
     * Set vornamen
     *
     * @param string $vornamen
     *
     * @return SchwiegervaterDesKindes
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
     * Set rang
     *
     * @param string $rang
     *
     * @return SchwiegervaterDesKindes
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
}

