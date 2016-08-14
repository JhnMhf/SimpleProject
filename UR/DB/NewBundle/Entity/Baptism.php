<?php

namespace UR\DB\NewBundle\Entity;

use JMS\Serializer\Annotation\Type;

/**
 * Baptism
 */
class Baptism
{
    public function __toString (){
        return "Baptism with ID: ".$this->getId();
    }
    
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $baptismDateid;

    /**
     * @var integer
     */
    private $baptismLocationid;


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
     * Set baptismDateid
     *
     * @param string $baptismDateid
     *
     * @return Baptism
     */
    public function setBaptismDateid($baptismDateid)
    {
        $this->baptismDateid = $baptismDateid;

        return $this;
    }

    /**
     * Get baptismDateid
     *
     * @return string
     */
    public function getBaptismDateid()
    {
        return $this->baptismDateid;
    }

    /**
     * Set baptismLocationid
     *
     * @param integer $baptismLocationid
     *
     * @return Baptism
     */
    public function setBaptismLocationid($baptismLocationid)
    {
        $this->baptismLocationid = $baptismLocationid;

        return $this;
    }

    /**
     * Get baptismLocationid
     *
     * @return integer
     */
    public function getBaptismLocationid()
    {
        return $this->baptismLocationid;
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
     * @return Baptism
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
     * @return Baptism
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
     * @var \UR\DB\NewBundle\Entity\Location
     */
    private $baptismLocation;


    /**
     * Set baptismLocation
     *
     * @param \UR\DB\NewBundle\Entity\Location $baptismLocation
     *
     * @return Baptism
     */
    public function setBaptismLocation(\UR\DB\NewBundle\Entity\Location $baptismLocation = null)
    {
        $this->baptismLocation = $baptismLocation;

        return $this;
    }

    /**
     * Get baptismLocation
     *
     * @return \UR\DB\NewBundle\Entity\Location
     */
    public function getBaptismLocation()
    {
        return $this->baptismLocation;
    }
    /**
     * @var date_reference
     * @Type("array<UR\DB\NewBundle\Types\DateReference>")
     */
    private $baptismDate;


    /**
     * Set baptismDate
     *
     * @param date_reference $baptismDate
     *
     * @return Baptism
     */
    public function setBaptismDate($baptismDate)
    {
        $this->baptismDate = $baptismDate;

        return $this;
    }

    /**
     * Get baptismDate
     *
     * @return date_reference
     */
    public function getBaptismDate()
    {
        return $this->baptismDate;
    }
}
