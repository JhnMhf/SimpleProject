<?php

namespace UR\DB\FinalBundle\Entity;

/**
 * Education
 */
class Education
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $educationOrder;

    /**
     * @var string
     */
    private $label;

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
    private $graduationLabel;

    /**
     * @var integer
     */
    private $graduationDateid;

    /**
     * @var integer
     */
    private $graduationLocationid;

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
     * Set educationOrder
     *
     * @param integer $educationOrder
     *
     * @return Education
     */
    public function setEducationOrder($educationOrder)
    {
        $this->educationOrder = $educationOrder;

        return $this;
    }

    /**
     * Get educationOrder
     *
     * @return integer
     */
    public function getEducationOrder()
    {
        return $this->educationOrder;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return Education
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
     * Set countryid
     *
     * @param integer $countryid
     *
     * @return Education
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
     * @return Education
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
     * @return Education
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
     * @return Education
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
     * @return Education
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
     * @return Education
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
     * Set graduationLabel
     *
     * @param string $graduationLabel
     *
     * @return Education
     */
    public function setGraduationLabel($graduationLabel)
    {
        $this->graduationLabel = $graduationLabel;

        return $this;
    }

    /**
     * Get graduationLabel
     *
     * @return string
     */
    public function getGraduationLabel()
    {
        return $this->graduationLabel;
    }

    /**
     * Set graduationDateid
     *
     * @param integer $graduationDateid
     *
     * @return Education
     */
    public function setGraduationDateid($graduationDateid)
    {
        $this->graduationDateid = $graduationDateid;

        return $this;
    }

    /**
     * Get graduationDateid
     *
     * @return integer
     */
    public function getGraduationDateid()
    {
        return $this->graduationDateid;
    }

    /**
     * Set graduationLocationid
     *
     * @param integer $graduationLocationid
     *
     * @return Education
     */
    public function setGraduationLocationid($graduationLocationid)
    {
        $this->graduationLocationid = $graduationLocationid;

        return $this;
    }

    /**
     * Get graduationLocationid
     *
     * @return integer
     */
    public function getGraduationLocationid()
    {
        return $this->graduationLocationid;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Education
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
