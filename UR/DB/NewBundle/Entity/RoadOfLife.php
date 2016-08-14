<?php

namespace UR\DB\NewBundle\Entity;

use JMS\Serializer\Annotation\Type;

/**
 * RoadOfLife
 */
class RoadOfLife
{
    public function __toString (){
        return "RoadOfLife with ID: ".$this->getId();
    }
    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $roadOfLifeOrder = '1';

    /**
     * @var integer
     */
    private $originCountryid;

    /**
     * @var integer
     */
    private $originTerritoryid;

    /**
     * @var integer
     */
    private $jobid;

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
     * @var integer
     */
    private $fromDateid;

    /**
     * @var integer
     */
    private $toDateid;

    /**
     * @var integer
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
     * Set roadOfLifeOrder
     *
     * @param boolean $roadOfLifeOrder
     *
     * @return RoadOfLife
     */
    public function setRoadOfLifeOrder($roadOfLifeOrder)
    {
        $this->roadOfLifeOrder = $roadOfLifeOrder;

        return $this;
    }

    /**
     * Get roadOfLifeOrder
     *
     * @return boolean
     */
    public function getRoadOfLifeOrder()
    {
        return $this->roadOfLifeOrder;
    }

    /**
     * Set originCountryid
     *
     * @param integer $originCountryid
     *
     * @return RoadOfLife
     */
    public function setOriginCountryid($originCountryid)
    {
        $this->originCountryid = $originCountryid;

        return $this;
    }

    /**
     * Get originCountryid
     *
     * @return integer
     */
    public function getOriginCountryid()
    {
        return $this->originCountryid;
    }

    /**
     * Set originTerritoryid
     *
     * @param integer $originTerritoryid
     *
     * @return RoadOfLife
     */
    public function setOriginTerritoryid($originTerritoryid)
    {
        $this->originTerritoryid = $originTerritoryid;

        return $this;
    }

    /**
     * Get originTerritoryid
     *
     * @return integer
     */
    public function getOriginTerritoryid()
    {
        return $this->originTerritoryid;
    }

    /**
     * Set jobid
     *
     * @param integer $jobid
     *
     * @return RoadOfLife
     */
    public function setJobid($jobid)
    {
        $this->jobid = $jobid;

        return $this;
    }

    /**
     * Get jobid
     *
     * @return integer
     */
    public function getJobid()
    {
        return $this->jobid;
    }

    /**
     * Set countryid
     *
     * @param integer $countryid
     *
     * @return RoadOfLife
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
     * @return RoadOfLife
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
     * @return RoadOfLife
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
     * @param integer $fromDateid
     *
     * @return RoadOfLife
     */
    public function setFromDateid($fromDateid)
    {
        $this->fromDateid = $fromDateid;

        return $this;
    }

    /**
     * Get fromDateid
     *
     * @return integer
     */
    public function getFromDateid()
    {
        return $this->fromDateid;
    }

    /**
     * Set toDateid
     *
     * @param integer $toDateid
     *
     * @return RoadOfLife
     */
    public function setToDateid($toDateid)
    {
        $this->toDateid = $toDateid;

        return $this;
    }

    /**
     * Get toDateid
     *
     * @return integer
     */
    public function getToDateid()
    {
        return $this->toDateid;
    }

    /**
     * Set provenDateid
     *
     * @param integer $provenDateid
     *
     * @return RoadOfLife
     */
    public function setProvenDateid($provenDateid)
    {
        $this->provenDateid = $provenDateid;

        return $this;
    }

    /**
     * Get provenDateid
     *
     * @return integer
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
     * @return RoadOfLife
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
     * @return RoadOfLife
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
     * @return RoadOfLife
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
    private $originCountry;

    /**
     * @var \UR\DB\NewBundle\Entity\Territory
     */
    private $originTerritory;

    /**
     * @var \UR\DB\NewBundle\Entity\Job
     */
    private $job;

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
     * Set originCountry
     *
     * @param \UR\DB\NewBundle\Entity\Country $originCountry
     *
     * @return RoadOfLife
     */
    public function setOriginCountry(\UR\DB\NewBundle\Entity\Country $originCountry = null)
    {
        $this->originCountry = $originCountry;

        return $this;
    }

    /**
     * Get originCountry
     *
     * @return \UR\DB\NewBundle\Entity\Country
     */
    public function getOriginCountry()
    {
        return $this->originCountry;
    }

    /**
     * Set originTerritory
     *
     * @param \UR\DB\NewBundle\Entity\Territory $originTerritory
     *
     * @return RoadOfLife
     */
    public function setOriginTerritory(\UR\DB\NewBundle\Entity\Territory $originTerritory = null)
    {
        $this->originTerritory = $originTerritory;

        return $this;
    }

    /**
     * Get originTerritory
     *
     * @return \UR\DB\NewBundle\Entity\Territory
     */
    public function getOriginTerritory()
    {
        return $this->originTerritory;
    }

    /**
     * Set job
     *
     * @param \UR\DB\NewBundle\Entity\Job $job
     *
     * @return RoadOfLife
     */
    public function setJob(\UR\DB\NewBundle\Entity\Job $job = null)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * Get job
     *
     * @return \UR\DB\NewBundle\Entity\Job
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Set country
     *
     * @param \UR\DB\NewBundle\Entity\Country $country
     *
     * @return RoadOfLife
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
     * @return RoadOfLife
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
     * @return RoadOfLife
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
     * @return RoadOfLife
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
     * @return RoadOfLife
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
     * @return RoadOfLife
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
