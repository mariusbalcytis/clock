<?php

namespace Maba\Component\Clock\Condition;

use Maba\Component\Clock\ClockInterface;

class CurrentTimeConditionChecker
{
    protected $clock;
    protected $timeConditionChecker;

    public function __construct(ClockInterface $clock, TimeConditionChecker $timeConditionChecker)
    {
        $this->clock = $clock;
        $this->timeConditionChecker = $timeConditionChecker;
    }

    /**
     * @param TimeConditionInterface $condition
     * @return boolean
     */
    public function checkCondition(TimeConditionInterface $condition)
    {
        return $this->timeConditionChecker->checkCondition($condition, $this->clock->getCurrentDateTime());
    }
}
