<?php

namespace UR\DB\NewBundle\Entity;

/**
 * IsInRelationshipWith
 */
class IsInRelationshipWith
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $firstPartnerID = '0';

    /**
     * @var integer
     */
    private $secondPartnerID = '0';

    /**
     * @var string
     */
    private $relationType;

    /**
     * @var string
     */
    private $comment;


    /**
     * Set id
     *
     * @param integer $id
     *
     * @return IsInRelationshipWith
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set firstPartnerID
     *
     * @param integer $firstPartnerID
     *
     * @return IsInRelationshipWith
     */
    public function setFirstPartnerID($firstPartnerID)
    {
        $this->firstPartnerID = $firstPartnerID;

        return $this;
    }

    /**
     * Get firstPartnerID
     *
     * @return integer
     */
    public function getFirstPartnerID()
    {
        return $this->firstPartnerID;
    }

    /**
     * Set secondPartnerID
     *
     * @param integer $secondPartnerID
     *
     * @return IsInRelationshipWith
     */
    public function setSecondPartnerID($secondPartnerID)
    {
        $this->secondPartnerID = $secondPartnerID;

        return $this;
    }

    /**
     * Get secondPartnerID
     *
     * @return integer
     */
    public function getSecondPartnerID()
    {
        return $this->secondPartnerID;
    }

    /**
     * Set relationType
     *
     * @param string $relationType
     *
     * @return IsInRelationshipWith
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
     * Set comment
     *
     * @param string $comment
     *
     * @return IsInRelationshipWith
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
