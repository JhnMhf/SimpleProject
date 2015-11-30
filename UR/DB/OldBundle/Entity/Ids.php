<?php

namespace UR\DB\OldBundle\Entity;

/**
 * Ids
 */
class Ids
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
     * @var string
     */
    private $kontrolle;

    /**
     * @var string
     */
    private $vollständig;


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
     * @return Ids
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
     * Set kontrolle
     *
     * @param string $kontrolle
     *
     * @return Ids
     */
    public function setKontrolle($kontrolle)
    {
        $this->kontrolle = $kontrolle;

        return $this;
    }

    /**
     * Get kontrolle
     *
     * @return string
     */
    public function getKontrolle()
    {
        return $this->kontrolle;
    }

    /**
     * Set vollständig
     *
     * @param string $vollständig
     *
     * @return Ids
     */
    public function setVollständig($vollständig)
    {
        $this->vollständig = $vollständig;

        return $this;
    }

    /**
     * Get vollständig
     *
     * @return string
     */
    public function getVollständig()
    {
        return $this->vollständig;
    }
}

