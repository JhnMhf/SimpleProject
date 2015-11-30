<?php

namespace UR\DB\OldBundle\Entity;

/**
 * MutterDesGeschwisters
 */
class MutterDesGeschwisters
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
    private $ehelich;

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
    private $geboren;

    /**
     * @var string
     */
    private $gestorben;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return MutterDesGeschwisters
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
     * @return MutterDesGeschwisters
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
     * @return MutterDesGeschwisters
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
     * @return MutterDesGeschwisters
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
     * Set vornamen
     *
     * @param string $vornamen
     *
     * @return MutterDesGeschwisters
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
     * @return MutterDesGeschwisters
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
     * Set geboren
     *
     * @param string $geboren
     *
     * @return MutterDesGeschwisters
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
     * @return MutterDesGeschwisters
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
}

