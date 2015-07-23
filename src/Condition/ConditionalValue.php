<?php

namespace Maba\Component\Clock\Condition;

class ConditionalValue
{

    /**
     * @var TimeConditionInterface
     */
    protected $timeCondition;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @return TimeConditionInterface
     */
    public function getTimeCondition()
    {
        return $this->timeCondition;
    }

    /**
     * @param TimeConditionInterface $timeCondition
     * @return $this
     */
    public function setTimeCondition(TimeConditionInterface $timeCondition)
    {
        $this->timeCondition = $timeCondition;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
}
