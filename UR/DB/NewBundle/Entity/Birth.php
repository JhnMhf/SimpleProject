<?php

namespace UR\DB\NewBundle\Entity;

use JMS\Serializer\Annotation\Type;

/**
 * Birth
 */
class Birth
{
    public function __toString (){
        return "Birth with ID: ".$this->getId();
    }
    /**
     * @var integer
     */
    private $id;

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
    private $originLocationid;

    /**
     * @var integer
     */
    private $birthCountryid;

    /**
     * @var integer
     */
    private $birthLocationid;

    /**
     * @var string
     */
    private $birthDateid;

    /**
     * @var integer
     */
    private $birthTerritoryid;

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
     * Set originCountryid
     *
     * @param integer $originCountryid
     *
     * @return Birth
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
     * @return Birth
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
     * Set originLocationid
     *
     * @param integer $originLocationid
     *
     * @return Birth
     */
    public function setOriginLocationid($originLocationid)
    {
        $this->originLocationid = $originLocationid;

        return $this;
    }

    /**
     * Get originLocationid
     *
     * @return integer
     */
    public function getOriginLocationid()
    {
        return $this->originLocationid;
    }

    /**
     * Set birthCountryid
     *
     * @param integer $birthCountryid
     *
     * @return Birth
     */
    public function setBirthCountryid($birthCountryid)
    {
        $this->birthCountryid = $birthCountryid;

        return $this;
    }

    /**
     * Get birthCountryid
     *
     * @return integer
     */
    public function getBirthCountryid()
    {
        return $this->birthCountryid;
    }

    /**
     * Set birthLocationid
     *
     * @param integer $birthLocationid
     *
     * @return Birth
     */
    public function setBirthLocationid($birthLocationid)
    {
        $this->birthLocationid = $birthLocationid;

        return $this;
    }

    /**
     * Get birthLocationid
     *
     * @return integer
     */
    public function getBirthLocationid()
    {
        return $this->birthLocationid;
    }

    /**
     * Set birthDateid
     *
     * @param string $birthDateid
     *
     * @return Birth
     */
    public function setBirthDateid($birthDateid)
    {
        $this->birthDateid = $birthDateid;

        return $this;
    }

    /**
     * Get birthDateid
     *
     * @return string
     */
    public function getBirthDateid()
    {
        return $this->birthDateid;
    }

    /**
     * Set birthTerritoryid
     *
     * @param integer $birthTerritoryid
     *
     * @return Birth
     */
    public function setBirthTerritoryid($birthTerritoryid)
    {
        $this->birthTerritoryid = $birthTerritoryid;

        return $this;
    }

    /**
     * Get birthTerritoryid
     *
     * @return integer
     */
    public function getBirthTerritoryid()
    {
        return $this->birthTerritoryid;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Birth
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
     * @return Birth
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
     * @return Birth
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
     * @var \UR\DB\NewBundle\Entity\Location
     */
    private $originLocation;

    /**
     * @var \UR\DB\NewBundle\Entity\Country
     */
    private $birthCountry;

    /**
     * @var \UR\DB\NewBundle\Entity\Territory
     */
    private $birthTerritory;

    /**
     * @var \UR\DB\NewBundle\Entity\Location
     */
    private $birthLocation;


    /**
     * Set originCountry
     *
     * @param \UR\DB\NewBundle\Entity\Country $originCountry
     *
     * @return Birth
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
     * @return Birth
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
     * Set originLocation
     *
     * @param \UR\DB\NewBundle\Entity\Location $originLocation
     *
     * @return Birth
     */
    public function setOriginLocation(\UR\DB\NewBundle\Entity\Location $originLocation = null)
    {
        $this->originLocation = $originLocation;

        return $this;
    }

    /**
     * Get originLocation
     *
     * @return \UR\DB\NewBundle\Entity\Location
     */
    public function getOriginLocation()
    {
        return $this->originLocation;
    }

    /**
     * Set birthCountry
     *
     * @param \UR\DB\NewBundle\Entity\Country $birthCountry
     *
     * @return Birth
     */
    public function setBirthCountry(\UR\DB\NewBundle\Entity\Country $birthCountry = null)
    {
        $this->birthCountry = $birthCountry;

        return $this;
    }

    /**
     * Get birthCountry
     *
     * @return \UR\DB\NewBundle\Entity\Country
     */
    public function getBirthCountry()
    {
        return $this->birthCountry;
    }

    /**
     * Set birthTerritory
     *
     * @param \UR\DB\NewBundle\Entity\Territory $birthTerritory
     *
     * @return Birth
     */
    public function setBirthTerritory(\UR\DB\NewBundle\Entity\Territory $birthTerritory = null)
    {
        $this->birthTerritory = $birthTerritory;

        return $this;
    }

    /**
     * Get birthTerritory
     *
     * @return \UR\DB\NewBundle\Entity\Territory
     */
    public function getBirthTerritory()
    {
        return $this->birthTerritory;
    }

    /**
     * Set birthLocation
     *
     * @param \UR\DB\NewBundle\Entity\Location $birthLocation
     *
     * @return Birth
     */
    public function setBirthLocation(\UR\DB\NewBundle\Entity\Location $birthLocation = null)
    {
        $this->birthLocation = $birthLocation;

        return $this;
    }

    /**
     * Get birthLocation
     *
     * @return \UR\DB\NewBundle\Entity\Location
     */
    public function getBirthLocation()
    {
        return $this->birthLocation;
    }
    /**
     * @var date_reference
     * @Type("array<UR\DB\NewBundle\Types\DateReference>")
     */
    
    private $birthDate;


    /**
     * Set birthDate
     *
     * @param date_reference $birthDate
     *
     * @return Birth
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return date_reference
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }
    /**
     * @var date_reference
     * @Type("array<UR\DB\NewBundle\Types\DateReference>")
     */
    private $provenDate;


    /**
     * Set provenDate
     *
     * @param date_reference $provenDate
     *
     * @return Birth
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
