<?php

namespace UR\DB\FinalBundle\Entity;

/**
 * IsGrandparent
 */
class IsGrandparent
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $grandParentid;

    /**
     * @var integer
     */
    private $grandChildid;

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
     * Set grandParentid
     *
     * @param integer $grandParentid
     *
     * @return IsGrandparent
     */
    public function setGrandParentid($grandParentid)
    {
        $this->grandParentid = $grandParentid;

        return $this;
    }

    /**
     * Get grandParentid
     *
     * @return integer
     */
    public function getGrandParentid()
    {
        return $this->grandParentid;
    }

    /**
     * Set grandChildid
     *
     * @param integer $grandChildid
     *
     * @return IsGrandparent
     */
    public function setGrandChildid($grandChildid)
    {
        $this->grandChildid = $grandChildid;

        return $this;
    }

    /**
     * Get grandChildid
     *
     * @return integer
     */
    public function getGrandChildid()
    {
        return $this->grandChildid;
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
