<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Manifest;

use Influence\ReturnStrategy\ArgumentsInterface;
use Influence\ReturnStrategy\ReturnInterface;
use Influence\ReturnStrategy\ScopeInterface;

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
     * Recursive extract value.
     *
     * @param ReturnInterface $extractor
     * @param array $arguments
     * @param object|string $scope
     *
     * @return mixed
     */
    public static function extractValue(ReturnInterface $extractor, array $arguments, $scope)
    {
        if ($extractor instanceof ArgumentsInterface) {
            $extractor->setArguments($arguments);
        }
        if ($extractor instanceof ScopeInterface) {
            $extractor->setScope($scope);
        }

        $value = $extractor->getValue();

        return ($value instanceof ReturnInterface) ? self::extractValue($value, $arguments, $scope) : $value;
    }

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
    public function writeLog(array $arguments)
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

        return self::extractValue($this->value, $arguments, $scope);
    }

    /**
     * @param ReturnInterface|null $value
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
