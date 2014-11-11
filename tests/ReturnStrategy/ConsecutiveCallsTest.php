<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Test\Influence\ReturnStrategy;

use Influence\ReturnStrategy\ConsecutiveCalls;

/**
 * Class ConsecutiveCallsTest
 *
 * @package Test\Influence\ReturnStrategy
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ConsecutiveCallsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test getting value.
     */
    public function testGetValue()
    {
        $object = new ConsecutiveCalls([]);
        $strategy = new ConsecutiveCalls([1, 'a', true, $object]);
        $this->assertSame(1, $strategy->getValue());
        $this->assertSame('a', $strategy->getValue());
        $this->assertTrue($strategy->getValue());
        $this->assertSame($object, $strategy->getValue());
        $this->assertNull($strategy->getValue());
    }
}
