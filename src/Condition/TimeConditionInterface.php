<?php

namespace Maba\Component\Clock\Condition;

interface TimeConditionInterface
{

    /**
     * @return int only available if month and day specified
     */
    public function getYear();

    /**
     * @return int only available if day specified
     */
    public function getMonth();

    /**
     * @return int day of the month (1-31)
     */
    public function getDay();

    /**
     * @return int 0 to 6, 0 as sunday
     */
    public function getWeekday();

    /**
     * @return int in seconds from 00:00, inclusive
     */
    public function getFromTime();

    /**
     * @return int in seconds from 00:00, not inclusive
     */
    public function getUntilTime();
}
