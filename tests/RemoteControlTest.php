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
     * Test getting class name.
     */
    public function testGetClassName()
    {
        $method = new \ReflectionMethod('Influence\RemoteControl', 'getClassName');
        $method->setAccessible(true);
        $this->assertSame(self::SIMPLE_CLASS_NAME, $method->invoke(null, self::SIMPLE_CLASS_NAME));
        $this->assertSame(self::SIMPLE_CLASS_NAME, $method->invoke(null, '\\' . self::SIMPLE_CLASS_NAME));
        $this->assertSame(self::SIMPLE_CLASS_NAME, $method->invoke(null, new SimpleClass()));
    }

    /**
     * Test getting invalid class name.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Target must be an object or string of class name.
     *
     * @dataProvider dpControlStaticInvalidArgument
     */
    public function testGetClassNameInvalid($target)
    {
        $method = new \ReflectionMethod('Influence\RemoteControl', 'getClassName');
        $method->setAccessible(true);
        $this->assertSame(self::SIMPLE_CLASS_NAME, $method->invoke(null, $target));
    }

    /**
     * Test default behavior of synthetic class.
     */
    public function testDefaultClassBehavior()
    {
        $class = new SimpleClass();
        $this->assertSame(self::SIMPLE_CLASS_NAME . '::method', $class->method());
        $this->assertSame(self::SIMPLE_CLASS_NAME . '::staticMethod', $class->staticMethod());
        $this->assertFalse(RC::isUnderControlObject($class, 'method'));
        $this->assertFalse(RC::isUnderControlStatic(self::SIMPLE_CLASS_NAME, 'staticMethod'));
    }

    /**
     * Test control static methods with specify class name of object.
     */
    public function testControlStaticObject()
    {
        $class = new SimpleClass();
        RC::controlStatic($class)->setReturn('staticMethod', 5);
        $this->assertSame(5, SimpleClass::staticMethod());
        RC::removeControlStatic(self::SIMPLE_CLASS_NAME);
    }

    /**
     * Test control static methods with specify class name.
     */
    public function testControlStaticClassName()
    {
        RC::controlStatic(self::SIMPLE_CLASS_NAME)->setReturn('staticMethod', 5);
        $this->assertSame(5, SimpleClass::staticMethod());
        RC::controlStatic('\\' . self::SIMPLE_CLASS_NAME)->setReturn('staticMethod', 6);
        $this->assertSame(6, SimpleClass::staticMethod());
        RC::removeControlStatic(self::SIMPLE_CLASS_NAME);
    }

    /**
     * @return array
     */
    public function dpControlStaticInvalidArgument()
    {
        return [
            [5],
            [[1 => true]],
            [.2],
            ['NotExistentClassName']
        ];
    }

    /**
     * Test incoming arguments for controlStatic.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Target must be an object or string of class name.
     *
     * @dataProvider dpControlStaticInvalidArgument
     */
    public function testControlStaticInvalidArgument($target)
    {
        RC::controlStatic($target);
    }
}
