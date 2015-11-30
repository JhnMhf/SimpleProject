<?php

namespace UR\DB\OldBundle\Entity;

/**
 * SchwiegermutterDesKindes
 */
class SchwiegermutterDesKindes
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
    private $vornamen;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return SchwiegermutterDesKindes
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
     * @return SchwiegermutterDesKindes
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
     * @return SchwiegermutterDesKindes
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
     * @return SchwiegermutterDesKindes
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
     * @return SchwiegermutterDesKindes
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
     * @return SchwiegermutterDesKindes
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
     * @return SchwiegermutterDesKindes
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
     * @return SchwiegermutterDesKindes
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
}

