<?php

namespace UR\DB\OldBundle\Entity;

/**
 * Tod
 */
class Tod
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
    private $todesort;

    /**
     * @var string
     */
    private $gestorben;

    /**
     * @var string
     */
    private $todesursache;

    /**
     * @var string
     */
    private $todesterritorium;

    /**
     * @var string
     */
    private $friedhof;

    /**
     * @var string
     */
    private $begräbnisort;

    /**
     * @var string
     */
    private $begraben;

    /**
     * @var string
     */
    private $todesland;

    /**
     * @var string
     */
    private $kommentar;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return Tod
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
     * @return Tod
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
     * @return Tod
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
     * Set gestorben
     *
     * @param string $gestorben
     *
     * @return Tod
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
     * Set todesursache
     *
     * @param string $todesursache
     *
     * @return Tod
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
     * Set todesterritorium
     *
     * @param string $todesterritorium
     *
     * @return Tod
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
     * Set friedhof
     *
     * @param string $friedhof
     *
     * @return Tod
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
     * Set begräbnisort
     *
     * @param string $begräbnisort
     *
     * @return Tod
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
     * Set begraben
     *
     * @param string $begraben
     *
     * @return Tod
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
     * Set todesland
     *
     * @param string $todesland
     *
     * @return Tod
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

    /**
     * Set kommentar
     *
     * @param string $kommentar
     *
     * @return Tod
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
}

