<?php

namespace UR\DB\OldBundle\Entity;

/**
 * VaterDesGeschwisters
 */
class VaterDesGeschwisters
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
    private $geschwistervaterIdNr;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return VaterDesGeschwisters
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
     * @return VaterDesGeschwisters
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
     * @return VaterDesGeschwisters
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
     * Set geschwistervaterIdNr
     *
     * @param string $geschwistervaterIdNr
     *
     * @return VaterDesGeschwisters
     */
    public function setGeschwistervaterIdNr($geschwistervaterIdNr)
    {
        $this->geschwistervaterIdNr = $geschwistervaterIdNr;

        return $this;
    }

    /**
     * Get geschwistervaterIdNr
     *
     * @return string
     */
    public function getGeschwistervaterIdNr()
    {
        return $this->geschwistervaterIdNr;
    }
}

