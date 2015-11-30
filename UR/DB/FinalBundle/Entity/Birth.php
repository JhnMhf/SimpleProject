<?php

namespace UR\DB\FinalBundle\Entity;

/**
 * Birth
 */
class Birth
{
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
     * @var integer
     */
    private $birthDateid;

    /**
     * @var integer
     */
    private $birthTerritoryid;

    /**
     * @var integer
     */
    private $baptismDateid;

    /**
     * @var integer
     */
    private $baptismLocationid;

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
     * @param integer $birthDateid
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
     * @return integer
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
     * Set baptismDateid
     *
     * @param integer $baptismDateid
     *
     * @return Birth
     */
    public function setBaptismDateid($baptismDateid)
    {
        $this->baptismDateid = $baptismDateid;

        return $this;
    }

    /**
     * Get baptismDateid
     *
     * @return integer
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
     * @return Birth
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
}
