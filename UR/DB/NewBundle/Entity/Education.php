<?php

namespace UR\DB\NewBundle\Entity;

use JMS\Serializer\Annotation\Type;

/**
 * Education
 */
class Education
{
    public function __toString (){
        return "Education with ID: ".$this->getId();
    }    
    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $educationOrder = '1';

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
    private $graduationLabel;

    /**
     * @var string
     */
    private $graduationDateid;

    /**
     * @var integer
     */
    private $graduationLocationid;

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
     * Set educationOrder
     *
     * @param boolean $educationOrder
     *
     * @return Education
     */
    public function setEducationOrder($educationOrder)
    {
        $this->educationOrder = $educationOrder;

        return $this;
    }

    /**
     * Get educationOrder
     *
     * @return boolean
     */
    public function getEducationOrder()
    {
        return $this->educationOrder;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return Education
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
     * @return Education
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
     * @return Education
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
     * @return Education
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
     * @return Education
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
     * @return Education
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
     * @return Education
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
     * Set graduationLabel
     *
     * @param string $graduationLabel
     *
     * @return Education
     */
    public function setGraduationLabel($graduationLabel)
    {
        $this->graduationLabel = $graduationLabel;

        return $this;
    }

    /**
     * Get graduationLabel
     *
     * @return string
     */
    public function getGraduationLabel()
    {
        return $this->graduationLabel;
    }

    /**
     * Set graduationDateid
     *
     * @param string $graduationDateid
     *
     * @return Education
     */
    public function setGraduationDateid($graduationDateid)
    {
        $this->graduationDateid = $graduationDateid;

        return $this;
    }

    /**
     * Get graduationDateid
     *
     * @return string
     */
    public function getGraduationDateid()
    {
        return $this->graduationDateid;
    }

    /**
     * Set graduationLocationid
     *
     * @param integer $graduationLocationid
     *
     * @return Education
     */
    public function setGraduationLocationid($graduationLocationid)
    {
        $this->graduationLocationid = $graduationLocationid;

        return $this;
    }

    /**
     * Get graduationLocationid
     *
     * @return integer
     */
    public function getGraduationLocationid()
    {
        return $this->graduationLocationid;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Education
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
     * @return Education
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
     * @return Education
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
     * @var \UR\DB\NewBundle\Entity\Location
     */
    private $graduationLocation;


    /**
     * Set country
     *
     * @param \UR\DB\NewBundle\Entity\Country $country
     *
     * @return Education
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
     * @return Education
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
     * @return Education
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
     * Set graduationLocation
     *
     * @param \UR\DB\NewBundle\Entity\Location $graduationLocation
     *
     * @return Education
     */
    public function setGraduationLocation(\UR\DB\NewBundle\Entity\Location $graduationLocation = null)
    {
        $this->graduationLocation = $graduationLocation;

        return $this;
    }

    /**
     * Get graduationLocation
     *
     * @return \UR\DB\NewBundle\Entity\Location
     */
    public function getGraduationLocation()
    {
        return $this->graduationLocation;
    }
    /**
     * @var date_reference
     *  @Type("array<UR\DB\NewBundle\Types\DateReference>")
     */
    private $fromDate;

    /**
     * @var date_reference
     *  @Type("array<UR\DB\NewBundle\Types\DateReference>")
     */
    private $toDate;

    /**
     * @var date_reference
     *  @Type("array<UR\DB\NewBundle\Types\DateReference>")
     */
    private $provenDate;

    /**
     * @var date_reference
     *  @Type("array<UR\DB\NewBundle\Types\DateReference>")
     */
    private $graduationDate;


    /**
     * Set fromDate
     *
     * @param date_reference $fromDate
     *
     * @return Education
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
     * @return Education
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
     * @return Education
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

    /**
     * Set graduationDate
     *
     * @param date_reference $graduationDate
     *
     * @return Education
     */
    public function setGraduationDate($graduationDate)
    {
        $this->graduationDate = $graduationDate;

        return $this;
    }

    /**
     * Get graduationDate
     *
     * @return date_reference
     */
    public function getGraduationDate()
    {
        return $this->graduationDate;
    }
}
