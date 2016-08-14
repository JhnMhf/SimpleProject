<?php

namespace UR\DB\NewBundle\Entity;

use JMS\Serializer\Annotation\Type;

/**
 * Death
 */
class Death
{
    public function __toString (){
        return "Death with ID: ".$this->getId();
    }
    
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $deathLocationid;

    /**
     * @var integer
     */
    private $deathCountryid;

    /**
     * @var string
     */
    private $causeOfDeath;

    /**
     * @var integer
     */
    private $territoryOfDeathid;

    /**
     * @var string
     */
    private $graveyard;

    /**
     * @var integer
     */
    private $funeralLocationid;

    /**
     * @var string
     */
    private $comment;

    /**
     * @var string
     */
    private $deathDateid;

    /**
     * @var string
     */
    private $funeralDateid;


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
     * Set deathLocationid
     *
     * @param integer $deathLocationid
     *
     * @return Death
     */
    public function setDeathLocationid($deathLocationid)
    {
        $this->deathLocationid = $deathLocationid;

        return $this;
    }

    /**
     * Get deathLocationid
     *
     * @return integer
     */
    public function getDeathLocationid()
    {
        return $this->deathLocationid;
    }

    /**
     * Set deathCountryid
     *
     * @param integer $deathCountryid
     *
     * @return Death
     */
    public function setDeathCountryid($deathCountryid)
    {
        $this->deathCountryid = $deathCountryid;

        return $this;
    }

    /**
     * Get deathCountryid
     *
     * @return integer
     */
    public function getDeathCountryid()
    {
        return $this->deathCountryid;
    }

    /**
     * Set causeOfDeath
     *
     * @param string $causeOfDeath
     *
     * @return Death
     */
    public function setCauseOfDeath($causeOfDeath)
    {
        $this->causeOfDeath = $causeOfDeath;

        return $this;
    }

    /**
     * Get causeOfDeath
     *
     * @return string
     */
    public function getCauseOfDeath()
    {
        return $this->causeOfDeath;
    }

    /**
     * Set territoryOfDeathid
     *
     * @param integer $territoryOfDeathid
     *
     * @return Death
     */
    public function setTerritoryOfDeathid($territoryOfDeathid)
    {
        $this->territoryOfDeathid = $territoryOfDeathid;

        return $this;
    }

    /**
     * Get territoryOfDeathid
     *
     * @return integer
     */
    public function getTerritoryOfDeathid()
    {
        return $this->territoryOfDeathid;
    }

    /**
     * Set graveyard
     *
     * @param string $graveyard
     *
     * @return Death
     */
    public function setGraveyard($graveyard)
    {
        $this->graveyard = $graveyard;

        return $this;
    }

    /**
     * Get graveyard
     *
     * @return string
     */
    public function getGraveyard()
    {
        return $this->graveyard;
    }

    /**
     * Set funeralLocationid
     *
     * @param integer $funeralLocationid
     *
     * @return Death
     */
    public function setFuneralLocationid($funeralLocationid)
    {
        $this->funeralLocationid = $funeralLocationid;

        return $this;
    }

    /**
     * Get funeralLocationid
     *
     * @return integer
     */
    public function getFuneralLocationid()
    {
        return $this->funeralLocationid;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Death
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
     * Set deathDateid
     *
     * @param string $deathDateid
     *
     * @return Death
     */
    public function setDeathDateid($deathDateid)
    {
        $this->deathDateid = $deathDateid;

        return $this;
    }

    /**
     * Get deathDateid
     *
     * @return string
     */
    public function getDeathDateid()
    {
        return $this->deathDateid;
    }

    /**
     * Set funeralDateid
     *
     * @param string $funeralDateid
     *
     * @return Death
     */
    public function setFuneralDateid($funeralDateid)
    {
        $this->funeralDateid = $funeralDateid;

        return $this;
    }

    /**
     * Get funeralDateid
     *
     * @return string
     */
    public function getFuneralDateid()
    {
        return $this->funeralDateid;
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
     * @return Death
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
     * @return Death
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
    private $deathCountry;

    /**
     * @var \UR\DB\NewBundle\Entity\Territory
     */
    private $territoryOfDeath;

    /**
     * @var \UR\DB\NewBundle\Entity\Location
     */
    private $deathLocation;

    /**
     * @var \UR\DB\NewBundle\Entity\Location
     */
    private $funeralLocation;


    /**
     * Set deathCountry
     *
     * @param \UR\DB\NewBundle\Entity\Country $deathCountry
     *
     * @return Death
     */
    public function setDeathCountry(\UR\DB\NewBundle\Entity\Country $deathCountry = null)
    {
        $this->deathCountry = $deathCountry;

        return $this;
    }

    /**
     * Get deathCountry
     *
     * @return \UR\DB\NewBundle\Entity\Country
     */
    public function getDeathCountry()
    {
        return $this->deathCountry;
    }

    /**
     * Set territoryOfDeath
     *
     * @param \UR\DB\NewBundle\Entity\Territory $territoryOfDeath
     *
     * @return Death
     */
    public function setTerritoryOfDeath(\UR\DB\NewBundle\Entity\Territory $territoryOfDeath = null)
    {
        $this->territoryOfDeath = $territoryOfDeath;

        return $this;
    }

    /**
     * Get territoryOfDeath
     *
     * @return \UR\DB\NewBundle\Entity\Territory
     */
    public function getTerritoryOfDeath()
    {
        return $this->territoryOfDeath;
    }

    /**
     * Set deathLocation
     *
     * @param \UR\DB\NewBundle\Entity\Location $deathLocation
     *
     * @return Death
     */
    public function setDeathLocation(\UR\DB\NewBundle\Entity\Location $deathLocation = null)
    {
        $this->deathLocation = $deathLocation;

        return $this;
    }

    /**
     * Get deathLocation
     *
     * @return \UR\DB\NewBundle\Entity\Location
     */
    public function getDeathLocation()
    {
        return $this->deathLocation;
    }

    /**
     * Set funeralLocation
     *
     * @param \UR\DB\NewBundle\Entity\Location $funeralLocation
     *
     * @return Death
     */
    public function setFuneralLocation(\UR\DB\NewBundle\Entity\Location $funeralLocation = null)
    {
        $this->funeralLocation = $funeralLocation;

        return $this;
    }

    /**
     * Get funeralLocation
     *
     * @return \UR\DB\NewBundle\Entity\Location
     */
    public function getFuneralLocation()
    {
        return $this->funeralLocation;
    }
    /**
     * @var date_reference
     * @Type("array<UR\DB\NewBundle\Types\DateReference>")
     */
    private $deathDate;

    /**
     * @var date_reference
     * @Type("array<UR\DB\NewBundle\Types\DateReference>")
     */
    private $funeralDate;


    /**
     * Set deathDate
     *
     * @param date_reference $deathDate
     *
     * @return Death
     */
    public function setDeathDate($deathDate)
    {
        $this->deathDate = $deathDate;

        return $this;
    }

    /**
     * Get deathDate
     *
     * @return date_reference
     */
    public function getDeathDate()
    {
        return $this->deathDate;
    }

    /**
     * Set funeralDate
     *
     * @param date_reference $funeralDate
     *
     * @return Death
     */
    public function setFuneralDate($funeralDate)
    {
        $this->funeralDate = $funeralDate;

        return $this;
    }

    /**
     * Get funeralDate
     *
     * @return date_reference
     */
    public function getFuneralDate()
    {
        return $this->funeralDate;
    }
}
