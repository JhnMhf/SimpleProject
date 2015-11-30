<?php

namespace UR\DB\FinalBundle\Entity;

/**
 * Wedding
 */
class Wedding
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $weddingOrder;

    /**
     * @var integer
     */
    private $husbandId;

    /**
     * @var integer
     */
    private $wifeId;

    /**
     * @var string
     */
    private $relationType;

    /**
     * @var integer
     */
    private $weddingDateid;

    /**
     * @var integer
     */
    private $weddingLocationid;

    /**
     * @var integer
     */
    private $weddingTerritoryid;

    /**
     * @var integer
     */
    private $bannsDateid;

    /**
     * @var string
     */
    private $breakupReason;

    /**
     * @var integer
     */
    private $breakupDateid;

    /**
     * @var string
     */
    private $marriageComment;

    /**
     * @var string
     */
    private $beforeAfter;

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
     * Set weddingOrder
     *
     * @param integer $weddingOrder
     *
     * @return Wedding
     */
    public function setWeddingOrder($weddingOrder)
    {
        $this->weddingOrder = $weddingOrder;

        return $this;
    }

    /**
     * Get weddingOrder
     *
     * @return integer
     */
    public function getWeddingOrder()
    {
        return $this->weddingOrder;
    }

    /**
     * Set husbandId
     *
     * @param integer $husbandId
     *
     * @return Wedding
     */
    public function setHusbandId($husbandId)
    {
        $this->husbandId = $husbandId;

        return $this;
    }

    /**
     * Get husbandId
     *
     * @return integer
     */
    public function getHusbandId()
    {
        return $this->husbandId;
    }

    /**
     * Set wifeId
     *
     * @param integer $wifeId
     *
     * @return Wedding
     */
    public function setWifeId($wifeId)
    {
        $this->wifeId = $wifeId;

        return $this;
    }

    /**
     * Get wifeId
     *
     * @return integer
     */
    public function getWifeId()
    {
        return $this->wifeId;
    }

    /**
     * Set relationType
     *
     * @param string $relationType
     *
     * @return Wedding
     */
    public function setRelationType($relationType)
    {
        $this->relationType = $relationType;

        return $this;
    }

    /**
     * Get relationType
     *
     * @return string
     */
    public function getRelationType()
    {
        return $this->relationType;
    }

    /**
     * Set weddingDateid
     *
     * @param integer $weddingDateid
     *
     * @return Wedding
     */
    public function setWeddingDateid($weddingDateid)
    {
        $this->weddingDateid = $weddingDateid;

        return $this;
    }

    /**
     * Get weddingDateid
     *
     * @return integer
     */
    public function getWeddingDateid()
    {
        return $this->weddingDateid;
    }

    /**
     * Set weddingLocationid
     *
     * @param integer $weddingLocationid
     *
     * @return Wedding
     */
    public function setWeddingLocationid($weddingLocationid)
    {
        $this->weddingLocationid = $weddingLocationid;

        return $this;
    }

    /**
     * Get weddingLocationid
     *
     * @return integer
     */
    public function getWeddingLocationid()
    {
        return $this->weddingLocationid;
    }

    /**
     * Set weddingTerritoryid
     *
     * @param integer $weddingTerritoryid
     *
     * @return Wedding
     */
    public function setWeddingTerritoryid($weddingTerritoryid)
    {
        $this->weddingTerritoryid = $weddingTerritoryid;

        return $this;
    }

    /**
     * Get weddingTerritoryid
     *
     * @return integer
     */
    public function getWeddingTerritoryid()
    {
        return $this->weddingTerritoryid;
    }

    /**
     * Set bannsDateid
     *
     * @param integer $bannsDateid
     *
     * @return Wedding
     */
    public function setBannsDateid($bannsDateid)
    {
        $this->bannsDateid = $bannsDateid;

        return $this;
    }

    /**
     * Get bannsDateid
     *
     * @return integer
     */
    public function getBannsDateid()
    {
        return $this->bannsDateid;
    }

    /**
     * Set breakupReason
     *
     * @param string $breakupReason
     *
     * @return Wedding
     */
    public function setBreakupReason($breakupReason)
    {
        $this->breakupReason = $breakupReason;

        return $this;
    }

    /**
     * Get breakupReason
     *
     * @return string
     */
    public function getBreakupReason()
    {
        return $this->breakupReason;
    }

    /**
     * Set breakupDateid
     *
     * @param integer $breakupDateid
     *
     * @return Wedding
     */
    public function setBreakupDateid($breakupDateid)
    {
        $this->breakupDateid = $breakupDateid;

        return $this;
    }

    /**
     * Get breakupDateid
     *
     * @return integer
     */
    public function getBreakupDateid()
    {
        return $this->breakupDateid;
    }

    /**
     * Set marriageComment
     *
     * @param string $marriageComment
     *
     * @return Wedding
     */
    public function setMarriageComment($marriageComment)
    {
        $this->marriageComment = $marriageComment;

        return $this;
    }

    /**
     * Get marriageComment
     *
     * @return string
     */
    public function getMarriageComment()
    {
        return $this->marriageComment;
    }

    /**
     * Set beforeAfter
     *
     * @param string $beforeAfter
     *
     * @return Wedding
     */
    public function setBeforeAfter($beforeAfter)
    {
        $this->beforeAfter = $beforeAfter;

        return $this;
    }

    /**
     * Get beforeAfter
     *
     * @return string
     */
    public function getBeforeAfter()
    {
        return $this->beforeAfter;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Wedding
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
