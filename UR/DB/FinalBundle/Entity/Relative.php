<?php

namespace UR\DB\FinalBundle\Entity;

/**
 * Relative
 */
class Relative
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $patronym;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var integer
     */
    private $foreName;

    /**
     * @var integer
     */
    private $birthName;

    /**
     * @var integer
     */
    private $gender = '0';

    /**
     * @var integer
     */
    private $birthid;

    /**
     * @var integer
     */
    private $deathid;

    /**
     * @var integer
     */
    private $religionid;

    /**
     * @var integer
     */
    private $originalNationid;

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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Relative
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set patronym
     *
     * @param string $patronym
     *
     * @return Relative
     */
    public function setPatronym($patronym)
    {
        $this->patronym = $patronym;

        return $this;
    }

    /**
     * Get patronym
     *
     * @return string
     */
    public function getPatronym()
    {
        return $this->patronym;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Relative
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set foreName
     *
     * @param integer $foreName
     *
     * @return Relative
     */
    public function setForeName($foreName)
    {
        $this->foreName = $foreName;

        return $this;
    }

    /**
     * Get foreName
     *
     * @return integer
     */
    public function getForeName()
    {
        return $this->foreName;
    }

    /**
     * Set birthName
     *
     * @param integer $birthName
     *
     * @return Relative
     */
    public function setBirthName($birthName)
    {
        $this->birthName = $birthName;

        return $this;
    }

    /**
     * Get birthName
     *
     * @return integer
     */
    public function getBirthName()
    {
        return $this->birthName;
    }

    /**
     * Set gender
     *
     * @param integer $gender
     *
     * @return Relative
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return integer
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set birthid
     *
     * @param integer $birthid
     *
     * @return Relative
     */
    public function setBirthid($birthid)
    {
        $this->birthid = $birthid;

        return $this;
    }

    /**
     * Get birthid
     *
     * @return integer
     */
    public function getBirthid()
    {
        return $this->birthid;
    }

    /**
     * Set deathid
     *
     * @param integer $deathid
     *
     * @return Relative
     */
    public function setDeathid($deathid)
    {
        $this->deathid = $deathid;

        return $this;
    }

    /**
     * Get deathid
     *
     * @return integer
     */
    public function getDeathid()
    {
        return $this->deathid;
    }

    /**
     * Set religionid
     *
     * @param integer $religionid
     *
     * @return Relative
     */
    public function setReligionid($religionid)
    {
        $this->religionid = $religionid;

        return $this;
    }

    /**
     * Get religionid
     *
     * @return integer
     */
    public function getReligionid()
    {
        return $this->religionid;
    }

    /**
     * Set originalNationid
     *
     * @param integer $originalNationid
     *
     * @return Relative
     */
    public function setOriginalNationid($originalNationid)
    {
        $this->originalNationid = $originalNationid;

        return $this;
    }

    /**
     * Get originalNationid
     *
     * @return integer
     */
    public function getOriginalNationid()
    {
        return $this->originalNationid;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Relative
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
}
