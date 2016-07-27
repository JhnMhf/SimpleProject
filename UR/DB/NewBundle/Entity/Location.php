<?php

namespace UR\DB\NewBundle\Entity;

/**
 * Location
 */
class Location
{
    public function __toString (){
        return "Location '".$this->getName()."' with ID: ".$this->getId();
    }
    
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

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
     * Set name
     *
     * @param string $name
     *
     * @return Location
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Location
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
     * @var string
     */
    private $latitude;

    /**
     * @var string
     */
    private $longitute;


    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return Location
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitute
     *
     * @param string $longitute
     *
     * @return Location
     */
    public function setLongitute($longitute)
    {
        $this->longitute = $longitute;

        return $this;
    }

    /**
     * Get longitute
     *
     * @return string
     */
    public function getLongitute()
    {
        return $this->longitute;
    }
    /**
     * @var string
     */
    private $longitude;


    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return Location
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
}
