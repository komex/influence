<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace ReturnStrategy;

use Influence\ReturnStrategy\ReturnCallbackScope;

/**
 * Class ReturnCallbackScopeTest
 *
 * @package ReturnStrategy
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ReturnCallbackScopeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test strategy implements interfaces.
     */
    public function testImplements()
    {
        $return = new ReturnCallbackScope(
            function () {
            }
        );
        $this->assertInstanceOf('Influence\\ReturnStrategy\\ReturnInterface', $return);
        $this->assertInstanceOf('Influence\\ReturnStrategy\\UseArgsReturnInterface', $return);
        $this->assertInstanceOf('Influence\\ReturnStrategy\\UseScopeReturnInterface', $return);
    }

    /**
     * Test getting value.
     */
    public function testGetValue()
    {
        $return = new ReturnCallbackScope(
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
        $return = new ReturnCallbackScope(
            function ($firstProperty) {
                $this->testProperty = $firstProperty;

                return $this;
            }
        );
        $testObject = new \stdClass();
        $this->assertObjectNotHasAttribute('testProperty', $testObject);
        $return->setScope($testObject);
        $return->setArguments(['a', 1, true, [0.5]]);

        $this->assertSame($testObject, $return->getValue());
        $this->assertObjectHasAttribute('testProperty', $testObject);
        $this->assertSame('a', $testObject->testProperty);
    }
}
