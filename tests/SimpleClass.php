<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Test\Influence;

/**
 * Class SimpleClass
 *
 * @package Test\Influence
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class SimpleClass
{
    /**
     * Simple method.
     *
     * @return string
     */
    public function method()
    {
        return __METHOD__;
    }

    /**
     * Static method.
     *
     * @return string
     */
    public static function staticMethod()
    {
        return __METHOD__;
    }
}
