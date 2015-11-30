<?php

namespace UR\DB\FinalBundle\Entity;

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
    private $deathDateid;

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
     * @var integer
     */
    private $funeralDateid;

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
     * Set deathDateid
     *
     * @param integer $deathDateid
     *
     * @return Death
     */
    public function setDeathDateid($deathDateid)
    {
        $this->deathDateid = $deathDateid;

        return $this;
    }

    /**
     * Get deathDateid
     *
     * @return integer
     */
    public function getDeathDateid()
    {
        return $this->deathDateid;
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
     * Set funeralDateid
     *
     * @param integer $funeralDateid
     *
     * @return Death
     */
    public function setFuneralDateid($funeralDateid)
    {
        $this->funeralDateid = $funeralDateid;

        return $this;
    }

    /**
     * Get funeralDateid
     *
     * @return integer
     */
    public function getFuneralDateid()
    {
        return $this->funeralDateid;
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
}
