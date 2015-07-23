<?php

namespace Maba\Component\Clock\Condition;

class TimeCondition implements TimeConditionInterface
{
    /**
     * @var int in seconds from 00:00, inclusive
     */
    protected $fromTime;

    /**
     * @var int in seconds from 00:00, not inclusive
     */
    protected $untilTime;

    /**
     * @var int only available if month and day specified
     */
    protected $year;

    /**
     * @var int only available if day specified
     */
    protected $month;

    /**
     * @var int day of the month (1-31)
     */
    protected $day;

    /**
     * @var int 0 to 6, 0 as sunday
     */
    protected $weekday;

    /**
     * @return int
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param int $day
     * @return $this
     */
    public function setDay($day)
    {
        $this->day = $day;
        return $this;
    }

    /**
     * @return int
     */
    public function getFromTime()
    {
        return $this->fromTime;
    }

    /**
     * @param int $fromTime
     * @return $this
     */
    public function setFromTime($fromTime)
    {
        $this->fromTime = $fromTime;
        return $this;
    }

    /**
     * @return int
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param int $month
     * @return $this
     */
    public function setMonth($month)
    {
        $this->month = $month;
        return $this;
    }

    /**
     * @return int
     */
    public function getUntilTime()
    {
        return $this->untilTime;
    }

    /**
     * @param int $untilTime
     * @return $this
     */
    public function setUntilTime($untilTime)
    {
        $this->untilTime = $untilTime;
        return $this;
    }

    /**
     * @return int
     */
    public function getWeekday()
    {
        return $this->weekday;
    }

    /**
     * @param int $weekday
     * @return $this
     */
    public function setWeekday($weekday)
    {
        $this->weekday = $weekday;
        return $this;
    }

    /**
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param int $year
     * @return $this
     */
    public function setYear($year)
    {
        $this->year = $year;
        return $this;
    }
}
