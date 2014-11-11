<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Test\Influence\ReturnStrategy;

use Influence\ReturnStrategy\CallbackScope;
use Influence\ReturnStrategy\ConsecutiveCallsCallback;
use Influence\ReturnStrategy\Value;

/**
 * Class ConsecutiveCallsCallbackTest
 *
 * @package Test\Influence\ReturnStrategy
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ConsecutiveCallsCallbackTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var int
     */
    private $testVariable = 41;

    /**
     * Test get value with callback.
     */
    public function testGetValue()
    {
        $object = new \stdClass();
        $strategy = new ConsecutiveCallsCallback(
            [
                1,
                'a',
                true,
                $object,
                new Value('Strategy value'),
                new CallbackScope(
                    function ($first, $second) {
                        return 'Args: ' . $second . ' ' . $first . ' ' . $this->testVariable;
                    }
                )
            ]
        );
        $strategy->setArguments(['first', 'second']);
        $strategy->setScope($this);
        $this->assertSame(1, $strategy->getValue());
        $this->assertSame('a', $strategy->getValue());
        $this->assertTrue($strategy->getValue());
        $this->assertSame($object, $strategy->getValue());
        $this->assertSame('Strategy value', $strategy->getValue());
        $this->assertSame('Args: second first 41', $strategy->getValue());
        $this->assertNull($strategy->getValue());
    }
}
