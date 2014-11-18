<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Test\Influence\Manifest;

use Influence\Manifest\MethodManifest;
use Influence\ReturnStrategy\Callback;
use Influence\ReturnStrategy\CallbackScope;
use Influence\ReturnStrategy\Value;
use Test\Influence\SimpleClass;

/**
 * Class MethodManifestTest
 *
 * @package Test\Influence\Manifest
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class MethodManifestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test log disabled by default.
     */
    public function testLogDefault()
    {
        $manifest = new MethodManifest();
        $manifest->writeLog([1]);
        $logs = $manifest->getLogs();
        $this->assertInternalType('array', $logs);
        $this->assertEmpty($logs);
    }

    /**
     * Test enabled log.
     */
    public function testLogEnabled()
    {
        $manifest = new MethodManifest();
        $manifest->setLog(true);
        $manifest->writeLog([1]);
        $manifest->writeLog(['a']);
        $this->assertSame([[1], ['a']], $manifest->getLogs());
    }

    /**
     * Test disabled log.
     */
    public function testLogDisabled()
    {
        $manifest = new MethodManifest();
        $manifest->setLog(true);
        $manifest->writeLog([1]);
        $manifest->setLog(false);
        $manifest->writeLog(['a']);
        $this->assertSame([[1]], $manifest->getLogs());
    }

    /**
     * Test clear all logs.
     */
    public function testClearLogs()
    {
        $manifest = new MethodManifest();
        $manifest->setLog(true);
        $manifest->writeLog([1]);
        $manifest->writeLog(['a']);
        $manifest->clearLogs();
        $logs = $manifest->getLogs();
        $this->assertInternalType('array', $logs);
        $this->assertEmpty($logs);
    }

    /**
     * Test no custom value by default.
     */
    public function testDefaultValue()
    {
        $manifest = new MethodManifest();
        $this->assertFalse($manifest->hasValue());
    }

    /**
     * @return array
     */
    public function dpGetValueScalar()
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
     * Test getValue with scalar data.
     *
     * @param mixed $data
     *
     * @dataProvider dpGetValueScalar
     */
    public function testGetValueScalar($data)
    {
        $manifest = new MethodManifest();
        $manifest->setValue(new Value($data));
        $this->assertTrue($manifest->hasValue());
        $this->assertSame($data, $manifest->getValue([], null));
    }

    /**
     * Test getValue with closure.
     */
    public function testGetValueClosure()
    {
        $manifest = new MethodManifest();
        $handler = new Callback(
            function ($first, $second) {
                return $first + $second + 1;
            }
        );
        $manifest->setValue($handler);
        $this->assertTrue($manifest->hasValue());
        $this->assertSame(6, $manifest->getValue([2, 3], null));
    }

    /**
     * Test getValue with scoped closure.
     */
    public function testGetValueClosureWithScope()
    {
        $class = new SimpleClass();
        $manifest = new MethodManifest();
        $handler = new CallbackScope(
            function ($first, $second) {
                $this->a = 4;

                return $first + $second + $this->a;
            }
        );
        $manifest->setValue($handler);
        $this->assertSame(9, $manifest->getValue([2, 3], $class));
        $this->assertSame(4, $class->getA());
    }

    /**
     * Test reset method intercept with reset custom value in manifest.
     */
    public function testUseDefaultValue()
    {
        $manifest = new MethodManifest();
        $manifest->setValue();
        $this->assertFalse($manifest->hasValue());
        $this->assertNull($manifest->getValue([], null));
    }
}
