<?php

namespace UR\AmburgerBundle\Entity;

/**
 * CorrectionSession
 */
class CorrectionSession
{

    /**
     * @var String
     */
    private $activeUserName;

    /**
     * @var String
     */
    private $activeUserId;

    /**
     * @var String
     */
    private $sessionIdentifier;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $modified;


    /**
     * Set activeUserName
     *
     * @param \String $activeUserName
     *
     * @return CorrectionSession
     */
    public function setActiveUserName($activeUserName)
    {
        $this->activeUserName = $activeUserName;

        return $this;
    }

    /**
     * Get activeUserName
     *
     * @return \String
     */
    public function getActiveUserName()
    {
        return $this->activeUserName;
    }

    /**
     * Set activeUserId
     *
     * @param \String $activeUserId
     *
     * @return CorrectionSession
     */
    public function setActiveUserId($activeUserId)
    {
        $this->activeUserId = $activeUserId;

        return $this;
    }

    /**
     * Get activeUserId
     *
     * @return \String
     */
    public function getActiveUserId()
    {
        return $this->activeUserId;
    }

    /**
     * Set sessionIdentifier
     *
     * @param \String $sessionIdentifier
     *
     * @return CorrectionSession
     */
    public function setSessionIdentifier($sessionIdentifier)
    {
        $this->sessionIdentifier = $sessionIdentifier;

        return $this;
    }

    /**
     * Get sessionIdentifier
     *
     * @return \String
     */
    public function getSessionIdentifier()
    {
        return $this->sessionIdentifier;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return CorrectionSession
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
     * @return CorrectionSession
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
     * @var integer
     */
    private $personId;


    /**
     * Set personId
     *
     * @param integer $personId
     *
     * @return CorrectionSession
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
