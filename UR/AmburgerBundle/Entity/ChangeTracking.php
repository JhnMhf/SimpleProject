<?php

namespace UR\AmburgerBundle\Entity;

/**
 * ChangeTracking
 */
class ChangeTracking
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $oid;

    /**
     * @var integer
     */
    private $affected_id;

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
     * Set oid
     *
     * @param integer $oid
     *
     * @return ChangeTracking
     */
    public function setOid($oid)
    {
        $this->oid = $oid;

        return $this;
    }

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
     * Set affectedId
     *
     * @param integer $affectedId
     *
     * @return ChangeTracking
     */
    public function setAffectedId($affectedId)
    {
        $this->affected_id = $affectedId;

        return $this;
    }

    /**
     * Get affectedId
     *
     * @return integer
     */
    public function getAffectedId()
    {
        return $this->affected_id;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return ChangeTracking
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
     * @return ChangeTracking
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
     * @var string
     */
    private $old_data;

    /**
     * @var string
     */
    private $new_data;

    /**
     * @var string
     */
    private $activeUserName;

    /**
     * @var string
     */
    private $activeUserId;


    /**
     * Set oldData
     *
     * @param string $oldData
     *
     * @return ChangeTracking
     */
    public function setOldData($oldData)
    {
        $this->old_data = $oldData;

        return $this;
    }

    /**
     * Get oldData
     *
     * @return string
     */
    public function getOldData()
    {
        return $this->old_data;
    }

    /**
     * Set newData
     *
     * @param string $newData
     *
     * @return ChangeTracking
     */
    public function setNewData($newData)
    {
        $this->new_data = $newData;

        return $this;
    }

    /**
     * Get newData
     *
     * @return string
     */
    public function getNewData()
    {
        return $this->new_data;
    }

    /**
     * Set activeUserName
     *
     * @param string $activeUserName
     *
     * @return ChangeTracking
     */
    public function setActiveUserName($activeUserName)
    {
        $this->activeUserName = $activeUserName;

        return $this;
    }

    /**
     * Get activeUserName
     *
     * @return string
     */
    public function getActiveUserName()
    {
        return $this->activeUserName;
    }

    /**
     * Set activeUserId
     *
     * @param string $activeUserId
     *
     * @return ChangeTracking
     */
    public function setActiveUserId($activeUserId)
    {
        $this->activeUserId = $activeUserId;

        return $this;
    }

    /**
     * Get activeUserId
     *
     * @return string
     */
    public function getActiveUserId()
    {
        return $this->activeUserId;
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
     * @return ChangeTracking
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
