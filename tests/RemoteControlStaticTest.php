<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Test\Influence;

use Influence\RemoteControl as RC;
use Influence\ReturnStrategy\Value;

/**
 * Class RemoteControlStaticTest
 *
 * @package Test\Influence
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class RemoteControlStaticTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Class name.
     */
    const SIMPLE_CLASS_NAME = 'Test\\Influence\\SimpleClass';

    /**
     * Test control static methods with specify class name of object.
     */
    public function testControlStaticObject()
    {
        $class = new SimpleClass();
        RC::getStatic($class)->getMethod('staticMethod')->setValue(new Value(__FUNCTION__));
        $this->assertSame(__FUNCTION__, SimpleClass::staticMethod());
    }

    /**
     * Test control static methods with specify class name.
     */
    public function testControlStaticClassName()
    {
        RC::getStatic(self::SIMPLE_CLASS_NAME)->getMethod('staticMethod')->setValue(new Value(__CLASS__));
        $this->assertSame(__CLASS__, SimpleClass::staticMethod());
        RC::getStatic('\\' . self::SIMPLE_CLASS_NAME)->getMethod('staticMethod')->setValue(new Value(__FUNCTION__));
        $this->assertSame(__FUNCTION__, SimpleClass::staticMethod());
    }

    /**
     * Test remove control from class by its name.
     */
    public function testRemoveControlStaticClassName()
    {
        RC::getStatic(self::SIMPLE_CLASS_NAME)->getMethod('staticMethod')->setValue(new Value(__FUNCTION__));
        RC::removeStatic(self::SIMPLE_CLASS_NAME);
        $this->assertFalse(RC::hasStatic(self::SIMPLE_CLASS_NAME, 'staticMethod'));
    }

    /**
     * Test remove control from class by object class name.
     */
    public function testRemoveControlStaticObject()
    {
        RC::getStatic(self::SIMPLE_CLASS_NAME)->getMethod('staticMethod')->setValue(new Value(__FUNCTION__));
        RC::removeStatic(new SimpleClass());
        $this->assertFalse(RC::hasStatic(self::SIMPLE_CLASS_NAME, 'staticMethod'));
    }

    /**
     * Test if method is under control.
     */
    public function testIsUnderControlStatic()
    {
        $class = new SimpleClass();
        $this->assertFalse(RC::hasStatic(self::SIMPLE_CLASS_NAME, 'staticMethod'));
        $this->assertFalse(RC::hasStatic($class, 'staticMethod'));
        RC::getStatic(self::SIMPLE_CLASS_NAME)->getMethod('staticMethod')->setValue(new Value(__FUNCTION__));
        $this->assertTrue(RC::hasStatic(self::SIMPLE_CLASS_NAME, 'staticMethod'));
        $this->assertTrue(RC::hasStatic($class, 'staticMethod'));
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
     * Test incoming arguments for getStatic.
     *
     * @param mixed $target
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Target must be an object or string of class name.
     *
     * @dataProvider dpControlStaticInvalidArgument
     */
    public function testControlStaticInvalidArgument($target)
    {
        RC::getStatic($target);
    }

    /**
     * Cleanup.
     */
    protected function tearDown()
    {
        RC::removeStatic(self::SIMPLE_CLASS_NAME);
        RC::removeNewInstance(self::SIMPLE_CLASS_NAME);
    }
}
