<?php

namespace UR\DB\NewBundle\Entity;

use JMS\Serializer\Annotation\Type;

/**
 * Wedding
 */
class Wedding
{
    public function __toString (){
        return "Wedding with ID '".$this->getId(). "' between '".$this->getHusbandId(). "' and '".$this->getWifeId()."'";
    }
    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $weddingOrder = '1';

    /**
     * @var integer
     */
    private $husbandId = '0';

    /**
     * @var integer
     */
    private $wifeId = '0';

    /**
     * @var integer
     */
    private $weddingLocationid;

    /**
     * @var integer
     */
    private $weddingTerritoryid;

    /**
     * @var string
     */
    private $breakupReason;

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
     * @param boolean $weddingOrder
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
     * @return boolean
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
    /**
     * @var date_reference
     * @Type("array<UR\DB\NewBundle\Types\DateReference>")
     */
    private $weddingDate;

    /**
     * @var date_reference
     * @Type("array<UR\DB\NewBundle\Types\DateReference>")
     */
    private $bannsDate;

    /**
     * @var \UR\DB\NewBundle\Entity\Territory
     */
    private $weddingTerritory;

    /**
     * @var \UR\DB\NewBundle\Entity\Location
     */
    private $weddingLocation;


    /**
     * Set weddingDate
     *
     * @param date_reference $weddingDate
     *
     * @return Wedding
     */
    public function setWeddingDate($weddingDate)
    {
        $this->weddingDate = $weddingDate;

        return $this;
    }

    /**
     * Get weddingDate
     *
     * @return date_reference
     */
    public function getWeddingDate()
    {
        return $this->weddingDate;
    }

    /**
     * Set bannsDate
     *
     * @param date_reference $bannsDate
     *
     * @return Wedding
     */
    public function setBannsDate($bannsDate)
    {
        $this->bannsDate = $bannsDate;

        return $this;
    }

    /**
     * Get bannsDate
     *
     * @return date_reference
     */
    public function getBannsDate()
    {
        return $this->bannsDate;
    }

    /**
     * Set weddingTerritory
     *
     * @param \UR\DB\NewBundle\Entity\Territory $weddingTerritory
     *
     * @return Wedding
     */
    public function setWeddingTerritory(\UR\DB\NewBundle\Entity\Territory $weddingTerritory = null)
    {
        $this->weddingTerritory = $weddingTerritory;

        return $this;
    }

    /**
     * Get weddingTerritory
     *
     * @return \UR\DB\NewBundle\Entity\Territory
     */
    public function getWeddingTerritory()
    {
        return $this->weddingTerritory;
    }

    /**
     * Set weddingLocation
     *
     * @param \UR\DB\NewBundle\Entity\Location $weddingLocation
     *
     * @return Wedding
     */
    public function setWeddingLocation(\UR\DB\NewBundle\Entity\Location $weddingLocation = null)
    {
        $this->weddingLocation = $weddingLocation;

        return $this;
    }

    /**
     * Get weddingLocation
     *
     * @return \UR\DB\NewBundle\Entity\Location
     */
    public function getWeddingLocation()
    {
        return $this->weddingLocation;
    }
   
    /**
     * @var date_reference
     * @Type("array<UR\DB\NewBundle\Types\DateReference>")
     */
    private $breakupDate;


    /**
     * Set breakupDate
     *
     * @param date_reference $breakupDate
     *
     * @return Wedding
     */
    public function setBreakupDate($breakupDate)
    {
        $this->breakupDate = $breakupDate;

        return $this;
    }

    /**
     * Get breakupDate
     *
     * @return date_reference
     */
    public function getBreakupDate()
    {
        return $this->breakupDate;
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
     * @return Wedding
     */
    public function setProvenDate($provenDate)
    {
        $this->provenDate = $provenDate;

        return $this;
    }

    /**
     * Get provenDate
     *
     * @return date_reference
     */
    public function getProvenDate()
    {
        return $this->provenDate;
    }
}
