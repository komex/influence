<?php
/**
 * This file is a part of RemoteControl project.
 *
 * (c) Andrey Kolchenko <a.j.kolchenko@baltsoftservice.ru>
 */

namespace Influence;

/**
 * Class Manifest
 *
 * @package Influence
 * @author Andrey Kolchenko <a.j.kolchenko@baltsoftservice.ru>
 */
class Manifest
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
     * @param bool $register
     */
    public function registerCalls($register)
    {
        $this->registerCalls = (bool)$register;
    }

    /**
     * @param string $method
     * @param array $arguments
     */
    public function registerCall($method, array $arguments)
    {
        if ($this->registerCalls) {
            if (empty($this->calls[$method])) {
                $this->calls[$method] = [];
            }
            array_push($this->calls[$method], $arguments);
        }
    }

    /**
     * @return array
     */
    public function getCalls()
    {
        return $this->calls;
    }

    /**
     * @param string $method
     *
     * @return bool
     */
    public function intercept($method)
    {
        return !empty($this->return[$method]);
    }

    /**
     * @param string $method
     * @param mixed $return
     */
    public function setReturn($method, $return)
    {
        $this->return[$method] = $return;
    }

    /**
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
