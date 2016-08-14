<?php

namespace UR\DB\NewBundle\Entity;

use JMS\Serializer\Annotation\Type;

/**
 * Honour
 */
class Honour
{
    public function __toString (){
        return "Honour with ID: ".$this->getId();
    }
    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $honourOrder = '1';

    /**
     * @var string
     */
    private $label;

    /**
     * @var integer
     */
    private $countryid;

    /**
     * @var integer
     */
    private $territoryid;

    /**
     * @var integer
     */
    private $locationid;

    /**
     * @var string
     */
    private $fromDateid;

    /**
     * @var string
     */
    private $toDateid;

    /**
     * @var string
     */
    private $provenDateid;

    /**
     * @var string
     */
    private $comment;


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
     * Set honourOrder
     *
     * @param boolean $honourOrder
     *
     * @return Honour
     */
    public function setHonourOrder($honourOrder)
    {
        $this->honourOrder = $honourOrder;

        return $this;
    }

    /**
     * Get honourOrder
     *
     * @return boolean
     */
    public function getHonourOrder()
    {
        return $this->honourOrder;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return Honour
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set countryid
     *
     * @param integer $countryid
     *
     * @return Honour
     */
    public function setCountryid($countryid)
    {
        $this->countryid = $countryid;

        return $this;
    }

    /**
     * Get countryid
     *
     * @return integer
     */
    public function getCountryid()
    {
        return $this->countryid;
    }

    /**
     * Set territoryid
     *
     * @param integer $territoryid
     *
     * @return Honour
     */
    public function setTerritoryid($territoryid)
    {
        $this->territoryid = $territoryid;

        return $this;
    }

    /**
     * Get territoryid
     *
     * @return integer
     */
    public function getTerritoryid()
    {
        return $this->territoryid;
    }

    /**
     * Set locationid
     *
     * @param integer $locationid
     *
     * @return Honour
     */
    public function setLocationid($locationid)
    {
        $this->locationid = $locationid;

        return $this;
    }

    /**
     * Get locationid
     *
     * @return integer
     */
    public function getLocationid()
    {
        return $this->locationid;
    }

    /**
     * Set fromDateid
     *
     * @param string $fromDateid
     *
     * @return Honour
     */
    public function setFromDateid($fromDateid)
    {
        $this->fromDateid = $fromDateid;

        return $this;
    }

    /**
     * Get fromDateid
     *
     * @return string
     */
    public function getFromDateid()
    {
        return $this->fromDateid;
    }

    /**
     * Set toDateid
     *
     * @param string $toDateid
     *
     * @return Honour
     */
    public function setToDateid($toDateid)
    {
        $this->toDateid = $toDateid;

        return $this;
    }

    /**
     * Get toDateid
     *
     * @return string
     */
    public function getToDateid()
    {
        return $this->toDateid;
    }

    /**
     * Set provenDateid
     *
     * @param string $provenDateid
     *
     * @return Honour
     */
    public function setProvenDateid($provenDateid)
    {
        $this->provenDateid = $provenDateid;

        return $this;
    }

    /**
     * Get provenDateid
     *
     * @return string
     */
    public function getProvenDateid()
    {
        return $this->provenDateid;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Honour
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
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
     * @return Honour
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
     * @return Honour
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
    private $country;

    /**
     * @var \UR\DB\NewBundle\Entity\Territory
     */
    private $territory;

    /**
     * @var \UR\DB\NewBundle\Entity\Location
     */
    private $location;


    /**
     * Set country
     *
     * @param \UR\DB\NewBundle\Entity\Country $country
     *
     * @return Honour
     */
    public function setCountry(\UR\DB\NewBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \UR\DB\NewBundle\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set territory
     *
     * @param \UR\DB\NewBundle\Entity\Territory $territory
     *
     * @return Honour
     */
    public function setTerritory(\UR\DB\NewBundle\Entity\Territory $territory = null)
    {
        $this->territory = $territory;

        return $this;
    }

    /**
     * Get territory
     *
     * @return \UR\DB\NewBundle\Entity\Territory
     */
    public function getTerritory()
    {
        return $this->territory;
    }

    /**
     * Set location
     *
     * @param \UR\DB\NewBundle\Entity\Location $location
     *
     * @return Honour
     */
    public function setLocation(\UR\DB\NewBundle\Entity\Location $location = null)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return \UR\DB\NewBundle\Entity\Location
     */
    public function getLocation()
    {
        return $this->location;
    }
    /**
     * @var date_reference
     * @Type("array<UR\DB\NewBundle\Types\DateReference>")
     */
    private $fromDate;

    /**
     * @var date_reference
     * @Type("array<UR\DB\NewBundle\Types\DateReference>")
     */
    private $toDate;

    /**
     * @var date_reference
     * @Type("array<UR\DB\NewBundle\Types\DateReference>")
     */
    private $provenDate;


    /**
     * Set fromDate
     *
     * @param date_reference $fromDate
     *
     * @return Honour
     */
    public function setFromDate($fromDate)
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    /**
     * Get fromDate
     *
     * @return date_reference
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * Set toDate
     *
     * @param date_reference $toDate
     *
     * @return Honour
     */
    public function setToDate($toDate)
    {
        $this->toDate = $toDate;

        return $this;
    }

    /**
     * Get toDate
     *
     * @return date_reference
     */
    public function getToDate()
    {
        return $this->toDate;
    }

    /**
     * Set provenDate
     *
     * @param date_reference $provenDate
     *
     * @return Honour
     */
    public function setProvenDate($provenDate)
    {
        $this->provenDate = $provenDate;

        return $this;
    }

    /**
     * Get provenDate
     *
     * @return date_reference
     */
    public function getProvenDate()
    {
        return $this->provenDate;
    }
}
