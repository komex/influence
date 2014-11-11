<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\ReturnStrategy;

/**
 * Class Value
 *
 * @package Influence\ReturnStrategy
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class Value implements ReturnInterface
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Return custom value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
