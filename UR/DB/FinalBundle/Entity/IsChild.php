<?php

namespace UR\DB\FinalBundle\Entity;

/**
 * IsChild
 */
class IsChild
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $childid;

    /**
     * @var integer
     */
    private $parentid;

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
     * Set childid
     *
     * @param integer $childid
     *
     * @return IsChild
     */
    public function setChildid($childid)
    {
        $this->childid = $childid;

        return $this;
    }

    /**
     * Get childid
     *
     * @return integer
     */
    public function getChildid()
    {
        return $this->childid;
    }

    /**
     * Set parentid
     *
     * @param integer $parentid
     *
     * @return IsChild
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
     * @return IsChild
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
     * @return IsChild
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
