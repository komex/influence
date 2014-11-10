<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Test\Influence;

use Influence\RemoteControl as RC;
use Influence\ReturnStrategy\ReturnValue;

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
     * Test getting hash of object.
     */
    public function testGetObjectHash()
    {
        $method = new \ReflectionMethod('Influence\\RemoteControl', 'getObjectHash');
        $method->setAccessible(true);
        $hash = $method->invoke(null, new SimpleClass());
        $this->assertInternalType('string', $hash);
        $this->assertNotEmpty($hash);
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
        $method = new \ReflectionMethod('Influence\RemoteControl', 'getObjectHash');
        $method->setAccessible(true);
        $method->invoke(null, $target);
    }

    /**
     * Test getting class name.
     */
    public function testGetClassName()
    {
        $method = new \ReflectionMethod('Influence\\RemoteControl', 'getClassName');
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
        $method = new \ReflectionMethod('Influence\RemoteControl', 'getClassName');
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

    /**
     * Test control static methods with specify class name of object.
     */
    public function testControlStaticObject()
    {
        $class = new SimpleClass();
        RC::getStatic($class)->get('staticMethod')->setValue(new ReturnValue(__FUNCTION__));
        $this->assertSame(__FUNCTION__, SimpleClass::staticMethod());
    }

    /**
     * Test control static methods with specify class name.
     */
    public function testControlStaticClassName()
    {
        RC::getStatic(self::SIMPLE_CLASS_NAME)->get('staticMethod')->setValue(new ReturnValue(__CLASS__));
        $this->assertSame(__CLASS__, SimpleClass::staticMethod());
        RC::getStatic('\\' . self::SIMPLE_CLASS_NAME)->get('staticMethod')->setValue(new ReturnValue(__FUNCTION__));
        $this->assertSame(__FUNCTION__, SimpleClass::staticMethod());
    }

    /**
     * Test remove control from class by its name.
     */
    public function testRemoveControlStaticClassName()
    {
        RC::getStatic(self::SIMPLE_CLASS_NAME)->get('staticMethod')->setValue(new ReturnValue(__FUNCTION__));
        RC::removeStatic(self::SIMPLE_CLASS_NAME);
        $this->assertFalse(RC::hasStatic(self::SIMPLE_CLASS_NAME, 'staticMethod'));
    }

    /**
     * Test remove control from class by object class name.
     */
    public function testRemoveControlStaticObject()
    {
        RC::getStatic(self::SIMPLE_CLASS_NAME)->get('staticMethod')->setValue(new ReturnValue(__FUNCTION__));
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
        RC::getStatic(self::SIMPLE_CLASS_NAME)->get('staticMethod')->setValue(new ReturnValue(__FUNCTION__));
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
     * Test control different objects.
     */
    public function testControlObject()
    {
        $class1 = new SimpleClass();
        $class2 = new SimpleClass();
        RC::getObject($class1)->get('method')->setValue(new ReturnValue(__FUNCTION__));
        RC::getObject($class2)->get('method')->setValue(new ReturnValue(__FUNCTION__));
        $this->assertSame(__FUNCTION__, $class1->method());
        $this->assertSame(__FUNCTION__, $class2->method());
        RC::removeObject($class1);
        RC::removeObject($class2);
    }

    /**
     * Test success create new instances with manifest found by class name.
     */
    public function testControlObjectNewInstanceClassName()
    {
        $class1 = new SimpleClass();
        RC::getNewInstance(self::SIMPLE_CLASS_NAME)->get('method')->setValue(new ReturnValue(__FUNCTION__));
        $class2 = new SimpleClass();
        $this->assertSame(__FUNCTION__, $class1->method());
        $this->assertSame(__FUNCTION__, $class2->method());
        RC::removeObject($class1);
        RC::removeObject($class2);
    }

    /**
     * Test success create new instances with manifest found by object class name.
     */
    public function testControlObjectNewInstanceObject()
    {
        $class1 = new SimpleClass();
        RC::getNewInstance($class1)->get('method')->setValue(new ReturnValue(__FUNCTION__));
        $class2 = new SimpleClass();
        $this->assertSame(__FUNCTION__, $class1->method());
        $this->assertSame(__FUNCTION__, $class2->method());
        RC::removeObject($class1);
        RC::removeObject($class2);
    }

    /**
     * Remove control from specified object.
     */
    public function testRemoveControlObject()
    {
        $class1 = new SimpleClass();
        $class2 = new SimpleClass();
        $return = new ReturnValue(__FUNCTION__);
        RC::getObject($class1)->get('method')->setValue($return);
        RC::getObject($class2)->get('method')->setValue($return);
        RC::removeObject($class1);
        $this->assertSame(self::SIMPLE_CLASS_NAME . '::method', $class1->method());
        $this->assertSame(__FUNCTION__, $class2->method());
        RC::removeObject($class2);
    }

    /**
     * Test remove control for new instances before cloning.
     */
    public function testRemoveControlNewInstanceBeforeClone()
    {
        $class = new SimpleClass();
        RC::getNewInstance(self::SIMPLE_CLASS_NAME)->get('method')->setValue(new ReturnValue(__FUNCTION__));
        RC::removeNewInstance($class);
        $this->assertSame(self::SIMPLE_CLASS_NAME . '::method', $class->method());
    }

    /**
     * Test remove control for new instances with cloning.
     */
    public function testRemoveControlNewInstanceAfterClone()
    {
        $class1 = new SimpleClass();
        $class2 = new SimpleClass();
        RC::getNewInstance(self::SIMPLE_CLASS_NAME)->get('method')->setValue(new ReturnValue(__FUNCTION__));
        $this->assertSame(__FUNCTION__, $class1->method());
        RC::removeNewInstance(self::SIMPLE_CLASS_NAME);
        $this->assertSame(self::SIMPLE_CLASS_NAME . '::method', $class2->method());
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
