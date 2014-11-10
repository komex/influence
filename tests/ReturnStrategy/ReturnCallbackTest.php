<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Test\Influence\ReturnStrategy;

use Influence\ReturnStrategy\ReturnCallback;

/**
 * Class ReturnCallbackTest
 *
 * @package ReturnStrategy
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ReturnCallbackTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test strategy implements interfaces.
     */
    public function testImplements()
    {
        $return = new ReturnCallback(
            function () {
            }
        );
        $this->assertInstanceOf('Influence\\ReturnStrategy\\ReturnInterface', $return);
        $this->assertInstanceOf('Influence\\ReturnStrategy\\UseArgsReturnInterface', $return);
    }

    /**
     * Test getting value.
     */
    public function testGetValue()
    {
        $return = new ReturnCallback(
            function () {
                return array_reverse(func_get_args());
            }
        );
        $return->setArguments(['a', 1, true, [0.5]]);
        $this->assertSame([[0.5], true, 1, 'a'], $return->getValue());
    }
}
