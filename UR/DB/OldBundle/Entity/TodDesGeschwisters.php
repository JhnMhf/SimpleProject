<?php

namespace UR\DB\OldBundle\Entity;

/**
 * TodDesGeschwisters
 */
class TodDesGeschwisters
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
    private $begräbnisort;

    /**
     * @var string
     */
    private $gestorben;

    /**
     * @var string
     */
    private $todesort;

    /**
     * @var string
     */
    private $friedhof;

    /**
     * @var string
     */
    private $kommentar;

    /**
     * @var string
     */
    private $todesursache;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return TodDesGeschwisters
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
     * @return TodDesGeschwisters
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
     * @return TodDesGeschwisters
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
     * Set begräbnisort
     *
     * @param string $begräbnisort
     *
     * @return TodDesGeschwisters
     */
    public function setBegräbnisort($begräbnisort)
    {
        $this->begräbnisort = $begräbnisort;

        return $this;
    }

    /**
     * Get begräbnisort
     *
     * @return string
     */
    public function getBegräbnisort()
    {
        return $this->begräbnisort;
    }

    /**
     * Set gestorben
     *
     * @param string $gestorben
     *
     * @return TodDesGeschwisters
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
     * Set todesort
     *
     * @param string $todesort
     *
     * @return TodDesGeschwisters
     */
    public function setTodesort($todesort)
    {
        $this->todesort = $todesort;

        return $this;
    }

    /**
     * Get todesort
     *
     * @return string
     */
    public function getTodesort()
    {
        return $this->todesort;
    }

    /**
     * Set friedhof
     *
     * @param string $friedhof
     *
     * @return TodDesGeschwisters
     */
    public function setFriedhof($friedhof)
    {
        $this->friedhof = $friedhof;

        return $this;
    }

    /**
     * Get friedhof
     *
     * @return string
     */
    public function getFriedhof()
    {
        return $this->friedhof;
    }

    /**
     * Set kommentar
     *
     * @param string $kommentar
     *
     * @return TodDesGeschwisters
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
     * Set todesursache
     *
     * @param string $todesursache
     *
     * @return TodDesGeschwisters
     */
    public function setTodesursache($todesursache)
    {
        $this->todesursache = $todesursache;

        return $this;
    }

    /**
     * Get todesursache
     *
     * @return string
     */
    public function getTodesursache()
    {
        return $this->todesursache;
    }
}

