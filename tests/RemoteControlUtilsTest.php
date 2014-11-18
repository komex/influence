<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Test\Influence;

use Influence\RemoteControlUtils as RC;

/**
 * Class RemoteControlUtilsTest
 *
 * @package Test\Influence
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class RemoteControlUtilsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Class name.
     */
    const SIMPLE_CLASS_NAME = 'Test\\Influence\\SimpleClass';

    /**
     * Test getting hash of object.
     */
    public function testGetObjectHash()
    {
        $method = new \ReflectionMethod('Influence\\RemoteControlUtils', 'getObjectHash');
        $method->setAccessible(true);
        $hash = $method->invoke(null, new SimpleClass());
        $this->assertInternalType('string', $hash);
        $this->assertNotEmpty($hash);
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
     * Test getting invalid class name.
     *
     * @param mixed $target
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Target must be an object.
     *
     * @dataProvider dpControlStaticInvalidArgument
     */
    public function testGetObjectHashInvalid($target)
    {
        $method = new \ReflectionMethod('Influence\\RemoteControlUtils', 'getObjectHash');
        $method->setAccessible(true);
        $method->invoke(null, $target);
    }

    /**
     * Test getting class name.
     */
    public function testGetClassName()
    {
        $method = new \ReflectionMethod('Influence\\RemoteControlUtils', 'getClassName');
        $method->setAccessible(true);
        $this->assertSame(self::SIMPLE_CLASS_NAME, $method->invoke(null, self::SIMPLE_CLASS_NAME));
        $this->assertSame(self::SIMPLE_CLASS_NAME, $method->invoke(null, '\\' . self::SIMPLE_CLASS_NAME));
        $this->assertSame(self::SIMPLE_CLASS_NAME, $method->invoke(null, new SimpleClass()));
    }

    /**
     * Test getting invalid class name.
     *
     * @param mixed $target
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Target must be an object or string of class name.
     *
     * @dataProvider dpControlStaticInvalidArgument
     */
    public function testGetClassNameInvalid($target)
    {
        $method = new \ReflectionMethod('Influence\\RemoteControlUtils', 'getClassName');
        $method->setAccessible(true);
        $method->invoke(null, $target);
    }

    /**
     * Test default behavior of synthetic class.
     */
    public function testDefaultClassBehavior()
    {
        $class = new SimpleClass();
        $this->assertSame(self::SIMPLE_CLASS_NAME . '::method', $class->method());
        $this->assertSame(self::SIMPLE_CLASS_NAME . '::staticMethod', $class->staticMethod());
        $this->assertFalse(RC::hasObject($class, 'method'));
        $this->assertFalse(RC::hasStatic(self::SIMPLE_CLASS_NAME, 'staticMethod'));
    }
}
