<?php

namespace UR\DB\NewBundle\Entity;

/**
 * Death
 */
class Death
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $deathLocationid;

    /**
     * @var integer
     */
    private $deathCountryid;

    /**
     * @var string
     */
    private $causeOfDeath;

    /**
     * @var integer
     */
    private $territoryOfDeathid;

    /**
     * @var string
     */
    private $graveyard;

    /**
     * @var integer
     */
    private $funeralLocationid;

    /**
     * @var string
     */
    private $comment;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $deathDate;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $funeralDate;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->deathDate = new \Doctrine\Common\Collections\ArrayCollection();
        $this->funeralDate = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set deathLocationid
     *
     * @param integer $deathLocationid
     *
     * @return Death
     */
    public function setDeathLocationid($deathLocationid)
    {
        $this->deathLocationid = $deathLocationid;

        return $this;
    }

    /**
     * Get deathLocationid
     *
     * @return integer
     */
    public function getDeathLocationid()
    {
        return $this->deathLocationid;
    }

    /**
     * Set deathCountryid
     *
     * @param integer $deathCountryid
     *
     * @return Death
     */
    public function setDeathCountryid($deathCountryid)
    {
        $this->deathCountryid = $deathCountryid;

        return $this;
    }

    /**
     * Get deathCountryid
     *
     * @return integer
     */
    public function getDeathCountryid()
    {
        return $this->deathCountryid;
    }

    /**
     * Set causeOfDeath
     *
     * @param string $causeOfDeath
     *
     * @return Death
     */
    public function setCauseOfDeath($causeOfDeath)
    {
        $this->causeOfDeath = $causeOfDeath;

        return $this;
    }

    /**
     * Get causeOfDeath
     *
     * @return string
     */
    public function getCauseOfDeath()
    {
        return $this->causeOfDeath;
    }

    /**
     * Set territoryOfDeathid
     *
     * @param integer $territoryOfDeathid
     *
     * @return Death
     */
    public function setTerritoryOfDeathid($territoryOfDeathid)
    {
        $this->territoryOfDeathid = $territoryOfDeathid;

        return $this;
    }

    /**
     * Get territoryOfDeathid
     *
     * @return integer
     */
    public function getTerritoryOfDeathid()
    {
        return $this->territoryOfDeathid;
    }

    /**
     * Set graveyard
     *
     * @param string $graveyard
     *
     * @return Death
     */
    public function setGraveyard($graveyard)
    {
        $this->graveyard = $graveyard;

        return $this;
    }

    /**
     * Get graveyard
     *
     * @return string
     */
    public function getGraveyard()
    {
        return $this->graveyard;
    }

    /**
     * Set funeralLocationid
     *
     * @param integer $funeralLocationid
     *
     * @return Death
     */
    public function setFuneralLocationid($funeralLocationid)
    {
        $this->funeralLocationid = $funeralLocationid;

        return $this;
    }

    /**
     * Get funeralLocationid
     *
     * @return integer
     */
    public function getFuneralLocationid()
    {
        return $this->funeralLocationid;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Death
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

    /**
     * Add deathDate
     *
     * @param \UR\DB\NewBundle\Entity\Date $deathDate
     *
     * @return Death
     */
    public function addDeathDate(\UR\DB\NewBundle\Entity\Date $deathDate)
    {
        $this->deathDate[] = $deathDate;

        return $this;
    }

    /**
     * Remove deathDate
     *
     * @param \UR\DB\NewBundle\Entity\Date $deathDate
     */
    public function removeDeathDate(\UR\DB\NewBundle\Entity\Date $deathDate)
    {
        $this->deathDate->removeElement($deathDate);
    }

    /**
     * Get deathDate
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDeathDate()
    {
        return $this->deathDate;
    }

    /**
     * Add funeralDate
     *
     * @param \UR\DB\NewBundle\Entity\Date $funeralDate
     *
     * @return Death
     */
    public function addFuneralDate(\UR\DB\NewBundle\Entity\Date $funeralDate)
    {
        $this->funeralDate[] = $funeralDate;

        return $this;
    }

    /**
     * Remove funeralDate
     *
     * @param \UR\DB\NewBundle\Entity\Date $funeralDate
     */
    public function removeFuneralDate(\UR\DB\NewBundle\Entity\Date $funeralDate)
    {
        $this->funeralDate->removeElement($funeralDate);
    }

    /**
     * Get funeralDate
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFuneralDate()
    {
        return $this->funeralDate;
    }
    /**
     * @var string
     */
    private $deathDateId;

    /**
     * @var string
     */
    private $funeralDateId;


    /**
     * Set deathDateId
     *
     * @param string $deathDateId
     *
     * @return Death
     */
    public function setDeathDateId($deathDateId)
    {
        $this->deathDateId = $deathDateId;

        return $this;
    }

    /**
     * Get deathDateId
     *
     * @return string
     */
    public function getDeathDateId()
    {
        return $this->deathDateId;
    }

    /**
     * Set funeralDateId
     *
     * @param string $funeralDateId
     *
     * @return Death
     */
    public function setFuneralDateId($funeralDateId)
    {
        $this->funeralDateId = $funeralDateId;

        return $this;
    }

    /**
     * Get funeralDateId
     *
     * @return string
     */
    public function getFuneralDateId()
    {
        return $this->funeralDateId;
    }
}
