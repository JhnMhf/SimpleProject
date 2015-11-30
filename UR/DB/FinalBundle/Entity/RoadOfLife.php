<?php

namespace UR\DB\FinalBundle\Entity;

/**
 * RoadOfLife
 */
class RoadOfLife
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $roadOfLifeOrder;

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
     * @param integer $roadOfLifeOrder
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
     * @return integer
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
}
