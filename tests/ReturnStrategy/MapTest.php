<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Test\Influence\ReturnStrategy;

use Influence\ReturnStrategy\Map;
use Influence\ReturnStrategy\Value;

/**
 * Class MapTest
 *
 * @package Test\Influence\ReturnStrategy
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class MapTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function dpGetValue()
    {
        return [
            [[], [1], null],
            [['a', 'b', 'c'], ['a'], null],
            [[['a', 'b'], ['a']], ['a', 'b'], null],
            [[['a', 'b'], ['a', 'b', 'c']], ['a', 'b'], 'c'],
            [[['a', 'b', new Value('c')], ['a', 'b', 'd']], ['a', 'b'], 'c'],
        ];
    }

    /**
     * Test getting value from Map strategy.
     *
     * @param array $valuesMap
     * @param array $arguments
     * @param mixed $expectedValue
     *
     * @dataProvider dpGetValue
     */
    public function testGetValue(array $valuesMap, array $arguments, $expectedValue)
    {
        $map = new Map($valuesMap);
        $map->setArguments($arguments);
        $map->setScope($this);
        $this->assertSame($expectedValue, $map->getValue());
    }
}
