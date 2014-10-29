<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Manifest;

/**
 * Class MethodManifest
 *
 * @package Influence\Manifest
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class MethodManifest
{
    /**
     * @var array
     */
    private $logs = [];
    /**
     * @var bool
     */
    private $log = false;
    /**
     * @var mixed
     */
    private $value;
    /**
     * @var bool
     */
    private $useDefaultValue = true;

    /**
     * @param boolean $log
     */
    public function setLog($log)
    {
        $this->log = (bool)$log;
    }

    /**
     * @param array $arguments
     *
     * @return $this
     */
    public function log(array $arguments)
    {
        if ($this->log) {
            array_push($this->logs, $arguments);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * @return $this
     */
    public function clearLogs()
    {
        $this->logs = [];

        return $this;
    }

    /**
     * Return custom value.
     *
     * @param array $arguments
     * @param $scope
     *
     * @return mixed
     */
    public function getValue(array $arguments, $scope)
    {
        if (is_callable($this->value)) {
            $handler = $this->value->bindTo((is_object($scope) ? $scope : null), $scope);

            return call_user_func_array($handler, $arguments);
        } else {
            return $this->value;
        }
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
        $this->useDefaultValue = false;
    }

    /**
     * @return bool
     */
    public function hasValue()
    {
        return !$this->useDefaultValue;
    }

    /**
     * @param bool $resetCustomValue
     */
    public function useDefaultValue($resetCustomValue = true)
    {
        $this->useDefaultValue = true;
        if ($resetCustomValue) {
            $this->value = null;
        }
    }
}
