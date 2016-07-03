<?php

namespace UR\DB\NewBundle\Entity;

/**
 * Residence
 */
class Residence
{
    public function __toString (){
        return "Residence with ID: ".$this->getId();
    }
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
    /**
     * @var \UR\DB\NewBundle\Entity\BasePerson
     */
    private $person;


    /**
     * Set person
     *
     * @param \UR\DB\NewBundle\Entity\BasePerson $person
     *
     * @return Residence
     */
    public function setPerson(\UR\DB\NewBundle\Entity\BasePerson $person = null)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \UR\DB\NewBundle\Entity\BasePerson
     */
    public function getPerson()
    {
        return $this->person;
    }
    /**
     * @var \UR\DB\NewBundle\Entity\Country
     */
    private $residenceCountry;

    /**
     * @var \UR\DB\NewBundle\Entity\Territory
     */
    private $residenceTerritory;

    /**
     * @var \UR\DB\NewBundle\Entity\Location
     */
    private $residenceLocation;


    /**
     * Set residenceCountry
     *
     * @param \UR\DB\NewBundle\Entity\Country $residenceCountry
     *
     * @return Residence
     */
    public function setResidenceCountry(\UR\DB\NewBundle\Entity\Country $residenceCountry = null)
    {
        $this->residenceCountry = $residenceCountry;

        return $this;
    }

    /**
     * Get residenceCountry
     *
     * @return \UR\DB\NewBundle\Entity\Country
     */
    public function getResidenceCountry()
    {
        return $this->residenceCountry;
    }

    /**
     * Set residenceTerritory
     *
     * @param \UR\DB\NewBundle\Entity\Territory $residenceTerritory
     *
     * @return Residence
     */
    public function setResidenceTerritory(\UR\DB\NewBundle\Entity\Territory $residenceTerritory = null)
    {
        $this->residenceTerritory = $residenceTerritory;

        return $this;
    }

    /**
     * Get residenceTerritory
     *
     * @return \UR\DB\NewBundle\Entity\Territory
     */
    public function getResidenceTerritory()
    {
        return $this->residenceTerritory;
    }

    /**
     * Set residenceLocation
     *
     * @param \UR\DB\NewBundle\Entity\Location $residenceLocation
     *
     * @return Residence
     */
    public function setResidenceLocation(\UR\DB\NewBundle\Entity\Location $residenceLocation = null)
    {
        $this->residenceLocation = $residenceLocation;

        return $this;
    }

    /**
     * Get residenceLocation
     *
     * @return \UR\DB\NewBundle\Entity\Location
     */
    public function getResidenceLocation()
    {
        return $this->residenceLocation;
    }
}
