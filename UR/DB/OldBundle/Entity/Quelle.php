<?php

namespace UR\DB\OldBundle\Entity;

/**
 * Quelle
 */
class Quelle
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
    private $bezeichnung;

    /**
     * @var string
     */
    private $fundstelle;

    /**
     * @var string
     */
    private $bemerkung;

    /**
     * @var string
     */
    private $kommentar;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return Quelle
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
     * @return Quelle
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
     * Set bezeichnung
     *
     * @param string $bezeichnung
     *
     * @return Quelle
     */
    public function setBezeichnung($bezeichnung)
    {
        $this->bezeichnung = $bezeichnung;

        return $this;
    }

    /**
     * Get bezeichnung
     *
     * @return string
     */
    public function getBezeichnung()
    {
        return $this->bezeichnung;
    }

    /**
     * Set fundstelle
     *
     * @param string $fundstelle
     *
     * @return Quelle
     */
    public function setFundstelle($fundstelle)
    {
        $this->fundstelle = $fundstelle;

        return $this;
    }

    /**
     * Get fundstelle
     *
     * @return string
     */
    public function getFundstelle()
    {
        return $this->fundstelle;
    }

    /**
     * Set bemerkung
     *
     * @param string $bemerkung
     *
     * @return Quelle
     */
    public function setBemerkung($bemerkung)
    {
        $this->bemerkung = $bemerkung;

        return $this;
    }

    /**
     * Get bemerkung
     *
     * @return string
     */
    public function getBemerkung()
    {
        return $this->bemerkung;
    }

    /**
     * Set kommentar
     *
     * @param string $kommentar
     *
     * @return Quelle
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

