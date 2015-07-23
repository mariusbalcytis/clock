<?php

namespace Maba\Component\Clock\Tests\Condition;

use Maba\Component\Clock\Condition\ConditionPriorityResolver;
use Maba\Component\Clock\Condition\TimeCondition;
use Maba\Component\Clock\Condition\TimeConditionInterface;

class ConditionPriorityResolverTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param TimeConditionInterface $looses
     * @param TimeConditionInterface $wins
     *
     * @dataProvider dataProvider
     */
    public function testGetConditionPriority(
        TimeConditionInterface $looses,
        TimeConditionInterface $wins
    ) {
        $conditionPriorityResolver = new ConditionPriorityResolver();

        $smallerPriority = $conditionPriorityResolver->getConditionPriority($looses);
        $largerPriority = $conditionPriorityResolver->getConditionPriority($wins);

        $this->assertTrue($smallerPriority < $largerPriority);
    }

    public function dataProvider()
    {
        return array(
            'counts seconds' => array(
                $this->createCondition()->setFromTime(0)->setUntilTime(10),
                $this->createCondition()->setFromTime(0)->setUntilTime(9),
            ),
            'counts seconds when overlapping' => array(
                $this->createCondition()->setFromTime(0)->setUntilTime(9),
                $this->createCondition()->setFromTime(8)->setUntilTime(15),
            ),
            'counts until time if not set' => array(
                $this->createCondition()->setFromTime(23 * 3600),
                $this->createCondition()->setFromTime(0)->setUntilTime(3599),
            ),
            'counts until time if not set #2' => array(
                $this->createCondition()->setFromTime(0)->setUntilTime(3601),
                $this->createCondition()->setFromTime(23 * 3600),
            ),
            'counts from time if not set' => array(
                $this->createCondition()->setUntilTime(3600),
                $this->createCondition()->setFromTime(10000)->setUntilTime(13599),
            ),
            'counts from time if not set #2' => array(
                $this->createCondition()->setFromTime(10000)->setUntilTime(13601),
                $this->createCondition()->setUntilTime(3600),
            ),
            'from and until can overlap' => array(
                $this->createCondition()->setFromTime(8 * 3600)->setUntilTime(20 * 3600),
                $this->createCondition()->setFromTime(23 * 3600)->setUntilTime(1 * 3600),
            ),
            'from and until can overlap #2' => array(
                $this->createCondition()->setFromTime(8 * 3600)->setUntilTime(3 * 3600),
                $this->createCondition()->setFromTime(23 * 3600)->setUntilTime(1 * 3600),
            ),
            'same from and until means whole day' => array(
                $this->createCondition()->setFromTime(123)->setUntilTime(123),
                $this->createCondition()->setFromTime(0)->setUntilTime(24 * 3600 - 1),
            ),
            'time wins over weekday' => array(
                $this->createCondition()->setFromTime(100)->setUntilTime(200),
                $this->createCondition()->setWeekday(1),
            ),
            'day wins over weekday' => array(
                $this->createCondition()->setWeekday(1),
                $this->createCondition()->setDay(12),
            ),
            'day wins over weekday even if with time' => array(
                $this->createCondition()->setWeekday(1)->setFromTime(0)->setUntilTime(3600),
                $this->createCondition()->setDay(12),
            ),
            'day with month wins over day' => array(
                $this->createCondition()->setDay(1),
                $this->createCondition()->setDay(12)->setMonth(1),
            ),
            'day with month and year wins over day with month' => array(
                $this->createCondition()->setDay(1)->setMonth(11),
                $this->createCondition()->setDay(12)->setMonth(1)->setYear(2000),
            ),
            'time wins over no restrictions' => array(
                $this->createCondition(),
                $this->createCondition()->setFromTime(1),
            ),
            'weekday wins over no restrictions' => array(
                $this->createCondition(),
                $this->createCondition()->setWeekday(1),
            ),
            'date wins over no restrictions' => array(
                $this->createCondition(),
                $this->createCondition()->setYear(2012)->setMonth(1)->setDay(1),
            ),
        );
    }

    private function createCondition()
    {
        return new TimeCondition();
    }
}
