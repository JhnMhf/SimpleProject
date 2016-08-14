<?php

namespace UR\DB\NewBundle\Entity;

/**
 * IsGrandparent
 */
class IsGrandparent
{
    public function __toString (){
        return get_class($this);
    }
    
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $grandParentID = '0';

    /**
     * @var integer
     */
    private $grandChildID = '0';

    /**
     * @var string
     */
    private $relationType;

    /**
     * @var boolean
     */
    private $isPaternal;

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
     * Set grandParentID
     *
     * @param integer $grandParentID
     *
     * @return IsGrandparent
     */
    public function setGrandParentID($grandParentID)
    {
        $this->grandParentID = $grandParentID;

        return $this;
    }

    /**
     * Get grandParentID
     *
     * @return integer
     */
    public function getGrandParentID()
    {
        return $this->grandParentID;
    }

    /**
     * Set grandChildID
     *
     * @param integer $grandChildID
     *
     * @return IsGrandparent
     */
    public function setGrandChildID($grandChildID)
    {
        $this->grandChildID = $grandChildID;

        return $this;
    }

    /**
     * Get grandChildID
     *
     * @return integer
     */
    public function getGrandChildID()
    {
        return $this->grandChildID;
    }

    /**
     * Set relationType
     *
     * @param string $relationType
     *
     * @return IsGrandparent
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
     * Set isPaternal
     *
     * @param boolean $isPaternal
     *
     * @return IsGrandparent
     */
    public function setIsPaternal($isPaternal)
    {
        $this->isPaternal = $isPaternal;

        return $this;
    }

    /**
     * Get isPaternal
     *
     * @return boolean
     */
    public function getIsPaternal()
    {
        return $this->isPaternal;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return IsGrandparent
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
