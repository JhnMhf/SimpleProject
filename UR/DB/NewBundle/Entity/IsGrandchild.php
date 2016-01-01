<?php

namespace UR\DB\NewBundle\Entity;

/**
 * IsGrandchild
 */
class IsGrandchild
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $grandParentid = '0';

    /**
     * @var integer
     */
    private $grandChildid = '0';

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
     * @return IsGrandchild
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
     * Set grandParentid
     *
     * @param integer $grandParentid
     *
     * @return IsGrandchild
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
     * @return IsGrandchild
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
     * @return IsGrandchild
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
     * @return IsGrandchild
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
