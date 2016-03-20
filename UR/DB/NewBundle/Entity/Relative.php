<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UR\DB\NewBundle\Entity;

/**
 * Description of Relative
 *
 * @author johanna
 */
class Relative extends BasePerson {
    public function __toString (){
        return "Relative with ID: ".$this->getId();
    }
    /**
     * @var integer
     */
    private $nationid;


    /**
     * Set nationid
     *
     * @param integer $nationid
     *
     * @return Relative
     */
    public function setNationid($nationid)
    {
        $this->nationid = $nationid;

        return $this;
    }

    /**
     * Get nationid
     *
     * @return integer
     */
    public function getNationid()
    {
        return $this->nationid;
    }
}
