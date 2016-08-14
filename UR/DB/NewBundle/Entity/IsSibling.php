<?php

namespace UR\DB\NewBundle\Entity;

/**
 * IsSibling
 */
class IsSibling
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
    private $siblingOneid = '0';

    /**
     * @var integer
     */
    private $siblingTwoid = '0';

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
     * @return IsSibling
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
     * Set siblingOneid
     *
     * @param integer $siblingOneid
     *
     * @return IsSibling
     */
    public function setSiblingOneid($siblingOneid)
    {
        $this->siblingOneid = $siblingOneid;

        return $this;
    }

    /**
     * Get siblingOneid
     *
     * @return integer
     */
    public function getSiblingOneid()
    {
        return $this->siblingOneid;
    }

    /**
     * Set siblingTwoid
     *
     * @param integer $siblingTwoid
     *
     * @return IsSibling
     */
    public function setSiblingTwoid($siblingTwoid)
    {
        $this->siblingTwoid = $siblingTwoid;

        return $this;
    }

    /**
     * Get siblingTwoid
     *
     * @return integer
     */
    public function getSiblingTwoid()
    {
        return $this->siblingTwoid;
    }

    /**
     * Set relationType
     *
     * @param string $relationType
     *
     * @return IsSibling
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
     * @return IsSibling
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
