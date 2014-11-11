<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\ReturnStrategy;

use Influence\Manifest\MethodManifest;

/**
 * Class ConsecutiveCallsCallback
 *
 * @package Influence\ReturnStrategy
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ConsecutiveCallsCallback implements ReturnInterface, ArgumentsInterface, ScopeInterface
{
    /**
     * @var array
     */
    private $arguments = [];
    /**
     * @var object|string
     */
    private $scope;
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
        $value = array_shift($this->stack);
        if ($value instanceof ReturnInterface) {
            return MethodManifest::extractValue($value, $this->arguments, $this->scope);
        } else {
            return $value;
        }
    }

    /**
     * Set object or class scope for closure handler.
     *
     * @param object|string $scope
     *
     * @return $this
     */
    public function setScope($scope)
    {
        $this->scope = $scope;

        return $this;
    }
}
