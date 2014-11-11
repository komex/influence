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
     * @var int
     */
    private $a = 3;

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

    /**
     * @return int
     */
    public function getA()
    {
        return $this->a;
    }
}
