<?php

namespace UR\DB\NewBundle\Entity;

/**
 * Partner
 */
class Partner
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
     * @var string
     */
    private $bornInMarriage;

    /**
     * @var integer
     */
    private $baptismid;

    /**
     * @var integer
     */
    private $worksid;

    /**
     * @var integer
     */
    private $weddingid;

    /**
     * @var integer
     */
    private $statusid;

    /**
     * @var integer
     */
    private $sourceid;

    /**
     * @var integer
     */
    private $roadOfLiveid;

    /**
     * @var integer
     */
    private $rankid;

    /**
     * @var integer
     */
    private $propertyid;

    /**
     * @var integer
     */
    private $honourid;

    /**
     * @var integer
     */
    private $educationid;

    /**
     * @var integer
     */
    private $jobClassid;

    /**
     * @var integer
     */
    private $residenceid;


    public function __toString (){
        return "Partner with ID: ".$this->getId();
    }
    
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
     * @return Partner
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
     * @return Partner
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
     * @return Partner
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
     * @return Partner
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
     * @return Partner
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
     * @return Partner
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
     * @return Partner
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
     * @return Partner
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
     * @return Partner
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
     * @return Partner
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
     * @return Partner
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
     * @return Partner
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
     * Set baptismid
     *
     * @param integer $baptismid
     *
     * @return Partner
     */
    public function setBaptismid($baptismid)
    {
        $this->baptismid = $baptismid;

        return $this;
    }

    /**
     * Get baptismid
     *
     * @return integer
     */
    public function getBaptismid()
    {
        return $this->baptismid;
    }

    /**
     * Set worksid
     *
     * @param integer $worksid
     *
     * @return Partner
     */
    public function setWorksid($worksid)
    {
        $this->worksid = $worksid;

        return $this;
    }

    /**
     * Get worksid
     *
     * @return integer
     */
    public function getWorksid()
    {
        return $this->worksid;
    }

    /**
     * Set weddingid
     *
     * @param integer $weddingid
     *
     * @return Partner
     */
    public function setWeddingid($weddingid)
    {
        $this->weddingid = $weddingid;

        return $this;
    }

    /**
     * Get weddingid
     *
     * @return integer
     */
    public function getWeddingid()
    {
        return $this->weddingid;
    }

    /**
     * Set statusid
     *
     * @param integer $statusid
     *
     * @return Partner
     */
    public function setStatusid($statusid)
    {
        $this->statusid = $statusid;

        return $this;
    }

    /**
     * Get statusid
     *
     * @return integer
     */
    public function getStatusid()
    {
        return $this->statusid;
    }

    /**
     * Set sourceid
     *
     * @param integer $sourceid
     *
     * @return Partner
     */
    public function setSourceid($sourceid)
    {
        $this->sourceid = $sourceid;

        return $this;
    }

    /**
     * Get sourceid
     *
     * @return integer
     */
    public function getSourceid()
    {
        return $this->sourceid;
    }

    /**
     * Set roadOfLiveid
     *
     * @param integer $roadOfLiveid
     *
     * @return Partner
     */
    public function setRoadOfLiveid($roadOfLiveid)
    {
        $this->roadOfLiveid = $roadOfLiveid;

        return $this;
    }

    /**
     * Get roadOfLiveid
     *
     * @return integer
     */
    public function getRoadOfLiveid()
    {
        return $this->roadOfLiveid;
    }

    /**
     * Set rankid
     *
     * @param integer $rankid
     *
     * @return Partner
     */
    public function setRankid($rankid)
    {
        $this->rankid = $rankid;

        return $this;
    }

    /**
     * Get rankid
     *
     * @return integer
     */
    public function getRankid()
    {
        return $this->rankid;
    }

    /**
     * Set propertyid
     *
     * @param integer $propertyid
     *
     * @return Partner
     */
    public function setPropertyid($propertyid)
    {
        $this->propertyid = $propertyid;

        return $this;
    }

    /**
     * Get propertyid
     *
     * @return integer
     */
    public function getPropertyid()
    {
        return $this->propertyid;
    }

    /**
     * Set honourid
     *
     * @param integer $honourid
     *
     * @return Partner
     */
    public function setHonourid($honourid)
    {
        $this->honourid = $honourid;

        return $this;
    }

    /**
     * Get honourid
     *
     * @return integer
     */
    public function getHonourid()
    {
        return $this->honourid;
    }

    /**
     * Set educationid
     *
     * @param integer $educationid
     *
     * @return Partner
     */
    public function setEducationid($educationid)
    {
        $this->educationid = $educationid;

        return $this;
    }

    /**
     * Get educationid
     *
     * @return integer
     */
    public function getEducationid()
    {
        return $this->educationid;
    }

    /**
     * Set jobClassid
     *
     * @param integer $jobClassid
     *
     * @return Partner
     */
    public function setJobClassid($jobClassid)
    {
        $this->jobClassid = $jobClassid;

        return $this;
    }

    /**
     * Get jobClassid
     *
     * @return integer
     */
    public function getJobClassid()
    {
        return $this->jobClassid;
    }

    /**
     * Set residenceid
     *
     * @param integer $residenceid
     *
     * @return Partner
     */
    public function setResidenceid($residenceid)
    {
        $this->residenceid = $residenceid;

        return $this;
    }

    /**
     * Get residenceid
     *
     * @return integer
     */
    public function getResidenceid()
    {
        return $this->residenceid;
    }
    /**
     * @var integer
     */
    private $jobid;


    /**
     * Set jobid
     *
     * @param integer $jobid
     *
     * @return Partner
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
}
