<?php

namespace UR\DB\NewBundle\Entity;

/**
 * BasePerson
 */
class BasePerson
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
     * @var string
     */
    private $foreName;

    /**
     * @var string
     */
    private $birthName;

    /**
     * @var integer
     */
    private $gender = '0';

    /**
     * @var string
     */
    private $comment;

    /**
     * @var string
     */
    private $bornInMarriage;


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
     * @return BasePerson
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
     * @return BasePerson
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
     * @return BasePerson
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
     * @param string $foreName
     *
     * @return BasePerson
     */
    public function setForeName($foreName)
    {
        $this->foreName = $foreName;

        return $this;
    }

    /**
     * Get foreName
     *
     * @return string
     */
    public function getForeName()
    {
        return $this->foreName;
    }

    /**
     * Set birthName
     *
     * @param string $birthName
     *
     * @return BasePerson
     */
    public function setBirthName($birthName)
    {
        $this->birthName = $birthName;

        return $this;
    }

    /**
     * Get birthName
     *
     * @return string
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
     * @return BasePerson
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
     * Set comment
     *
     * @param string $comment
     *
     * @return BasePerson
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
     * Set bornInMarriage
     *
     * @param string $bornInMarriage
     *
     * @return BasePerson
     */
    public function setBornInMarriage($bornInMarriage)
    {
        $this->bornInMarriage = $bornInMarriage;

        return $this;
    }

    /**
     * Get bornInMarriage
     *
     * @return string
     */
    public function getBornInMarriage()
    {
        return $this->bornInMarriage;
    }

    /**
     * @var \UR\DB\NewBundle\Entity\Job
     */
    private $job;

    /**
     * @var \UR\DB\NewBundle\Entity\JobClass
     */
    private $jobClass;


    /**
     * Set job
     *
     * @param \UR\DB\NewBundle\Entity\Job $job
     *
     * @return BasePerson
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
     * Set jobClass
     *
     * @param \UR\DB\NewBundle\Entity\JobClass $jobClass
     *
     * @return BasePerson
     */
    public function setJobClass(\UR\DB\NewBundle\Entity\JobClass $jobClass = null)
    {
        $this->jobClass = $jobClass;

        return $this;
    }

    /**
     * Get jobClass
     *
     * @return \UR\DB\NewBundle\Entity\JobClass
     */
    public function getJobClass()
    {
        return $this->jobClass;
    }
    /**
     * @var string
     */
    private $jobClassid;

    /**
     * @var string
     */
    private $jobid;


    /**
     * Set jobClassid
     *
     * @param string $jobClassid
     *
     * @return BasePerson
     */
    public function setJobClassid($jobClassid)
    {
        $this->jobClassid = $jobClassid;

        return $this;
    }

    /**
     * Get jobClassid
     *
     * @return string
     */
    public function getJobClassid()
    {
        return $this->jobClassid;
    }

    /**
     * Set jobid
     *
     * @param string $jobid
     *
     * @return BasePerson
     */
    public function setJobid($jobid)
    {
        $this->jobid = $jobid;

        return $this;
    }

    /**
     * Get jobid
     *
     * @return string
     */
    public function getJobid()
    {
        return $this->jobid;
    }
}
