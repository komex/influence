<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Test\Influence;

use Influence\RemoteControl as RC;

/**
 * Class RemoteControlTest
 *
 * @package Test\Influence
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class RemoteControlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Class name.
     */
    const SIMPLE_CLASS_NAME = 'Test\\Influence\\SimpleClass';

    /**
     * Test default behavior of synthetic class.
     */
    public function testDefaultClassBehavior()
    {
        $class = new SimpleClass();
        $this->assertSame(self::SIMPLE_CLASS_NAME . '::method', $class->method());
        $this->assertSame(self::SIMPLE_CLASS_NAME . '::staticMethod', $class->staticMethod());
    }

    /**
     * Test control method via class manifest.
     */
    public function testControlMethodViaClassManifest()
    {
        $class = new SimpleClass();
        RC::control(self::SIMPLE_CLASS_NAME)->setReturn('method', 'influence method');
        $this->assertSame('influence method', $class->method());
        RC::removeControl($class);
    }

    /**
     * Test control method via object manifest.
     */
    public function testControlMethodViaObjectManifest()
    {
        $class = new SimpleClass();
        RC::control($class)->setReturn('method', 'influence method');
        $this->assertSame('influence method', $class->method());
        RC::removeControl($class);
    }

    /**
     * Test hierarchy of method manifests.
     */
    public function testControlMethodHierarchy()
    {
        $class = new SimpleClass();
        RC::control($class)->setReturn('method', 'object method');
        RC::control(self::SIMPLE_CLASS_NAME)->setReturn('method', 'class method');
        $this->assertSame(
            'object method',
            $class->method(),
            'Object manifest must be more priority than class manifest.'
        );
        RC::removeControl($class);
    }

    /**
     * Test control of static methods via class manifest.
     */
    public function testControlStaticMethodViaClassManifest()
    {
        $class = new SimpleClass();
        RC::control(self::SIMPLE_CLASS_NAME)->setReturn('staticMethod', 'influence staticMethod');
        $this->assertSame('influence staticMethod', $class->staticMethod());
        $this->assertSame('influence staticMethod', $class::staticMethod());
        $this->assertSame('influence staticMethod', SimpleClass::staticMethod());
    }

    /**
     * Test control of static methods via object manifest.
     */
    public function testControlStaticMethodViaObjectManifest()
    {
        $class = new SimpleClass();
        RC::control($class)->setReturn('staticMethod', 'influence staticMethod');
        $errorMessage = 'Static methods may be overwritten only by its class, not object';
        $this->assertSame(self::SIMPLE_CLASS_NAME . '::staticMethod', $class->staticMethod(), $errorMessage);
        $this->assertSame(self::SIMPLE_CLASS_NAME . '::staticMethod', $class::staticMethod(), $errorMessage);
        $this->assertSame(self::SIMPLE_CLASS_NAME . '::staticMethod', SimpleClass::staticMethod(), $errorMessage);
        RC::removeControl($class);
    }

    /**
     * Test hierarchy of static method manifests.
     */
    public function testControlStaticMethodHierarchy()
    {
        $class = new SimpleClass();
        RC::control(self::SIMPLE_CLASS_NAME)->setReturn('staticMethod', 'class static method');
        RC::control($class)->setReturn('staticMethod', 'object static method');
        $errorMessage = 'Static methods may be overwritten only by its class, not object';
        $this->assertSame('class static method', $class->staticMethod(), $errorMessage);
        RC::removeControl($class);
    }

    /**
     * @return array
     */
    public function dpRemoveControl()
    {
        return [[true], [false]];
    }

    /**
     * Test remove control.
     *
     * @param bool $useClass
     *
     * @dataProvider dpRemoveControl
     */
    public function testRemoveControl($useClass)
    {
        $class = new SimpleClass();
        $target = ($useClass ? self::SIMPLE_CLASS_NAME : $class);
        $manifest = RC::control($target);
        $manifest->setReturn('method', 'influence method');
        $manifest->setReturn('staticMethod', 'influence static method');
        RC::removeControl($target);

        $this->assertSame(self::SIMPLE_CLASS_NAME . '::method', $class->method());
        $this->assertSame(self::SIMPLE_CLASS_NAME . '::staticMethod', $class->staticMethod());

        RC::removeControl($class);
    }

    /**
     * Test remove control of class with controlled object.
     */
    public function testRemoveControlOfClassWithObject()
    {
        $class = new SimpleClass();
        $manifest = RC::control($class);
        $manifest->setReturn('method', 'influence method');
        $manifest->setReturn('staticMethod', 'influence static method');
        RC::removeControl(self::SIMPLE_CLASS_NAME);

        $this->assertSame('influence method', $class->method());
        $this->assertSame(self::SIMPLE_CLASS_NAME . '::staticMethod', $class->staticMethod());

        RC::removeControl($class);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Target must be an object or string of class name.
     */
    public function testInvalidControlTarget()
    {
        RC::control(5);
    }

    /**
     * Remove control of static class.
     */
    protected function tearDown()
    {
        RC::removeControl(self::SIMPLE_CLASS_NAME);
    }
}
