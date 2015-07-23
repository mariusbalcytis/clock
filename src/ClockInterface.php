<?php

namespace Maba\Component\Clock;

interface ClockInterface
{
    /**
     * Returns \DateTime representing current time.
     * Returns new object each time called, even if time has not changed.
     *
     * @return \DateTime
     */
    public function getCurrentDateTime();
}
