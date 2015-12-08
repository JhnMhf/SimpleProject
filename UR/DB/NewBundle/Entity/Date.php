<?php

namespace UR\DB\NewBundle\Entity;

/**
 * Date
 */
class Date
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $day;

    /**
     * @var string
     */
    private $month;

    /**
     * @var string
     */
    private $year;

    /**
     * @var string
     */
    private $weekday;

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
     * Set day
     *
     * @param string $day
     *
     * @return Date
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Get day
     *
     * @return string
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set month
     *
     * @param string $month
     *
     * @return Date
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return string
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set year
     *
     * @param string $year
     *
     * @return Date
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set weekday
     *
     * @param string $weekday
     *
     * @return Date
     */
    public function setWeekday($weekday)
    {
        $this->weekday = $weekday;

        return $this;
    }

    /**
     * Get weekday
     *
     * @return string
     */
    public function getWeekday()
    {
        return $this->weekday;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Date
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
     * @var boolean
     */
    private $until = 0;

    /**
     * @var boolean
     */
    private $after = 0;


    /**
     * Set until
     *
     * @param boolean $until
     *
     * @return Date
     */
    public function setUntil($until)
    {
        $this->until = $until;

        return $this;
    }

    /**
     * Get until
     *
     * @return boolean
     */
    public function getUntil()
    {
        return $this->until;
    }

    /**
     * Set after
     *
     * @param boolean $after
     *
     * @return Date
     */
    public function setAfter($after)
    {
        $this->after = $after;

        return $this;
    }

    /**
     * Get after
     *
     * @return boolean
     */
    public function getAfter()
    {
        return $this->after;
    }
    /**
     * @var boolean
     */
    private $before = 0;


    /**
     * Set before
     *
     * @param boolean $before
     *
     * @return Date
     */
    public function setBefore($before)
    {
        $this->before = $before;

        return $this;
    }

    /**
     * Get before
     *
     * @return boolean
     */
    public function getBefore()
    {
        return $this->before;
    }
    /**
     * @var boolean
     */
    private $beforeDate = 0;

    /**
     * @var boolean
     */
    private $afterDate = 0;


    /**
     * Set beforeDate
     *
     * @param boolean $beforeDate
     *
     * @return Date
     */
    public function setBeforeDate($beforeDate)
    {
        $this->beforeDate = $beforeDate;

        return $this;
    }

    /**
     * Get beforeDate
     *
     * @return boolean
     */
    public function getBeforeDate()
    {
        return $this->beforeDate;
    }

    /**
     * Set afterDate
     *
     * @param boolean $afterDate
     *
     * @return Date
     */
    public function setAfterDate($afterDate)
    {
        $this->afterDate = $afterDate;

        return $this;
    }

    /**
     * Get afterDate
     *
     * @return boolean
     */
    public function getAfterDate()
    {
        return $this->afterDate;
    }
}
