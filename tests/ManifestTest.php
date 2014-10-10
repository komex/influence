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
}
 