<?php

namespace Maba\Component\Clock\Tests\Condition;

use Maba\Component\Clock\Condition\TimeCondition;
use Maba\Component\Clock\Condition\TimeConditionChecker;
use Maba\Component\Clock\Condition\TimeConditionInterface;

class TimeConditionCheckerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param boolean $expected
     * @param \DateTime $dateTime
     * @param TimeConditionInterface $condition
     *
     * @dataProvider dataProvider
     */
    public function testCheckCondition($expected, \DateTime $dateTime, TimeConditionInterface $condition)
    {
        $timeConditionChecker = new TimeConditionChecker();
        $this->assertEquals($expected, $timeConditionChecker->checkCondition($condition, $dateTime));
    }

    public function dataProvider()
    {
        return array(
            'looks at from time' => array(
                true,
                new \DateTime('2000-01-01 10:00:00'),
                $this->createTimeCondition()->setFromTime(10 * 3600 - 1)
            ),
            'looks at from time inclusively' => array(
                true,
                new \DateTime('2000-01-01 10:00:00'),
                $this->createTimeCondition()->setFromTime(10 * 3600)
            ),
            'looks at from time #2' => array(
                false,
                new \DateTime('2000-01-01 10:00:00'),
                $this->createTimeCondition()->setFromTime(10 * 3600 + 1)
            ),
            'looks at until time' => array(
                false,
                new \DateTime('2000-01-01 10:00:00'),
                $this->createTimeCondition()->setUntilTime(10 * 3600 - 1)
            ),
            'looks at until time exclusively' => array(
                false,
                new \DateTime('2000-01-01 10:00:00'),
                $this->createTimeCondition()->setUntilTime(10 * 3600)
            ),
            'looks at until time #2' => array(
                true,
                new \DateTime('2000-01-01 10:00:00'),
                $this->createTimeCondition()->setUntilTime(10 * 3600 + 1)
            ),
            'looks at from and until time' => array(
                true,
                new \DateTime('2000-01-01 10:00:00'),
                $this->createTimeCondition()->setFromTime(10 * 3600 - 1)->setUntilTime(10 * 3600 + 1)
            ),
            'looks at from and until time # 2' => array(
                false,
                new \DateTime('2000-01-01 10:00:00'),
                $this->createTimeCondition()->setFromTime(10 * 3600 + 1)->setUntilTime(10 * 3600 + 2)
            ),
            'looks at from and until time # 3' => array(
                false,
                new \DateTime('2000-01-01 10:00:00'),
                $this->createTimeCondition()->setFromTime(10 * 3600 - 2)->setUntilTime(10 * 3600 - 1)
            ),
            'from and until can overlap' => array(
                true,
                new \DateTime('2000-01-01 03:00:00'),
                $this->createTimeCondition()->setFromTime(23 * 3600)->setUntilTime(5 * 3600)
            ),
            'same from and until means whole day' => array(
                true,
                new \DateTime('2000-01-01 12:00:00'),
                $this->createTimeCondition()->setFromTime(23 * 3600)->setUntilTime(23 * 3600)
            ),
            'same from and until means whole day #2' => array(
                true,
                new \DateTime('2000-01-01 12:00:00'),
                $this->createTimeCondition()->setFromTime(0)->setUntilTime(0)
            ),
            'whole day' => array(
                true,
                new \DateTime('2000-01-01 00:00:00'),
                $this->createTimeCondition()->setFromTime(0)->setUntilTime(24 * 3600)
            ),
            'weekday "0" is sunday' => array(
                true,
                new \DateTime('2015-07-05 00:00:00'),
                $this->createTimeCondition()->setWeekday(0)
            ),
            'weekday "7" is not sunday' => array(
                false,
                new \DateTime('2015-07-05 12:00:00'),
                $this->createTimeCondition()->setWeekday(7)
            ),
            'fails on wrong weekday' => array(
                false,
                new \DateTime('2015-07-23 12:00:00'),
                $this->createTimeCondition()->setWeekday(3)
            ),
            'fails if already another weekday' => array(
                false,
                new \DateTime('2015-07-22 24:00:00'),
                $this->createTimeCondition()->setWeekday(3)
            ),
            'ok if weekday already started' => array(
                true,
                new \DateTime('2015-07-22 00:00:00'),
                $this->createTimeCondition()->setWeekday(3)
            ),
            'checks day' => array(
                true,
                new \DateTime('2015-07-23 12:00:00'),
                $this->createTimeCondition()->setDay(23)
            ),
            'fails on wrong day' => array(
                false,
                new \DateTime('2015-07-23 12:00:00'),
                $this->createTimeCondition()->setDay(22)
            ),
            'fails if already another day' => array(
                false,
                new \DateTime('2015-07-22 24:00:00'),
                $this->createTimeCondition()->setDay(22)
            ),
            'ok if day already started' => array(
                true,
                new \DateTime('2015-07-22 00:00:00'),
                $this->createTimeCondition()->setDay(22)
            ),
            'checks both weekday and day' => array(
                true,
                new \DateTime('2015-07-22 12:00:00'),
                $this->createTimeCondition()->setDay(22)->setWeekday(3)
            ),
            'checks both weekday and day #2' => array(
                false,
                new \DateTime('2015-07-22 12:00:00'),
                $this->createTimeCondition()->setDay(21)->setWeekday(3)
            ),
            'checks both weekday and day #3' => array(
                false,
                new \DateTime('2015-07-22 12:00:00'),
                $this->createTimeCondition()->setDay(22)->setWeekday(2)
            ),
            'checks both month and day' => array(
                true,
                new \DateTime('2015-07-22 12:00:00'),
                $this->createTimeCondition()->setDay(22)->setMonth(7)
            ),
            'checks both month and day #2' => array(
                false,
                new \DateTime('2015-07-22 12:00:00'),
                $this->createTimeCondition()->setDay(21)->setMonth(7)
            ),
            'checks both month and day #3' => array(
                false,
                new \DateTime('2015-07-22 12:00:00'),
                $this->createTimeCondition()->setDay(22)->setMonth(6)
            ),
            'checks month, day and weekday' => array(
                true,
                new \DateTime('2015-07-23 12:00:00'),
                $this->createTimeCondition()->setDay(23)->setMonth(7)->setWeekday(4)
            ),
            'checks month, day and weekday #2' => array(
                false,
                new \DateTime('2015-07-23 12:00:00'),
                $this->createTimeCondition()->setDay(23)->setMonth(7)->setWeekday(2)
            ),
            'checks year, month and day' => array(
                true,
                new \DateTime('2015-07-23 12:00:00'),
                $this->createTimeCondition()->setDay(23)->setMonth(7)->setYear(2015)
            ),
            'checks year, month and day #2' => array(
                false,
                new \DateTime('2015-07-23 12:00:00'),
                $this->createTimeCondition()->setDay(23)->setMonth(7)->setYear(2014)
            ),
            'matches on empty condition (no restrictions)' => array(
                true,
                new \DateTime('2015-07-23 12:00:00'),
                $this->createTimeCondition()
            ),
            'checks time with weekday' => array(
                true,
                new \DateTime('2015-07-23 12:00:00'),
                $this->createTimeCondition()->setWeekday(4)->setFromTime(11 * 3600)->setUntilTime(13 * 3600)
            ),
            'checks time with weekday #2' => array(
                false,
                new \DateTime('2015-07-23 12:00:00'),
                $this->createTimeCondition()->setWeekday(4)->setFromTime(13 * 3600)->setUntilTime(14 * 3600)
            ),
            'checks time with weekday #3' => array(
                false,
                new \DateTime('2015-07-23 12:00:00'),
                $this->createTimeCondition()->setWeekday(3)->setFromTime(11 * 3600)->setUntilTime(13 * 3600)
            ),
            'checks everything' => array(
                false,
                new \DateTime('2015-07-23 23:15:00'),
                $this->createTimeCondition()
                    ->setYear(2015)
                    ->setMonth(7)
                    ->setDay(23)
                    ->setWeekday(4)
                    ->setFromTime(23 * 3600)
                    ->setUntilTime(23 * 3600 + 30)
            ),
            'checks anything' => array(
                false,
                new \DateTime('2015-07-23 23:15:00'),
                $this->createTimeCondition()
                    ->setYear(2014)
                    ->setMonth(6)
                    ->setDay(22)
                    ->setWeekday(3)
                    ->setFromTime(22 * 3600)
                    ->setUntilTime(22 * 3600 + 30)
            ),
            'does not look at timezone' => array(
                true,
                new \DateTime('2015-07-23 23:00:00', new \DateTimeZone('Europe/Vilnius')),
                $this->createTimeCondition()->setFromTime(23 * 3600)->setUntilTime(23 * 3600 + 1)
            ),
            'does not look at timezone #2' => array(
                true,
                new \DateTime('2015-07-23 23:00:00', new \DateTimeZone('Europe/London')),
                $this->createTimeCondition()->setFromTime(23 * 3600)->setUntilTime(23 * 3600 + 1)
            ),
        );
    }

    private function createTimeCondition()
    {
        return new TimeCondition();
    }
}
