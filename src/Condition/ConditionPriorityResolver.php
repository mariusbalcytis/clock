<?php

namespace Maba\Component\Clock\Condition;

class ConditionPriorityResolver
{
    const SEC_IN_DAY = 86400;

    /**
     * If date is set, it wins.
     * If not set or set for both, weekday wins.
     * Lastly comes time by interval (narrower has bigger priority than wider)
     *
     * @param TimeConditionInterface $condition
     * @return int bigger means more specific
     */
    public function getConditionPriority(TimeConditionInterface $condition)
    {
        $priority = 0;
        $priorityItem = self::SEC_IN_DAY + 1;
        if ($condition->getYear() !== null) {
            $priority += $priorityItem * 8;
        }
        if ($condition->getMonth() !== null) {
            $priority += $priorityItem * 4;
        }
        if ($condition->getDay() !== null) {
            $priority += $priorityItem * 2;
        }
        if ($condition->getWeekday() !== null) {
            $priority += $priorityItem;
        }

        if ($condition->getFromTime() !== null || $condition->getUntilTime() !== null) {
            $timePriority = 0;
            if ($condition->getFromTime() !== null && $condition->getUntilTime() !== null) {
                if ($condition->getFromTime() === $condition->getUntilTime()) {
                    $timePriority = self::SEC_IN_DAY;
                } elseif ($condition->getFromTime() < $condition->getUntilTime()) {
                    $timePriority =$condition->getUntilTime() - $condition->getFromTime();
                } else {
                    $timePriority = self::SEC_IN_DAY - $condition->getFromTime() + $condition->getUntilTime();
                }
            } elseif ($condition->getFromTime() !== null) {
                $timePriority = self::SEC_IN_DAY - $condition->getFromTime();
            } elseif ($condition->getUntilTime() !== null) {
                $timePriority = $condition->getUntilTime();
            }
            $priority += max(min(self::SEC_IN_DAY - $timePriority, self::SEC_IN_DAY), 0);
        }

        return $priority;
    }
}
