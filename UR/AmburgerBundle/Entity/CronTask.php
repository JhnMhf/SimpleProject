<?php

namespace UR\AmburgerBundle\Entity;

/**
 * CronTask
 */
class CronTask
{
    
    public function __toString (){
        return "CronTask: ".$this->name;
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
     * @var \DateTime
     */
    private $lastrun;


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
     * @return CronTask
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
     * Set lastrun
     *
     * @param \DateTime $lastrun
     *
     * @return CronTask
     */
    public function setLastrun($lastrun)
    {
        $this->lastrun = $lastrun;

        return $this;
    }

    /**
     * Get lastrun
     *
     * @return \DateTime
     */
    public function getLastrun()
    {
        return $this->lastrun;
    }
    /**
     * @var array
     */
    private $commands;


    /**
     * Set commands
     *
     * @param array $commands
     *
     * @return CronTask
     */
    public function setCommands($commands)
    {
        $this->commands = $commands;

        return $this;
    }

    /**
     * Get commands
     *
     * @return array
     */
    public function getCommands()
    {
        return $this->commands;
    }
    /**
     * @var integer
     */
    private $runInterval;


    /**
     * Set runInterval
     *
     * @param integer $runInterval
     *
     * @return CronTask
     */
    public function setRunInterval($runInterval)
    {
        $this->runInterval = $runInterval;

        return $this;
    }

    /**
     * Get runInterval
     *
     * @return integer
     */
    public function getRunInterval()
    {
        return $this->runInterval;
    }
    /**
     * @var boolean
     */
    private $active = true;


    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return CronTask
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }
}
