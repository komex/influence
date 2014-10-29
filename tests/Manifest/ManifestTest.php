<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Test\Influence\Manifest;

use Influence\Manifest\Manifest;

/**
 * Class ManifestTest
 *
 * @package Test\Influence
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ManifestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test no methods by default.
     */
    public function testNoMethodsByDefault()
    {
        $this->assertCount(0, new Manifest());
    }

    /**
     * Test create method manifest only once for each method.
     */
    public function testGetMethod()
    {
        $manifest = new Manifest();
        for ($i = 0; $i < 3; $i++) {
            $this->assertInstanceOf('Influence\\Manifest\\MethodManifest', $manifest->get('method1'));
            $this->assertCount(1, $manifest);
        }
        for ($i = 0; $i < 3; $i++) {
            $this->assertInstanceOf('Influence\\Manifest\\MethodManifest', $manifest->get('method2'));
            $this->assertCount(2, $manifest);
        }
    }

    /**
     * Test check method exists.
     */
    public function testHasMethod()
    {
        $manifest = new Manifest();
        $this->assertFalse($manifest->has('method1'));
        $this->assertFalse($manifest->has('method2'));
        $manifest->get('method1');
        $this->assertTrue($manifest->has('method1'));
        $this->assertFalse($manifest->has('method2'));
    }

    /**
     * Test remove method from manifest.
     */
    public function testRemoveMethod()
    {
        $manifest = new Manifest();
        $manifest->get('method1');
        $manifest->get('method2');
        $manifest->remove('method1');
        $this->assertFalse($manifest->has('method1'));
        $this->assertTrue($manifest->has('method2'));
    }
}
