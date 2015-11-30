<?php

namespace UR\DB\OldBundle\Entity;

/**
 * TodDesKindes
 */
class TodDesKindes
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
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $todesort;

    /**
     * @var string
     */
    private $todesterritorium;

    /**
     * @var string
     */
    private $gestorben;

    /**
     * @var string
     */
    private $begräbnisort;

    /**
     * @var string
     */
    private $todesursache;

    /**
     * @var string
     */
    private $friedhof;

    /**
     * @var string
     */
    private $begraben;

    /**
     * @var string
     */
    private $kommentar;

    /**
     * @var string
     */
    private $todesland;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return TodDesKindes
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
     * @return TodDesKindes
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
     * @return TodDesKindes
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
     * Set id
     *
     * @param integer $id
     *
     * @return TodDesKindes
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
     * Set todesort
     *
     * @param string $todesort
     *
     * @return TodDesKindes
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
     * Set todesterritorium
     *
     * @param string $todesterritorium
     *
     * @return TodDesKindes
     */
    public function setTodesterritorium($todesterritorium)
    {
        $this->todesterritorium = $todesterritorium;

        return $this;
    }

    /**
     * Get todesterritorium
     *
     * @return string
     */
    public function getTodesterritorium()
    {
        return $this->todesterritorium;
    }

    /**
     * Set gestorben
     *
     * @param string $gestorben
     *
     * @return TodDesKindes
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
     * Set begräbnisort
     *
     * @param string $begräbnisort
     *
     * @return TodDesKindes
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
     * Set todesursache
     *
     * @param string $todesursache
     *
     * @return TodDesKindes
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

    /**
     * Set friedhof
     *
     * @param string $friedhof
     *
     * @return TodDesKindes
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
     * Set begraben
     *
     * @param string $begraben
     *
     * @return TodDesKindes
     */
    public function setBegraben($begraben)
    {
        $this->begraben = $begraben;

        return $this;
    }

    /**
     * Get begraben
     *
     * @return string
     */
    public function getBegraben()
    {
        return $this->begraben;
    }

    /**
     * Set kommentar
     *
     * @param string $kommentar
     *
     * @return TodDesKindes
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
     * Set todesland
     *
     * @param string $todesland
     *
     * @return TodDesKindes
     */
    public function setTodesland($todesland)
    {
        $this->todesland = $todesland;

        return $this;
    }

    /**
     * Get todesland
     *
     * @return string
     */
    public function getTodesland()
    {
        return $this->todesland;
    }
}

