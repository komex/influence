<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Test\Influence;

use Influence\Manifest;

/**
 * Class ManifestTest
 *
 * @package Test\Influence
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ManifestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test successful check intercept mode.
     */
    public function testIntercept()
    {
        $manifest = new Manifest();
        $this->assertFalse($manifest->intercept('method1'));
        $this->assertFalse($manifest->intercept('method2'));

        $manifest->setReturn('method1', 41);

        $this->assertTrue($manifest->intercept('method1'));
        $this->assertFalse($manifest->intercept('method2'));

        $manifest->setReturn('method2', false);
        $this->assertTrue($manifest->intercept('method2'));
    }

    /**
     * Test calls registration works fine.
     */
    public function testRegisterCall()
    {
        $manifest = new Manifest();
        $this->assertInternalType('array', $manifest->getAllCalls());
        $this->assertEmpty($manifest->getAllCalls());

        $manifest->registerCall('method', []);
        $this->assertEmpty($manifest->getAllCalls());

        $manifest->registerCalls(true);
        $manifest->registerCall('method', []);
        $manifest->registerCall('method2', ['args' => true]);
        $this->assertSame([['method', []], ['method2', ['args' => true]]], $manifest->getAllCalls());

        $manifest->registerCalls(false);
        $manifest->registerCall('method3', []);
        $this->assertSame([['method', []], ['method2', ['args' => true]]], $manifest->getAllCalls());
    }

    /**
     * Test we can clear methods calls.
     */
    public function testClearRegisteredCalls()
    {
        $manifest = new Manifest();
        $manifest->registerCalls(true);
        $manifest->registerCall('method', []);
        $this->assertNotEmpty($manifest->getAllCalls());
        $manifest->clearAllCalls();
        $this->assertEmpty($manifest->getAllCalls());
    }

    /**
     * Test working with unexpected method call.
     */
    public function testCallUnexpectedMethod()
    {
        $manifest = new Manifest();
        $this->assertNull($manifest->call('method', ['arg' => true]));
    }

    /**
     * @return array
     */
    public function dpCallAndReturnScalarResult()
    {
        return [
            [9],
            [null],
            [true],
            [false],
            ['string'],
            [['a', 'r', 'r', 'a', 'y']],
            [new \stdClass()],
        ];
    }

    /**
     * @param mixed $value
     *
     * @dataProvider dpCallAndReturnScalarResult
     */
    public function testCallAndReturnScalarResult($value)
    {
        $manifest = new Manifest();
        $manifest->setReturn('method', $value);
        $this->assertSame($value, $manifest->call('method', ['some' => 'arguments']));
    }

    /**
     * Test call with simple Closure.
     */
    public function testCallWithClosure()
    {
        $manifest = new Manifest();
        $manifest->setReturn(
            'method',
            function ($a, $b) {
                return $a + $b;
            }
        );
        $this->assertSame(7, $manifest->call('method', [3, 4]));
    }

    /**
     * Test call with closure with scope.
     */
    public function testCallWithScopeClosure()
    {
        $manifest = new Manifest();
        $object = new \stdClass();
        $object->c = 5;
        $manifest->setReturn(
            'method',
            function ($a, $b) {
                $result = $a + $b + $this->c;
                $this->c = 6;

                return $result;
            }
        );
        $this->assertSame(5, $object->c);
        $this->assertSame(12, $manifest->call('method', [3, 4], $object));
        $this->assertSame(6, $object->c);
    }
}
 