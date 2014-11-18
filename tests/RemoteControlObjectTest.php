<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Test\Influence;

use Influence\RemoteControlUtils as RC;
use Influence\ReturnStrategy\Value;

/**
 * Class RemoteControlObjectTest
 *
 * @package Test\Influence
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class RemoteControlObjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Class name.
     */
    const SIMPLE_CLASS_NAME = 'Test\\Influence\\SimpleClass';

    /**
     * Test control different objects.
     */
    public function testControlObject()
    {
        $class1 = new SimpleClass();
        $class2 = new SimpleClass();
        RC::getObject($class1)->getMethod('method')->setValue(new Value(__FUNCTION__));
        RC::getObject($class2)->getMethod('method')->setValue(new Value(__FUNCTION__));
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
        RC::getNewInstance(self::SIMPLE_CLASS_NAME)->getMethod('method')->setValue(new Value(__FUNCTION__));
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
        RC::getNewInstance($class1)->getMethod('method')->setValue(new Value(__FUNCTION__));
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
        $return = new Value(__FUNCTION__);
        RC::getObject($class1)->getMethod('method')->setValue($return);
        RC::getObject($class2)->getMethod('method')->setValue($return);
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
        RC::getNewInstance(self::SIMPLE_CLASS_NAME)->getMethod('method')->setValue(new Value(__FUNCTION__));
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
        RC::getNewInstance(self::SIMPLE_CLASS_NAME)->getMethod('method')->setValue(new Value(__FUNCTION__));
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
