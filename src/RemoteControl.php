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
     * @param object|string $target
     *
     * @return Manifest
     * @throws \InvalidArgumentException
     */
    public static function controlStatic($target)
    {
        $class = self::getClassName($target);
        if (empty(self::$classes[$class])) {
            self::$classes[$class] = new Manifest();
        }

        return self::$classes[$class];
    }

    /**
     * @param object|string $target
     *
     * @throws \InvalidArgumentException
     */
    public static function removeControlStatic($target)
    {
        unset(self::$classes[self::getClassName($target)]);
    }

    /**
     * @param string $class
     *
     * @return Manifest
     * @throws \InvalidArgumentException
     */
    public static function controlNewInstance($class)
    {
        $class = ltrim($class, '\\');
        if (class_exists($class, false)) {
            self::$newInstances[$class] = new Manifest();

            return self::$newInstances[$class];
        } else {
            throw new \InvalidArgumentException('Class ' . $class . ' does not exists.');
        }
    }

    /**
     * @param string $class
     */
    public static function removeControlNewInstance($class)
    {
        $class = ltrim($class, '\\');
        unset(self::$newInstances[$class]);
    }

    /**
     * @param object $object
     *
     * @return Manifest
     * @throws \InvalidArgumentException
     */
    public static function controlObject($object)
    {
        if (is_object($object)) {
            $hash = spl_object_hash($object);
            if (empty(self::$objects[$hash])) {
                $class = ltrim(get_class($object), '\\');
                if (isset(self::$newInstances[$class])) {
                    self::$objects[$hash] = clone self::$newInstances[$class];
                } else {
                    self::$objects[$hash] = new Manifest();
                }
            }

            return self::$objects[$hash];
        } else {
            throw new \InvalidArgumentException('Target must be an object.');
        }
    }

    /**
     * Test if static method is under control.
     *
     * @param string $class Class name
     * @param string $method Method name
     *
     * @return bool
     */
    public static function isUnderControlStatic($class, $method)
    {
        $class = ltrim($class, '\\');

        return (isset(self::$classes[$class]) and self::$classes[$class]->intercept($method));
    }

    /**
     * @param object|string $object
     * @param string $method
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    public static function isUnderControlObject($object, $method)
    {
        if (is_object($object)) {
            $hash = spl_object_hash($object);
            if (empty(self::$objects[$hash])) {
                $class = ltrim(get_class($object), '\\');

                return (isset(self::$newInstances[$class]) and self::$newInstances[$class]->intercept($method));
            } else {
                return self::$objects[$hash]->intercept($method);
            }
        } else {
            throw new \InvalidArgumentException('Target must be an object.');
        }
    }

    /**
     * @param object $object
     *
     * @throws \InvalidArgumentException
     */
    public static function removeControlObject($object)
    {
        if (is_object($object)) {
            $hash = spl_object_hash($object);
            unset(self::$objects[$hash]);
        } else {
            throw new \InvalidArgumentException('Target must be an object.');
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
}
