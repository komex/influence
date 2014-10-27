<?php
/**
 * This file is a part of RemoteControl project.
 *
 * (c) Andrey Kolchenko <a.j.kolchenko@baltsoftservice.ru>
 */

namespace Influence;

/**
 * Class RemoteControl
 *
 * @package Influence
 * @author Andrey Kolchenko <a.j.kolchenko@baltsoftservice.ru>
 */
class RemoteControl
{
    /**
     * @var Manifest[]
     */
    private static $map = [];

    /**
     * @param object|string $target
     *
     * @return Manifest
     * @throws \InvalidArgumentException
     */
    public static function control($target)
    {
        if (is_object($target)) {
            $id = spl_object_hash($target);
            if (empty(self::$map[$id])) {
                $staticId = ltrim(get_class($target), '\\');
                if (isset(self::$map[$staticId])) {
                    self::$map[$id] = clone self::$map[$staticId];
                } else {
                    self::$map[$id] = new Manifest();
                }
            }
        } elseif (is_string($target)) {
            $id = ltrim($target, '\\');
            if (empty(self::$map[$id])) {
                self::$map[$id] = new Manifest();
            }
        } else {
            throw new \InvalidArgumentException('Target must be an object or string of class name.');
        }

        return self::$map[$id];
    }

    /**
     * @param object|string $target
     *
     * @param string $method
     *
     * @throws \InvalidArgumentException
     * @return bool
     */
    public static function isUnderControl($target, $method)
    {
        if (is_object($target)) {
            $id = spl_object_hash($target);
            if (empty(self::$map[$id])) {
                $id = ltrim(get_class($target), '\\');
            }
        } elseif (is_string($target)) {
            $id = ltrim($target, '\\');
        } else {
            throw new \InvalidArgumentException('Target must be an object or string of class name.');
        }

        return (isset(self::$map[$id]) and self::$map[$id]->intercept($method));
    }

    /**
     * @param object|string $target
     *
     * @throws \InvalidArgumentException
     */
    public static function removeControl($target)
    {
        if (is_object($target)) {
            $id = spl_object_hash($target);
        } elseif (is_string($target)) {
            $id = ltrim($target, '\\');
        } else {
            throw new \InvalidArgumentException('Target must be an object or string of class name.');
        }

        unset(self::$map[$id]);
    }
}
