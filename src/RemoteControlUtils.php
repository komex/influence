<?php
/**
 * This file is a part of influence project.
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
        if (empty(self::$newInstances[$className]) === true) {
            self::$newInstances[$className] = new Manifest();
        }

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
            if (self::hasNewInstance($object) === true) {
                self::$objects[$hash] = clone self::getNewInstance($object);
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
     *
     * @return bool
     */
    public static function hasStatic($class)
    {
        return isset(self::$classes[self::getClassName($class)]);
    }

    /**
     * @param object|string $class
     *
     * @return bool
     */
    public static function hasNewInstance($class)
    {
        return isset(self::$newInstances[self::getClassName($class)]);
    }

    /**
     * @param object $object
     *
     * @return bool
     */
    public static function hasObject($object)
    {
        if (empty(self::$objects[self::getObjectHash($object)]) === true) {
            return self::hasNewInstance($object);
        } else {
            return true;
        }
    }

    /**
     * @return int
     */
    public static function countStatic()
    {
        return count(self::$classes);
    }

    /**
     * @return int
     */
    public static function countObjects()
    {
        return count(self::$objects);
    }

    /**
     * @return int
     */
    public static function countNewInstances()
    {
        return count(self::$newInstances);
    }

    /**
     * @return int
     */
    public static function count()
    {
        return self::countStatic() + self::countObjects() + self::countNewInstances();
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
