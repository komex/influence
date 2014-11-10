<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\ReturnStrategy;

/**
 * Class ReturnCallback
 *
 * @package Influence\ReturnStrategy
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ReturnCallback implements ReturnInterface, UseArgsReturnInterface
{
    /**
     * @var callable
     */
    protected $handler;
    /**
     * @var array
     */
    private $arguments;

    /**
     * @param callable $handler
     */
    public function __construct(\Closure $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @param array $arguments
     *
     * @return $this
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * Return custom value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return call_user_func_array($this->handler, $this->arguments);
    }
}
