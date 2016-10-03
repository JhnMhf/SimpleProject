<?php

namespace UR\DB\NewBundle\Entity;

/**
 * JobClass
 */
class JobClass
{
    public function __toString (){
        return "JobClass '".$this->getLabel()."' with ID: ".$this->getId();
    }
    
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $label;


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
     * Set label
     *
     * @param string $label
     *
     * @return JobClass
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }
    /**
     * @var string
     */
    private $comment;


    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return JobClass
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
