<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Test\Influence\Manifest;

use Influence\Manifest\MethodManifest;

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
        $manifest->log([1]);
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
        $manifest->log([1]);
        $manifest->log(['a']);
        $this->assertSame([[1], ['a']], $manifest->getLogs());
    }

    /**
     * Test disabled log.
     */
    public function testLogDisabled()
    {
        $manifest = new MethodManifest();
        $manifest->setLog(true);
        $manifest->log([1]);
        $manifest->setLog(false);
        $manifest->log(['a']);
        $this->assertSame([[1]], $manifest->getLogs());
    }

    /**
     * Test clear all logs.
     */
    public function testClearLogs()
    {
        $manifest = new MethodManifest();
        $manifest->setLog(true);
        $manifest->log([1]);
        $manifest->log(['a']);
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
     * @dataProvider dpGetValueScalar
     */
    public function testGetValueScalar($data)
    {
        $manifest = new MethodManifest();
        $manifest->setValue($data);
        $this->assertTrue($manifest->hasValue());
        $this->assertSame($data, $manifest->getValue([], null));
    }
}
