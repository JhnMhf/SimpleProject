<?php

namespace UR\DB\FinalBundle\Entity;

/**
 * Rank
 */
class Rank
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $rankOrder;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $class;

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
     * Set rankOrder
     *
     * @param integer $rankOrder
     *
     * @return Rank
     */
    public function setRankOrder($rankOrder)
    {
        $this->rankOrder = $rankOrder;

        return $this;
    }

    /**
     * Get rankOrder
     *
     * @return integer
     */
    public function getRankOrder()
    {
        return $this->rankOrder;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return Rank
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set class
     *
     * @param string $class
     *
     * @return Rank
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set countryid
     *
     * @param integer $countryid
     *
     * @return Rank
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
     * @return Rank
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
     * @return Rank
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
     * @return Rank
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
     * @return Rank
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
     * @return Rank
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
     * @return Rank
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
