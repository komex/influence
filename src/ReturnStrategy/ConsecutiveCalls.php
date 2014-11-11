<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\ReturnStrategy;

/**
 * Class ConsecutiveCalls
 *
 * @package Influence\ReturnStrategy
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ConsecutiveCalls implements ReturnInterface
{
    /**
     * @var array
     */
    private $stack;

    /**
     * @param array $stack
     */
    public function __construct(array $stack)
    {
        $this->stack = $stack;
    }

    /**
     * Return custom value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return array_shift($this->stack);
    }
}
