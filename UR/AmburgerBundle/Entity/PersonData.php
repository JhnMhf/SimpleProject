<?php

namespace UR\AmburgerBundle\Entity;

/**
 * PersonData
 */
class PersonData
{
    /**
     * @var integer
     */
    private $oid;

    /**
     * @var boolean
     */
    private $currentlyInProcess = false;

    /**
     * @var boolean
     */
    private $completed = false;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $modified;

    /**
     * Get oid
     *
     * @return integer
     */
    public function getOid()
    {
        return $this->oid;
    }

    /**
     * Set currentlyInProcess
     *
     * @param boolean $currentlyInProcess
     *
     * @return PersonData
     */
    public function setCurrentlyInProcess($currentlyInProcess)
    {
        $this->currentlyInProcess = $currentlyInProcess;

        return $this;
    }

    /**
     * Get currentlyInProcess
     *
     * @return boolean
     */
    public function getCurrentlyInProcess()
    {
        return $this->currentlyInProcess;
    }

    /**
     * Set completed
     *
     * @param boolean $completed
     *
     * @return PersonData
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;

        return $this;
    }

    /**
     * Get completed
     *
     * @return boolean
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return PersonData
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
     * @return PersonData
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

    /**
     * Set oid
     *
     * @param integer $oid
     *
     * @return PersonData
     */
    public function setOid($oid)
    {
        $this->oid = $oid;

        return $this;
    }
    /**
     * @var integer
     */
    private $personId;


    /**
     * Set personId
     *
     * @param integer $personId
     *
     * @return PersonData
     */
    public function setPersonId($personId)
    {
        $this->personId = $personId;

        return $this;
    }

    /**
     * Get personId
     *
     * @return integer
     */
    public function getPersonId()
    {
        return $this->personId;
    }
}
