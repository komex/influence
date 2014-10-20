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
     */
    public function registerCalls($register)
    {
        $this->registerCalls = (bool)$register;
    }

    /**
     * Register method call.
     *
     * @param string $method
     * @param array $arguments
     */
    public function registerCall($method, array $arguments)
    {
        if ($this->registerCalls) {
            array_push($this->calls, [$method, $arguments]);
        }
    }

    /**
     * @param string $method
     *
     * @return array|null
     */
    public function getCalls($method)
    {
        return $this->extractArguments($this->getMethodCalls($method));
    }

    /**
     * Get all methods calls.
     *
     * @return array
     */
    public function getAllCalls()
    {
        return $this->extractArguments($this->calls);
    }

    /**
     * @param string $method
     *
     * @return int
     */
    public function getCallsCount($method)
    {
        return count($this->getMethodCalls($method));
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
     */
    public function clearAllCalls()
    {
        $this->calls = [];
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
     */
    public function setReturn($method, $return)
    {
        $this->return[$method] = $return;
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

    /**
     * @param array $calls
     *
     * @return array
     */
    private function extractArguments(array $calls)
    {
        $calls = array_map(
            function (array $record) {
                unset($record[1][1]);

                return $record;
            },
            $calls
        );

        return $calls;
    }

    /**
     * @param string $method
     *
     * @return array
     */
    private function getMethodCalls($method)
    {
        return array_values(
            array_filter(
                $this->calls,
                function (array $record) use ($method) {
                    return $record[0] === $method;
                }
            )
        );
    }
}
