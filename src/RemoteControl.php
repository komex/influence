<?php
/**
 * This file is a part of RemoteControl project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence;

/**
 * Class RemoteControl
 *
 * @package Influence
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class RemoteControl
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
    public static function controlStatic($class)
    {
        $class = self::getClassName($class);
        if (empty(self::$classes[$class])) {
            self::$classes[$class] = new Manifest();
        }

        return self::$classes[$class];
    }

    /**
     * @param object|string $class
     *
     * @return Manifest
     * @throws \InvalidArgumentException
     */
    public static function controlNewInstance($class)
    {
        $class = self::getClassName($class);
        self::$newInstances[$class] = new Manifest();

        return self::$newInstances[$class];
    }

    /**
     * @param object $object
     *
     * @return Manifest
     * @throws \InvalidArgumentException
     */
    public static function controlObject($object)
    {
        $hash = self::getObjectHash($object);
        if (empty(self::$objects[$hash])) {
            $class = self::getClassName($object);
            if (isset(self::$newInstances[$class])) {
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
    public static function removeControlStatic($class)
    {
        unset(self::$classes[self::getClassName($class)]);
    }

    /**
     * @param object|string $class
     */
    public static function removeControlNewInstance($class)
    {
        unset(self::$newInstances[self::getClassName($class)]);
    }

    /**
     * @param object $object
     *
     * @throws \InvalidArgumentException
     */
    public static function removeControlObject($object)
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
    public static function isUnderControlStatic($class, $method)
    {
        $class = self::getClassName($class);

        return (isset(self::$classes[$class]) and self::$classes[$class]->intercept($method));
    }

    /**
     * @param object $object
     * @param string $method
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    public static function isUnderControlObject($object, $method)
    {
        $hash = self::getObjectHash($object);
        if (empty(self::$objects[$hash])) {
            $class = $class = self::getClassName($object);

            return (isset(self::$newInstances[$class]) and self::$newInstances[$class]->intercept($method));
        } else {
            return self::$objects[$hash]->intercept($method);
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
        if (is_object($target)) {
            $class = ltrim(get_class($target), '\\');
        } elseif (is_string($target) and class_exists($target)) {
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
        if (is_object($object)) {
            return spl_object_hash($object);
        } else {
            throw new \InvalidArgumentException('Target must be an object.');
        }
    }
}
