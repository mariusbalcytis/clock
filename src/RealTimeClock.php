<?php

namespace Maba\Component\Clock;

class RealTimeClock implements ClockInterface
{
    /**
     * @return \DateTime
     */
    public function getCurrentDateTime()
    {
        return new \DateTime();
    }
}
