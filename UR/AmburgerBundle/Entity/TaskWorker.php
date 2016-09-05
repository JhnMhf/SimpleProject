<?php

namespace UR\AmburgerBundle\Entity;

/**
 * TaskWorker
 */
class TaskWorker
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $running;


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
     * Set running
     *
     * @param boolean $running
     *
     * @return TaskWorker
     */
    public function setRunning($running)
    {
        $this->running = $running;

        return $this;
    }

    /**
     * Get running
     *
     * @return boolean
     */
    public function getRunning()
    {
        return $this->running;
    }
    /**
     * @var \DateTime
     */
    private $lastrun;


    /**
     * Set lastrun
     *
     * @param \DateTime $lastrun
     *
     * @return TaskWorker
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
}
