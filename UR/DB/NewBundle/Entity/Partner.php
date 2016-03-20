<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UR\DB\NewBundle\Entity;

/**
 * Description of Partner
 *
 * @author johanna
 */
class Partner extends BasePerson  {
    public function __toString (){
        return "Partner with ID: ".$this->getId();
    }
    /**
     * @var integer
     */
    private $originalNationid;


    /**
     * Set originalNationid
     *
     * @param integer $originalNationid
     *
     * @return Partner
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
}
