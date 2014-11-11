<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Test\Influence\ReturnStrategy;

use Influence\ReturnStrategy\Callback;

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
        $return = new Callback(
            function () {
            }
        );
        $this->assertInstanceOf('Influence\\ReturnStrategy\\ReturnInterface', $return);
        $this->assertInstanceOf('Influence\\ReturnStrategy\\ArgumentsInterface', $return);
    }

    /**
     * Test getting value.
     */
    public function testGetValue()
    {
        $return = new Callback(
            function () {
                return array_reverse(func_get_args());
            }
        );
        $return->setArguments(['a', 1, true, [0.5]]);
        $this->assertSame([[0.5], true, 1, 'a'], $return->getValue());
    }

    /**
     * Test default scope.
     */
    public function testScope()
    {
        $return = new Callback(
            function () {
                return $this;
            }
        );
        $this->assertSame($this, $return->getValue());
    }
}
