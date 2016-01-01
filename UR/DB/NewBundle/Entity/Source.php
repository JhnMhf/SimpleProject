<?php

namespace UR\DB\NewBundle\Entity;

/**
 * Source
 */
class Source
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $label;

    /**
     * @var boolean
     */
    private $sourceOrder = '1';

    /**
     * @var string
     */
    private $placeOfDiscovery;

    /**
     * @var string
     */
    private $remark;

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
     * Set label
     *
     * @param string $label
     *
     * @return Source
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
     * Set sourceOrder
     *
     * @param boolean $sourceOrder
     *
     * @return Source
     */
    public function setSourceOrder($sourceOrder)
    {
        $this->sourceOrder = $sourceOrder;

        return $this;
    }

    /**
     * Get sourceOrder
     *
     * @return boolean
     */
    public function getSourceOrder()
    {
        return $this->sourceOrder;
    }

    /**
     * Set placeOfDiscovery
     *
     * @param string $placeOfDiscovery
     *
     * @return Source
     */
    public function setPlaceOfDiscovery($placeOfDiscovery)
    {
        $this->placeOfDiscovery = $placeOfDiscovery;

        return $this;
    }

    /**
     * Get placeOfDiscovery
     *
     * @return string
     */
    public function getPlaceOfDiscovery()
    {
        return $this->placeOfDiscovery;
    }

    /**
     * Set remark
     *
     * @param string $remark
     *
     * @return Source
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;

        return $this;
    }

    /**
     * Get remark
     *
     * @return string
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Source
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
