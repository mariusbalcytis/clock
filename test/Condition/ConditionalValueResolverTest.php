<?php

namespace Maba\Component\Clock\Tests\Condition;

use Maba\Component\Clock\Condition\ConditionalValue;
use Maba\Component\Clock\Condition\ConditionalValueResolver;
use Maba\Component\Clock\Condition\ConditionPriorityResolver;
use Maba\Component\Clock\Condition\CurrentTimeConditionChecker;
use Maba\Component\Clock\Condition\TimeConditionInterface;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_MockObject_Builder_InvocationMocker as InvocationMocker;

class ConditionalValueResolverTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param mixed $expected
     * @param ConditionalValue[] $conditionalValues
     * @param mixed $default
     *
     * @dataProvider dataProvider
     */
    public function testResolveValue($expected, $conditionalValues, $default)
    {
        /** @var MockObject|InvocationMocker|ConditionPriorityResolver $conditionPriorityResolver */
        $conditionPriorityResolver = $this->getMock(
            'Maba\Component\Clock\Condition\ConditionPriorityResolver',
            array('getConditionPriority')
        );
        $conditionPriorityResolver
            ->method('getConditionPriority')
            ->will($this->returnCallback(function($condition) {
                return $condition->priority;
            }))
        ;

        /** @var MockObject|InvocationMocker|CurrentTimeConditionChecker $conditionChecker */
        $conditionChecker = $this->getMockBuilder('Maba\Component\Clock\Condition\CurrentTimeConditionChecker')
            ->setMethods(array('checkCondition'))
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $conditionChecker
            ->method('checkCondition')
            ->will($this->returnCallback(function($condition) {
                return $condition->matches;
            }))
        ;

        $conditionalValueResolver = new ConditionalValueResolver($conditionPriorityResolver, $conditionChecker);

        $this->assertSame($expected, $conditionalValueResolver->resolveValue($conditionalValues, $default));
    }

    public function dataProvider()
    {
        return array(
            'gives matching value' => array('a', array(
                $this->buildConditionalValue('a', 1, true),
            ), 'default'),
            'gives only matching value' => array('b', array(
                $this->buildConditionalValue('a', 2, false),
                $this->buildConditionalValue('b', 1, true),
            ), 'default'),
            'gives only matching value #2' => array('b', array(
                $this->buildConditionalValue('b', 1, true),
                $this->buildConditionalValue('a', 2, false),
            ), 'default'),
            'prioritizes' => array('a', array(
                $this->buildConditionalValue('b', 1, true),
                $this->buildConditionalValue('a', 2, true),
            ), 'default'),
            'prioritizes with different order' => array('a', array(
                $this->buildConditionalValue('a', 2, true),
                $this->buildConditionalValue('b', 1, true),
            ), 'default'),
            'gives first matching if priority is the same' => array('a', array(
                $this->buildConditionalValue('a', 2, true),
                $this->buildConditionalValue('b', 1, true),
                $this->buildConditionalValue('c', 2, true),
            ), 'default'),
            'gives first matching if priority is the same #2' => array('c', array(
                $this->buildConditionalValue('a', 1, false),
                $this->buildConditionalValue('b', 2, false),
                $this->buildConditionalValue('c', 3, true),
                $this->buildConditionalValue('d', 3, true),
                $this->buildConditionalValue('e', 3, true),
            ), 'default'),
            'gives default on no conditions' => array('default', array(), 'default'),
            'gives default if none matches' => array('default', array(
                $this->buildConditionalValue('a', 2, false),
                $this->buildConditionalValue('b', 1, false),
                $this->buildConditionalValue('c', 3, false),
            ), 'default'),
        );
    }

    private function buildConditionalValue($value, $priority, $matches)
    {
        /** @var TimeConditionInterface $condition */
        $condition = $this->getMock('Maba\Component\Clock\Condition\TimeConditionInterface');
        $condition->priority = $priority;
        $condition->matches = $matches;

        $conditionalValue = new ConditionalValue();
        $conditionalValue->setValue($value);
        $conditionalValue->setTimeCondition($condition);

        return $conditionalValue;
    }
}
