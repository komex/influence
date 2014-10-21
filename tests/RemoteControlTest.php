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
     * Test checking object status.
     */
    public function testIsUnderControl()
    {
        $className = 'SomeClassName';
        $object1 = new \stdClass();
        $object2 = new \stdClass();

        $this->assertFalse(RC::isUnderControl($className));
        $this->assertFalse(RC::isUnderControl($object1));
        $this->assertFalse(RC::isUnderControl($object2));

        RC::control($className);
        RC::control($object1);

        $this->assertTrue(RC::isUnderControl($className));
        $this->assertTrue(RC::isUnderControl($object1));
        $this->assertFalse(RC::isUnderControl($object2));
    }

    /**
     * Test removing object from control.
     */
    public function testRemoveControl()
    {
        $className = 'SomeClassName';
        $object1 = new \stdClass();
        $object2 = new \stdClass();

        RC::control($className);
        RC::control($object1);
        RC::control($object2);

        $this->assertTrue(RC::isUnderControl($className));
        $this->assertTrue(RC::isUnderControl($object1));
        $this->assertTrue(RC::isUnderControl($object2));

        RC::removeControl($className);
        RC::removeControl($object1);

        $this->assertFalse(RC::isUnderControl($className));
        $this->assertFalse(RC::isUnderControl($object1));
        $this->assertTrue(RC::isUnderControl($object2));
    }

    /**
     * Test getting right Manifest object.
     */
    public function testControl()
    {
        $className = 'SomeClassName';
        $object1 = new \stdClass();
        $object2 = new \stdClass();

        $this->assertFalse(RC::control($className)->intercept('method'));
        $this->assertFalse(RC::control($object1)->intercept('method'));
        $this->assertFalse(RC::control($object2)->intercept('method'));

        RC::control($className)->setReturn('method', 5);
        RC::control($object2)->setReturn('method', 5);

        $this->assertTrue(RC::control($className)->intercept('method'));
        $this->assertFalse(RC::control($object1)->intercept('method'));
        $this->assertTrue(RC::control($object2)->intercept('method'));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Target must be an object or string class name.
     */
    public function testInvalidTarget()
    {
        RC::isUnderControl(5);
    }
}
