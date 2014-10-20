<?php
/**
 * This file is a part of RemoteControl project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence;

/**
 * Class Manifest
 *
 * @package Influence
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class Manifest implements \Countable
{
    /**
     * @var bool
     */
    private $registerCalls = false;
    /**
     * @var array
     */
    private $calls = [];
    /**
     * @var mixed
     */
    private $return = [];

    /**
     * Enable or disable register methods calls.
     *
     * @param bool $register
     *
     * @return $this
     */
    public function registerCalls($register)
    {
        $this->registerCalls = (bool)$register;

        return $this;
    }

    /**
     * Register method call.
     *
     * @param string $method
     * @param array $arguments
     *
     * @return $this
     */
    public function registerCall($method, array $arguments)
    {
        if ($this->registerCalls) {
            array_push($this->calls, [$method, $arguments]);
        }

        return $this;
    }

    /**
     * @param string $method
     *
     * @return array
     */
    public function getCalls($method)
    {
        $calls = array_filter(
            $this->calls,
            function (array $record) use ($method) {
                return $record[0] === $method;
            }
        );
        $calls = array_map(
            function (array $record) {
                return $record[1];
            },
            $calls
        );

        return array_values($calls);
    }

    /**
     * Get all methods calls.
     *
     * @return array
     */
    public function getAllCalls()
    {
        return $this->calls;
    }

    /**
     * @param string $method
     *
     * @return int
     */
    public function getCallsCount($method)
    {
        return count($this->getCalls($method));
    }

    /**
     * Count number of calls.
     *
     * @return int
     */
    public function count()
    {
        return count($this->calls);
    }

    /**
     * Clear all methods calls.
     *
     * @return $this
     */
    public function clearAllCalls()
    {
        $this->calls = [];

        return $this;
    }

    /**
     * @param string $method
     *
     * @return $this
     */
    public function clearCalls($method)
    {
        $this->calls = array_filter(
            $this->calls,
            function (array $record) use ($method) {
                return $record[0] !== $method;
            }
        );

        return $this;
    }

    /**
     * Does we need to intercept call?
     *
     * @param string $method
     *
     * @return bool
     */
    public function intercept($method)
    {
        return array_key_exists($method, $this->return);
    }

    /**
     * Set return value for specified method.
     *
     * @param string $method
     * @param mixed $return
     *
     * @return $this
     */
    public function setReturn($method, $return)
    {
        $this->return[$method] = $return;

        return $this;
    }

    /**
     * Return custom value for specified method.
     *
     * @param string $method
     * @param array $arguments
     * @param object|null $scope
     *
     * @return mixed
     */
    public function call($method, array $arguments, $scope = null)
    {
        if (!$this->intercept($method)) {
            return null;
        }
        $handler = $this->return[$method];
        if (is_callable($handler)) {
            if (is_object($scope)) {
                $handler = $handler->bindTo($scope, $scope);
            }

            return call_user_func_array($handler, $arguments);
        } else {
            return $handler;
        }
    }
}
