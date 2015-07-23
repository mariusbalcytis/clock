<?php

namespace Maba\Component\Clock\Condition;

class ConditionalValueResolver
{
    protected $conditionPriorityResolver;
    protected $conditionChecker;

    public function __construct(
        ConditionPriorityResolver $conditionPriorityResolver,
        CurrentTimeConditionChecker $conditionChecker
    ) {
        $this->conditionPriorityResolver = $conditionPriorityResolver;
        $this->conditionChecker = $conditionChecker;
    }

    /**
     * @param ConditionalValue[] $conditionalValues
     * @param mixed $default
     *
     * @return mixed
     */
    public function resolveValue(array $conditionalValues, $default = null)
    {
        $priorityResolver = $this->conditionPriorityResolver;
        usort($conditionalValues, function(ConditionalValue $a, ConditionalValue $b) use ($priorityResolver) {
            $r = $priorityResolver->getConditionPriority($a->getTimeCondition());
            $r -= $priorityResolver->getConditionPriority($b->getTimeCondition());

            return -$r; // negate result as we need from biggest to smallest
        });

        foreach ($conditionalValues as $conditionalValue) {
            if ($this->conditionChecker->checkCondition($conditionalValue->getTimeCondition())) {
                return $conditionalValue->getValue();
            }
        }

        return $default;
    }
}
