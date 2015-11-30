<?php

namespace UR\DB\FinalBundle\Entity;

/**
 * IsParentInLaw
 */
class IsParentInLaw
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $childInLawid;

    /**
     * @var integer
     */
    private $parentInLawid;

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
     * Set childInLawid
     *
     * @param integer $childInLawid
     *
     * @return IsParentInLaw
     */
    public function setChildInLawid($childInLawid)
    {
        $this->childInLawid = $childInLawid;

        return $this;
    }

    /**
     * Get childInLawid
     *
     * @return integer
     */
    public function getChildInLawid()
    {
        return $this->childInLawid;
    }

    /**
     * Set parentInLawid
     *
     * @param integer $parentInLawid
     *
     * @return IsParentInLaw
     */
    public function setParentInLawid($parentInLawid)
    {
        $this->parentInLawid = $parentInLawid;

        return $this;
    }

    /**
     * Get parentInLawid
     *
     * @return integer
     */
    public function getParentInLawid()
    {
        return $this->parentInLawid;
    }

    /**
     * Set relationType
     *
     * @param string $relationType
     *
     * @return IsParentInLaw
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
     * @return IsParentInLaw
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
