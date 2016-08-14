<?php

namespace UR\DB\NewBundle\Entity;

/**
 * IsParent
 */
class IsParent
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
    private $childID = '0';

    /**
     * @var integer
     */
    private $parentID = '0';

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
     * Set parentID
     *
     * @param integer $parentID
     *
     * @return IsParent
     */
    public function setParentID($parentID)
    {
        $this->parentID = $parentID;

        return $this;
    }

    /**
     * Get parentID
     *
     * @return integer
     */
    public function getParentID()
    {
        return $this->parentID;
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
