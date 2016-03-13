<?php

namespace UR\DB\NewBundle\Entity;

/**
 * Religion
 */
class Religion
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $comment;

    /**
     * @var boolean
     */
    private $religionOrder = '1';

    /**
     * @var string
     */
    private $provenDateid;

    /**
     * @var string
     */
    private $fromDateid;

    /**
     * @var string
     */
    private $changeOfReligion;


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
     * Set name
     *
     * @param string $name
     *
     * @return Religion
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Religion
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
     * Set religionOrder
     *
     * @param boolean $religionOrder
     *
     * @return Religion
     */
    public function setReligionOrder($religionOrder)
    {
        $this->religionOrder = $religionOrder;

        return $this;
    }

    /**
     * Get religionOrder
     *
     * @return boolean
     */
    public function getReligionOrder()
    {
        return $this->religionOrder;
    }

    /**
     * Set provenDateid
     *
     * @param string $provenDateid
     *
     * @return Religion
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
     * Set fromDateid
     *
     * @param string $fromDateid
     *
     * @return Religion
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
     * Set changeOfReligion
     *
     * @param string $changeOfReligion
     *
     * @return Religion
     */
    public function setChangeOfReligion($changeOfReligion)
    {
        $this->changeOfReligion = $changeOfReligion;

        return $this;
    }

    /**
     * Get changeOfReligion
     *
     * @return string
     */
    public function getChangeOfReligion()
    {
        return $this->changeOfReligion;
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
     * @return Religion
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
