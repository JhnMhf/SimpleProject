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
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $educations;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $honours;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $properties;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $ranks;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $religions;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $residences;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $roadOfLife;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $stati;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $works;

    /**
     * @var \UR\DB\NewBundle\Entity\Nation
     */
    private $nation;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->educations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->honours = new \Doctrine\Common\Collections\ArrayCollection();
        $this->properties = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ranks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->religions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->residences = new \Doctrine\Common\Collections\ArrayCollection();
        $this->roadOfLife = new \Doctrine\Common\Collections\ArrayCollection();
        $this->stati = new \Doctrine\Common\Collections\ArrayCollection();
        $this->works = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add education
     *
     * @param \UR\DB\NewBundle\Entity\Education $education
     *
     * @return Relative
     */
    public function addEducation(\UR\DB\NewBundle\Entity\Education $education)
    {
        $this->educations[] = $education;

        return $this;
    }

    /**
     * Remove education
     *
     * @param \UR\DB\NewBundle\Entity\Education $education
     */
    public function removeEducation(\UR\DB\NewBundle\Entity\Education $education)
    {
        $this->educations->removeElement($education);
    }

    /**
     * Get educations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEducations()
    {
        return $this->educations;
    }

    /**
     * Add honour
     *
     * @param \UR\DB\NewBundle\Entity\Honour $honour
     *
     * @return Relative
     */
    public function addHonour(\UR\DB\NewBundle\Entity\Honour $honour)
    {
        $this->honours[] = $honour;

        return $this;
    }

    /**
     * Remove honour
     *
     * @param \UR\DB\NewBundle\Entity\Honour $honour
     */
    public function removeHonour(\UR\DB\NewBundle\Entity\Honour $honour)
    {
        $this->honours->removeElement($honour);
    }

    /**
     * Get honours
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHonours()
    {
        return $this->honours;
    }

    /**
     * Add property
     *
     * @param \UR\DB\NewBundle\Entity\Property $property
     *
     * @return Relative
     */
    public function addProperty(\UR\DB\NewBundle\Entity\Property $property)
    {
        $this->properties[] = $property;

        return $this;
    }

    /**
     * Remove property
     *
     * @param \UR\DB\NewBundle\Entity\Property $property
     */
    public function removeProperty(\UR\DB\NewBundle\Entity\Property $property)
    {
        $this->properties->removeElement($property);
    }

    /**
     * Get properties
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Add rank
     *
     * @param \UR\DB\NewBundle\Entity\Rank $rank
     *
     * @return Relative
     */
    public function addRank(\UR\DB\NewBundle\Entity\Rank $rank)
    {
        $this->ranks[] = $rank;

        return $this;
    }

    /**
     * Remove rank
     *
     * @param \UR\DB\NewBundle\Entity\Rank $rank
     */
    public function removeRank(\UR\DB\NewBundle\Entity\Rank $rank)
    {
        $this->ranks->removeElement($rank);
    }

    /**
     * Get ranks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRanks()
    {
        return $this->ranks;
    }

    /**
     * Add religion
     *
     * @param \UR\DB\NewBundle\Entity\Religion $religion
     *
     * @return Relative
     */
    public function addReligion(\UR\DB\NewBundle\Entity\Religion $religion)
    {
        $this->religions[] = $religion;

        return $this;
    }

    /**
     * Remove religion
     *
     * @param \UR\DB\NewBundle\Entity\Religion $religion
     */
    public function removeReligion(\UR\DB\NewBundle\Entity\Religion $religion)
    {
        $this->religions->removeElement($religion);
    }

    /**
     * Get religions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReligions()
    {
        return $this->religions;
    }

    /**
     * Add residence
     *
     * @param \UR\DB\NewBundle\Entity\Residence $residence
     *
     * @return Relative
     */
    public function addResidence(\UR\DB\NewBundle\Entity\Residence $residence)
    {
        $this->residences[] = $residence;

        return $this;
    }

    /**
     * Remove residence
     *
     * @param \UR\DB\NewBundle\Entity\Residence $residence
     */
    public function removeResidence(\UR\DB\NewBundle\Entity\Residence $residence)
    {
        $this->residences->removeElement($residence);
    }

    /**
     * Get residences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResidences()
    {
        return $this->residences;
    }

    /**
     * Add roadOfLife
     *
     * @param \UR\DB\NewBundle\Entity\RoadOfLife $roadOfLife
     *
     * @return Relative
     */
    public function addRoadOfLife(\UR\DB\NewBundle\Entity\RoadOfLife $roadOfLife)
    {
        $this->roadOfLife[] = $roadOfLife;

        return $this;
    }

    /**
     * Remove roadOfLife
     *
     * @param \UR\DB\NewBundle\Entity\RoadOfLife $roadOfLife
     */
    public function removeRoadOfLife(\UR\DB\NewBundle\Entity\RoadOfLife $roadOfLife)
    {
        $this->roadOfLife->removeElement($roadOfLife);
    }

    /**
     * Get roadOfLife
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRoadOfLife()
    {
        return $this->roadOfLife;
    }

    /**
     * Add stati
     *
     * @param \UR\DB\NewBundle\Entity\Status $stati
     *
     * @return Relative
     */
    public function addStati(\UR\DB\NewBundle\Entity\Status $stati)
    {
        $this->stati[] = $stati;

        return $this;
    }

    /**
     * Remove stati
     *
     * @param \UR\DB\NewBundle\Entity\Status $stati
     */
    public function removeStati(\UR\DB\NewBundle\Entity\Status $stati)
    {
        $this->stati->removeElement($stati);
    }

    /**
     * Get stati
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStati()
    {
        return $this->stati;
    }

    /**
     * Add work
     *
     * @param \UR\DB\NewBundle\Entity\Works $work
     *
     * @return Relative
     */
    public function addWork(\UR\DB\NewBundle\Entity\Works $work)
    {
        $this->works[] = $work;

        return $this;
    }

    /**
     * Remove work
     *
     * @param \UR\DB\NewBundle\Entity\Works $work
     */
    public function removeWork(\UR\DB\NewBundle\Entity\Works $work)
    {
        $this->works->removeElement($work);
    }

    /**
     * Get works
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWorks()
    {
        return $this->works;
    }

    /**
     * Set nation
     *
     * @param \UR\DB\NewBundle\Entity\Nation $nation
     *
     * @return Relative
     */
    public function setNation(\UR\DB\NewBundle\Entity\Nation $nation = null)
    {
        $this->nation = $nation;

        return $this;
    }

    /**
     * Get nation
     *
     * @return \UR\DB\NewBundle\Entity\Nation
     */
    public function getNation()
    {
        return $this->nation;
    }
    /**
     * @var \UR\DB\NewBundle\Entity\Birth
     */
    private $birth;

    /**
     * @var \UR\DB\NewBundle\Entity\Baptism
     */
    private $baptism;

    /**
     * @var \UR\DB\NewBundle\Entity\Death
     */
    private $death;


    /**
     * Set birth
     *
     * @param \UR\DB\NewBundle\Entity\Birth $birth
     *
     * @return Relative
     */
    public function setBirth(\UR\DB\NewBundle\Entity\Birth $birth = null)
    {
        $this->birth = $birth;

        return $this;
    }

    /**
     * Get birth
     *
     * @return \UR\DB\NewBundle\Entity\Birth
     */
    public function getBirth()
    {
        return $this->birth;
    }

    /**
     * Set baptism
     *
     * @param \UR\DB\NewBundle\Entity\Baptism $baptism
     *
     * @return Relative
     */
    public function setBaptism(\UR\DB\NewBundle\Entity\Baptism $baptism = null)
    {
        $this->baptism = $baptism;

        return $this;
    }

    /**
     * Get baptism
     *
     * @return \UR\DB\NewBundle\Entity\Baptism
     */
    public function getBaptism()
    {
        return $this->baptism;
    }

    /**
     * Set death
     *
     * @param \UR\DB\NewBundle\Entity\Death $death
     *
     * @return Relative
     */
    public function setDeath(\UR\DB\NewBundle\Entity\Death $death = null)
    {
        $this->death = $death;

        return $this;
    }

    /**
     * Get death
     *
     * @return \UR\DB\NewBundle\Entity\Death
     */
    public function getDeath()
    {
        return $this->death;
    }
}
