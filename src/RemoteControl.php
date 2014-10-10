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
     * @var array
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
        $id = self::getId($target);
        if (empty(self::$map[$id])) {
            self::$map[$id] = new Manifest();
        }

        return self::$map[$id];
    }

    /**
     * @param object|string $target
     *
     * @return bool
     */
    public static function isUnderControl($target)
    {
        $id = self::getId($target);

        return isset(self::$map[$id]);
    }

    /**
     * @param object|string $target
     */
    public static function removeControl($target)
    {
        $id = self::getId($target);
        unset(self::$map[$id]);
    }

    /**
     * @param object|string $target
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    private static function getId($target)
    {
        if (is_object($target)) {
            $id = spl_object_hash($target);
        } elseif (is_string($target)) {
            $id = $target;
        } else {
            throw new \InvalidArgumentException('Target must be an object or string class name.');
        }

        return $id;
    }
}
