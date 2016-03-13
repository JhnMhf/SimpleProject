<?php

namespace UR\DB\NewBundle\Entity;

/**
 * Residence
 */
class Residence
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $residenceLocationid;

    /**
     * @var integer
     */
    private $residenceTerritoryid;

    /**
     * @var boolean
     */
    private $residenceOrder = '1';

    /**
     * @var integer
     */
    private $residenceCountryid;


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
     * Set residenceLocationid
     *
     * @param integer $residenceLocationid
     *
     * @return Residence
     */
    public function setResidenceLocationid($residenceLocationid)
    {
        $this->residenceLocationid = $residenceLocationid;

        return $this;
    }

    /**
     * Get residenceLocationid
     *
     * @return integer
     */
    public function getResidenceLocationid()
    {
        return $this->residenceLocationid;
    }

    /**
     * Set residenceTerritoryid
     *
     * @param integer $residenceTerritoryid
     *
     * @return Residence
     */
    public function setResidenceTerritoryid($residenceTerritoryid)
    {
        $this->residenceTerritoryid = $residenceTerritoryid;

        return $this;
    }

    /**
     * Get residenceTerritoryid
     *
     * @return integer
     */
    public function getResidenceTerritoryid()
    {
        return $this->residenceTerritoryid;
    }

    /**
     * Set residenceOrder
     *
     * @param boolean $residenceOrder
     *
     * @return Residence
     */
    public function setResidenceOrder($residenceOrder)
    {
        $this->residenceOrder = $residenceOrder;

        return $this;
    }

    /**
     * Get residenceOrder
     *
     * @return boolean
     */
    public function getResidenceOrder()
    {
        return $this->residenceOrder;
    }

    /**
     * Set residenceCountryid
     *
     * @param integer $residenceCountryid
     *
     * @return Residence
     */
    public function setResidenceCountryid($residenceCountryid)
    {
        $this->residenceCountryid = $residenceCountryid;

        return $this;
    }

    /**
     * Get residenceCountryid
     *
     * @return integer
     */
    public function getResidenceCountryid()
    {
        return $this->residenceCountryid;
    }
    /**
     * @var integer
     */
    private $personID;


    /**
     * Set personID
     *
     * @param integer $personID
     *
     * @return Residence
     */
    public function setPersonID($personID)
    {
        $this->personID = $personID;

        return $this;
    }

    /**
     * Get personID
     *
     * @return integer
     */
    public function getPersonID()
    {
        return $this->personID;
    }
}
