<?php

namespace Maba\Component\Clock\Tests;

use Maba\Component\Clock\ClockInterface;
use Maba\Component\Clock\TimeZonedClock;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_MockObject_Builder_InvocationMocker as InvocationMocker;

class TimeZonedClockTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param \DateTime $expected
     * @param \DateTime $innerDateTime
     * @param \DateTimeZone $timeZone
     *
     * @dataProvider dataProvider
     */
    public function testGetCurrentDateTime(\DateTime $expected, \DateTime $innerDateTime, \DateTimeZone $timeZone)
    {
        /** @var MockObject|InvocationMocker|ClockInterface $innerClock */
        $innerClock = $this->getMock('Maba\Component\Clock\ClockInterface');
        $innerClock->expects($this->once())->method('getCurrentDateTime')->will($this->returnValue($innerDateTime));
        $clock = new TimeZonedClock($innerClock, $timeZone);

        $this->assertEquals($expected, $clock->getCurrentDateTime());
    }

    public function dataProvider()
    {
        return array(
            array(
                new \DateTime('2000-01-01 14:00:00', new \DateTimeZone('Europe/Vilnius')),
                new \DateTime('2000-01-01 12:00:00', new \DateTimeZone('Europe/London')),
                new \DateTimeZone('Europe/Vilnius'),
            ),
            array(
                new \DateTime('2015-07-23 21:03:53', new \DateTimeZone('Europe/London')),
                new \DateTime('@1437681833'),
                new \DateTimeZone('Europe/London'),
            ),
        );
    }
}
