<?php

namespace UR\DB\NewBundle\Entity;

/**
 * Person
 */
class Person extends BasePerson
{
    public function __toString (){
        return "Person with ID: ".$this->getId()." and OID: ".$this->getOid();
    }
    
    /**
     * @var integer
     */
    private $oid;

    /**
     * @var string
     */
    private $control;

    /**
     * @var string
     */
    private $complete;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $sources;

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
     * Constructor
     */
    public function __construct()
    {
        $this->sources = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Add source
     *
     * @param \UR\DB\NewBundle\Entity\Source $source
     *
     * @return Person
     */
    public function addSource(\UR\DB\NewBundle\Entity\Source $source)
    {
        $this->sources[] = $source;

        return $this;
    }

    /**
     * Remove source
     *
     * @param \UR\DB\NewBundle\Entity\Source $source
     */
    public function removeSource(\UR\DB\NewBundle\Entity\Source $source)
    {
        $this->sources->removeElement($source);
    }

    /**
     * Get sources
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSources()
    {
        return $this->sources;
    }

    /**
     * Add education
     *
     * @param \UR\DB\NewBundle\Entity\Education $education
     *
     * @return Person
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
     * @return Person
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
     * @return Person
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
     * @return Person
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
     * @return Person
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
     * @return Person
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
     * @return Person
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
     * @return Person
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
     * @return Person
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
}
