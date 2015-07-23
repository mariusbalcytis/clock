<?php

namespace Maba\Component\Clock;

class TimeZonedClock implements ClockInterface
{
    protected $clock;
    protected $timeZone;

    public function __construct(ClockInterface $clock, \DateTimeZone $timeZone)
    {
        $this->clock = $clock;
        $this->timeZone = $timeZone;
    }

    /**
     * @return \DateTime
     */
    public function getCurrentDateTime()
    {
        $result = $this->clock->getCurrentDateTime();
        $result->setTimezone($this->timeZone);
        return $result;
    }
}
