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
    }

    /**
     * Test calls registration works fine.
     */
    public function testRegisterCall()
    {
        $manifest = new Manifest();
        $this->assertInternalType('array', $manifest->getCalls());
        $this->assertEmpty($manifest->getCalls());

        $manifest->registerCall('method', []);
        $this->assertEmpty($manifest->getCalls());

        $manifest->registerCalls(true);
        $manifest->registerCall('method', []);
        $manifest->registerCall('method2', ['args' => true]);
        $this->assertSame([['method', []], ['method2', ['args' => true]]], $manifest->getCalls());

        $manifest->registerCalls(false);
        $manifest->registerCall('method3', []);
        $this->assertSame([['method', []], ['method2', ['args' => true]]], $manifest->getCalls());
    }

    /**
     * Test we can clear methods calls.
     */
    public function testClearRegisteredCalls()
    {
        $manifest = new Manifest();
        $manifest->registerCalls(true);
        $manifest->registerCall('method', []);
        $this->assertNotEmpty($manifest->getCalls());
        $manifest->clearCalls();
        $this->assertEmpty($manifest->getCalls());
    }
}
 