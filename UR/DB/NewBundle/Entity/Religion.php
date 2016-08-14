<?php

namespace UR\DB\NewBundle\Entity;

use JMS\Serializer\Annotation\Type;

/**
 * Religion
 */
class Religion {

    public function __toString() {
        return "Religion with ID: " . $this->getId();
    }

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
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Religion
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Religion
     */
    public function setComment($comment) {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment() {
        return $this->comment;
    }

    /**
     * Set religionOrder
     *
     * @param boolean $religionOrder
     *
     * @return Religion
     */
    public function setReligionOrder($religionOrder) {
        $this->religionOrder = $religionOrder;

        return $this;
    }

    /**
     * Get religionOrder
     *
     * @return boolean
     */
    public function getReligionOrder() {
        return $this->religionOrder;
    }

    /**
     * Set provenDateid
     *
     * @param string $provenDateid
     *
     * @return Religion
     */
    public function setProvenDateid($provenDateid) {
        $this->provenDateid = $provenDateid;

        return $this;
    }

    /**
     * Get provenDateid
     *
     * @return string
     */
    public function getProvenDateid() {
        return $this->provenDateid;
    }

    /**
     * Set fromDateid
     *
     * @param string $fromDateid
     *
     * @return Religion
     */
    public function setFromDateid($fromDateid) {
        $this->fromDateid = $fromDateid;

        return $this;
    }

    /**
     * Get fromDateid
     *
     * @return string
     */
    public function getFromDateid() {
        return $this->fromDateid;
    }

    /**
     * Set changeOfReligion
     *
     * @param string $changeOfReligion
     *
     * @return Religion
     */
    public function setChangeOfReligion($changeOfReligion) {
        $this->changeOfReligion = $changeOfReligion;

        return $this;
    }

    /**
     * Get changeOfReligion
     *
     * @return string
     */
    public function getChangeOfReligion() {
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
    public function setPersonID($personID) {
        $this->personID = $personID;

        return $this;
    }

    /**
     * Get personID
     *
     * @return integer
     */
    public function getPersonID() {
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
     * @return Religion
     */
    public function setPerson(\UR\DB\NewBundle\Entity\BasePerson $person = null) {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \UR\DB\NewBundle\Entity\BasePerson
     */
    public function getPerson() {
        return $this->person;
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
     * Set fromDate
     *
     * @param date_reference $fromDate
     *
     * @return Religion
     */
    public function setFromDate($fromDate) {
        $this->fromDate = $fromDate;

        return $this;
    }

    /**
     * Get fromDate
     *
     * @return date_reference
     */
    public function getFromDate() {
        return $this->fromDate;
    }

    /**
     * Set toDate
     *
     * @param date_reference $toDate
     *
     * @return Religion
     */
    public function setToDate($toDate) {
        $this->toDate = $toDate;

        return $this;
    }

    /**
     * Get toDate
     *
     * @return date_reference
     */
    public function getToDate() {
        return $this->toDate;
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
     * @return Religion
     */
    public function setProvenDate($provenDate) {
        $this->provenDate = $provenDate;

        return $this;
    }

    /**
     * Get provenDate
     *
     * @return date_reference
     */
    public function getProvenDate() {
        return $this->provenDate;
    }

}
