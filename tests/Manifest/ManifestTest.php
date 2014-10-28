<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Test\Influence\Manifest;

use Influence\Manifest\Manifest;
use Test\Influence\SimpleClass;

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
     * Test disabled calls registration.
     */
    public function testDisabledCallsRegister()
    {
        $manifest = new Manifest();
        $this->assertInternalType('array', $manifest->getAllCalls());
        $this->assertCount(0, $manifest);

        $manifest->registerCall('method', []);
        $this->assertCount(0, $manifest);

        $manifest->registerCalls(false);
        $manifest->registerCall('method2', ['args' => 4]);
        $this->assertCount(0, $manifest);
    }

    /**
     * Test calls registration works fine.
     */
    public function testRegisterCall()
    {
        $manifest = new Manifest();
        $manifest->registerCalls(true);
        $manifest->registerCall('method', []);
        $manifest->registerCall('method2', ['args' => true]);
        $this->assertSame([['method', []], ['method2', ['args' => true]]], $manifest->getAllCalls());

        $manifest->registerCalls(false);
        $manifest->registerCall('method3', []);
        $this->assertSame([['method', []], ['method2', ['args' => true]]], $manifest->getAllCalls());
    }

    /**
     * Test filtering methods calls.
     */
    public function testGetMethodCalls()
    {
        $manifest = new Manifest();
        $manifest->registerCalls(true);
        $manifest->registerCall('method', []);
        $manifest->registerCall('method2', ['args' => true]);
        $manifest->registerCall('method3', []);
        $manifest->registerCall('method', ['abc' => 4]);

        $this->assertSame(2, $manifest->getCallsCount('method'));
        $this->assertSame(1, $manifest->getCallsCount('method2'));
        $this->assertSame([[], ['abc' => 4]], $manifest->getCalls('method'));
        $this->assertSame([['args' => true]], $manifest->getCalls('method2'));
    }

    /**
     * Test we can clear methods calls.
     */
    public function testClearAllRegisteredCalls()
    {
        $manifest = new Manifest();
        $manifest->registerCalls(true);
        $manifest->registerCall('method', []);
        $this->assertNotEmpty($manifest->getAllCalls());
        $manifest->clearAllCalls();
        $this->assertEmpty($manifest->getAllCalls());
    }

    /**
     * Test we can clear specified method calls.
     */
    public function testClearMethodCalls()
    {
        $manifest = new Manifest();
        $manifest->registerCalls(true);
        $manifest->registerCall('method', ['a' => 'method']);
        $manifest->registerCall('method2', ['a' => 'method2']);

        $manifest->clearCalls('method');
        $this->assertSame(0, $manifest->getCallsCount('method'));
        $this->assertSame(1, $manifest->getCallsCount('method2'));
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
     * Test return to default method behavior.
     */
    public function testDefaultReturn()
    {
        $manifest = new Manifest();
        $manifest->setReturn('method', 5);
        $manifest->setReturn('method2', 6);
        $this->assertTrue($manifest->intercept('method'));
        $this->assertTrue($manifest->intercept('method2'));
        $manifest->setDefault('method');
        $this->assertFalse($manifest->intercept('method'));
        $this->assertTrue($manifest->intercept('method2'));
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
        $object = new SimpleClass();
        $manifest->setReturn(
            'method',
            function ($a, $b) {
                $this->a = 5;

                return $a + $b + $this->a;
            }
        );
        $this->assertSame(12, $manifest->call('method', [3, 4], $object));
        $manifest->setReturn(
            'method',
            function () {
                return $this->a;
            }
        );
        $this->assertSame(5, $manifest->call('method', [], $object));
    }
}
