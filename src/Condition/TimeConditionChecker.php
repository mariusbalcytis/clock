<?php

namespace Maba\Component\Clock\Condition;

class TimeConditionChecker
{
    /**
     * @param TimeConditionInterface $condition
     * @param \DateTime $dateTime
     * @return bool
     */
    public function checkCondition(TimeConditionInterface $condition, \DateTime $dateTime)
    {
        if ($condition->getYear() !== null && (string)$condition->getYear() !== $dateTime->format('Y')) {
            return false;
        }
        if ($condition->getMonth() !== null && (string)$condition->getMonth() !== $dateTime->format('n')) {
            return false;
        }
        if ($condition->getDay() !== null && (string)$condition->getDay() !== $dateTime->format('j')) {
            return false;
        }
        if ($condition->getWeekday() !== null && (string)$condition->getWeekday() !== $dateTime->format('w')) {
            return false;
        }

        if ($condition->getFromTime() !== null || $condition->getUntilTime() !== null) {
            $today = clone $dateTime;
            $today->setTime(0, 0, 0);
            $secondsInDay = $dateTime->getTimestamp() - $today->getTimestamp();
            if ($condition->getFromTime() !== null && $condition->getUntilTime() !== null) {
                if ($condition->getFromTime() === $condition->getUntilTime()) {
                    return true;
                } elseif ($condition->getFromTime() > $condition->getUntilTime()) {
                    return $secondsInDay >= $condition->getFromTime() || $secondsInDay < $condition->getUntilTime();
                } else {
                    return $secondsInDay >= $condition->getFromTime() && $secondsInDay < $condition->getUntilTime();
                }
            } elseif ($condition->getFromTime() !== null) {
                return $secondsInDay >= $condition->getFromTime();
            } else {
                return $secondsInDay < $condition->getUntilTime();
            }
        }

        return true;
    }
}
