<?php

namespace UR\DB\OldBundle\Entity;

/**
 * EhrenDesGeschwisters
 */
class EhrenDesGeschwisters
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
    private $land;

    /**
     * @var string
     */
    private $ehren;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return EhrenDesGeschwisters
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
     * @return EhrenDesGeschwisters
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
     * @return EhrenDesGeschwisters
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
     * Set land
     *
     * @param string $land
     *
     * @return EhrenDesGeschwisters
     */
    public function setLand($land)
    {
        $this->land = $land;

        return $this;
    }

    /**
     * Get land
     *
     * @return string
     */
    public function getLand()
    {
        return $this->land;
    }

    /**
     * Set ehren
     *
     * @param string $ehren
     *
     * @return EhrenDesGeschwisters
     */
    public function setEhren($ehren)
    {
        $this->ehren = $ehren;

        return $this;
    }

    /**
     * Get ehren
     *
     * @return string
     */
    public function getEhren()
    {
        return $this->ehren;
    }
}

