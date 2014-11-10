<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Manifest;

use Influence\ReturnStrategy\ReturnInterface;
use Influence\ReturnStrategy\UseArgsReturnInterface;
use Influence\ReturnStrategy\UseScopeReturnInterface;

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
     * @var ReturnInterface
     */
    private $value;

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
     * @param object|string $scope
     *
     * @return mixed
     */
    public function getValue(array $arguments, $scope)
    {
        if ($this->value === null) {
            return null;
        }
        if ($this->value instanceof UseArgsReturnInterface) {
            $this->value->setArguments($arguments);
        }
        if ($this->value instanceof UseScopeReturnInterface) {
            $this->value->setScope($scope);
        }

        return $this->value->getValue();
    }

    /**
     * @param ReturnInterface $value
     */
    public function setValue(ReturnInterface $value = null)
    {
        $this->value = $value;
    }

    /**
     * @return bool
     */
    public function hasValue()
    {
        return $this->value !== null;
    }
}
