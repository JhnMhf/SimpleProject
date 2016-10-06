<?php

namespace UR\AmburgerBundle\Entity;

/**
 * OriginOfData
 */
class OriginOfData
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $person_id;

    /**
     * @var string
     */
    private $data;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $modified;


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
     * Set personId
     *
     * @param integer $personId
     *
     * @return OriginOfData
     */
    public function setPersonId($personId)
    {
        $this->person_id = $personId;

        return $this;
    }

    /**
     * Get personId
     *
     * @return integer
     */
    public function getPersonId()
    {
        return $this->person_id;
    }

    /**
     * Set data
     *
     * @param string $data
     *
     * @return OriginOfData
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return OriginOfData
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set modified
     *
     * @param \DateTime $modified
     *
     * @return OriginOfData
     */
    public function setModified($modified)
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * Get modified
     *
     * @return \DateTime
     */
    public function getModified()
    {
        return $this->modified;
    }
    /**
     * @ORM\PreFlush
     */
    public function updateCreatedAndModified()
    {
        if ($this->getCreated() == null) {
            $this->setCreated(new \DateTime());
        }
        $this->setModified(new \DateTime());
    }
}
