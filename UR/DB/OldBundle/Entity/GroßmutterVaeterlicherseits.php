<?php

namespace UR\DB\OldBundle\Entity;

/**
 * GroßmutterVaeterlicherseits
 */
class GroßmutterVaeterlicherseits
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
    private $name;

    /**
     * @var string
     */
    private $beruf;

    /**
     * @var string
     */
    private $geburtsland;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return GroßmutterVaeterlicherseits
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
     * @return GroßmutterVaeterlicherseits
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
     * @return GroßmutterVaeterlicherseits
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
     * @return GroßmutterVaeterlicherseits
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
     * @return GroßmutterVaeterlicherseits
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
     * @return GroßmutterVaeterlicherseits
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
     * Set geburtsland
     *
     * @param string $geburtsland
     *
     * @return GroßmutterVaeterlicherseits
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
}

