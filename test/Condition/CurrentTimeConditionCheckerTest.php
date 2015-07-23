<?php

namespace Maba\Component\Clock\Tests\Condition;

use Maba\Component\Clock\Condition\CurrentTimeConditionChecker;
use Maba\Component\Clock\Condition\TimeCondition;
use Maba\Component\Clock\Condition\TimeConditionChecker;
use Maba\Component\Clock\ClockInterface;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_MockObject_Builder_InvocationMocker as InvocationMocker;

class CurrentTimeConditionCheckerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param boolean $expected
     *
     * @dataProvider dataProvider
     */
    public function testCheckCondition($expected)
    {
        $currentDateTime = new \DateTime('2012-01-01 12:00:00');
        $condition = new TimeCondition();
        $condition->setWeekday(3)->setFromTime(22)->setUntilTime(123);

        /** @var MockObject|InvocationMocker|ClockInterface $clock */
        $clock = $this->getMock('Maba\Component\Clock\ClockInterface');
        $clock->expects($this->once())->method('getCurrentDateTime')->will($this->returnValue($currentDateTime));

        /** @var MockObject|InvocationMocker|TimeConditionChecker $timeConditionChecker */
        $timeConditionChecker = $this->getMock(
            'Maba\Component\Clock\Condition\TimeConditionChecker',
            array('checkCondition')
        );
        $timeConditionChecker
            ->expects($this->once())
            ->method('checkCondition')
            ->with($condition, $currentDateTime)
            ->will($this->returnValue($expected))
        ;

        $currentTimeConditionChecker = new CurrentTimeConditionChecker($clock, $timeConditionChecker);
        $this->assertEquals($expected, $currentTimeConditionChecker->checkCondition($condition));
    }

    public function dataProvider()
    {
        return array(
            array(true),
            array(false),
        );
    }
}
