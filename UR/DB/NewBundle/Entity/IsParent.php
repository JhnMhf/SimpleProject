<?php

namespace UR\DB\NewBundle\Entity;

/**
 * IsParent
 */
class IsParent
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $childID = '0';

    /**
     * @var integer
     */
    private $parentid = '0';

    /**
     * @var string
     */
    private $relationType;

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
     * Set childID
     *
     * @param integer $childID
     *
     * @return IsParent
     */
    public function setChildID($childID)
    {
        $this->childID = $childID;

        return $this;
    }

    /**
     * Get childID
     *
     * @return integer
     */
    public function getChildID()
    {
        return $this->childID;
    }

    /**
     * Set parentid
     *
     * @param integer $parentid
     *
     * @return IsParent
     */
    public function setParentid($parentid)
    {
        $this->parentid = $parentid;

        return $this;
    }

    /**
     * Get parentid
     *
     * @return integer
     */
    public function getParentid()
    {
        return $this->parentid;
    }

    /**
     * Set relationType
     *
     * @param string $relationType
     *
     * @return IsParent
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
     * @return IsParent
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

