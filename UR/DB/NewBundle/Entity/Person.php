<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UR\DB\NewBundle\Entity;

/**
 * Description of Person
 *
 * @author johanna
 */
class Person extends BasePerson  {
    public function __toString (){
        return "Person with ID: ".$this->getId();
    }
    /**
     * @var integer
     */
    private $oid;

    /**
     * @var integer
     */
    private $originalNationid;

    /**
     * @var string
     */
    private $control;

    /**
     * @var string
     */
    private $complete;


    /**
     * Set oid
     *
     * @param integer $oid
     *
     * @return Person
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
     * Set originalNationid
     *
     * @param integer $originalNationid
     *
     * @return Person
     */
    public function setOriginalNationid($originalNationid)
    {
        $this->originalNationid = $originalNationid;

        return $this;
    }

    /**
     * Get originalNationid
     *
     * @return integer
     */
    public function getOriginalNationid()
    {
        return $this->originalNationid;
    }

    /**
     * Set control
     *
     * @param string $control
     *
     * @return Person
     */
    public function setControl($control)
    {
        $this->control = $control;

        return $this;
    }

    /**
     * Get control
     *
     * @return string
     */
    public function getControl()
    {
        return $this->control;
    }

    /**
     * Set complete
     *
     * @param string $complete
     *
     * @return Person
     */
    public function setComplete($complete)
    {
        $this->complete = $complete;

        return $this;
    }

    /**
     * Get complete
     *
     * @return string
     */
    public function getComplete()
    {
        return $this->complete;
    }
}
