<?php
/**
 * This file is a part of RemoteControlUtils project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence;

use Influence\Manifest\Manifest;

/**
 * Class RemoteControlUtils
 *
 * @package Influence
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class RemoteControlUtils
{
    /**
     * @var Manifest[]
     */
    private static $classes = [];
    /**
     * @var Manifest[]
     */
    private static $objects = [];
    /**
     * @var Manifest[]
     */
    private static $newInstances = [];

    /**
     * @param object|string $class
     *
     * @return Manifest
     * @throws \InvalidArgumentException
     */
    public static function getStatic($class)
    {
        $className = self::getClassName($class);
        if (empty(self::$classes[$className]) === true) {
            self::$classes[$className] = new Manifest();
        }

        return self::$classes[$className];
    }

    /**
     * @param object|string $class
     *
     * @return Manifest
     * @throws \InvalidArgumentException
     */
    public static function getNewInstance($class)
    {
        $className = self::getClassName($class);
        self::$newInstances[$className] = new Manifest();

        return self::$newInstances[$className];
    }

    /**
     * @param object $object
     *
     * @return Manifest
     * @throws \InvalidArgumentException
     */
    public static function getObject($object)
    {
        $hash = self::getObjectHash($object);
        if (empty(self::$objects[$hash]) === true) {
            $class = self::getClassName($object);
            if (isset(self::$newInstances[$class]) === true) {
                self::$objects[$hash] = clone self::$newInstances[$class];
            } else {
                self::$objects[$hash] = new Manifest();
            }
        }

        return self::$objects[$hash];
    }

    /**
     * @param object|string $class
     *
     * @throws \InvalidArgumentException
     */
    public static function removeStatic($class)
    {
        unset(self::$classes[self::getClassName($class)]);
    }

    /**
     * @param object|string $class
     */
    public static function removeNewInstance($class)
    {
        unset(self::$newInstances[self::getClassName($class)]);
    }

    /**
     * @param object $object
     *
     * @throws \InvalidArgumentException
     */
    public static function removeObject($object)
    {
        unset(self::$objects[self::getObjectHash($object)]);
    }

    /**
     * Test if static method is under control.
     *
     * @param object|string $class Class name
     * @param string $method Method name
     *
     * @return bool
     */
    public static function hasStatic($class, $method)
    {
        $className = self::getClassName($class);

        return (isset(self::$classes[$className]) && self::$classes[$className]->hasMethod($method));
    }

    /**
     * @param object $object
     * @param string $method
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    public static function hasObject($object, $method)
    {
        $hash = self::getObjectHash($object);
        if (empty(self::$objects[$hash]) === true) {
            $class = $class = self::getClassName($object);

            return (isset(self::$newInstances[$class]) && self::$newInstances[$class]->hasMethod($method));
        } else {
            return self::$objects[$hash]->hasMethod($method);
        }
    }

    /**
     * Get correct class name.
     *
     * @param object|string $target
     *
     * @return string Class name
     * @throws \InvalidArgumentException
     */
    private static function getClassName($target)
    {
        if (is_object($target) === true) {
            $class = ltrim(get_class($target), '\\');
        } elseif (is_string($target) === true && class_exists($target) === true) {
            $class = ltrim($target, '\\');
        } else {
            throw new \InvalidArgumentException('Target must be an object or string of class name.');
        }

        return $class;
    }

    /**
     * Get hash of object.
     *
     * @param object $object
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    private static function getObjectHash($object)
    {
        if (is_object($object) === true) {
            return spl_object_hash($object);
        } else {
            throw new \InvalidArgumentException('Target must be an object.');
        }
    }
}
