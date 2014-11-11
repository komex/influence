<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\ReturnStrategy;

use Influence\Manifest\MethodManifest;

/**
 * Class Map
 *
 * @package Influence\ReturnStrategy
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class Map implements ReturnInterface, ArgumentsInterface, ScopeInterface
{
    /**
     * @var array
     */
    private $map;
    /**
     * @var array
     */
    private $arguments = [];
    /**
     * @var int
     */
    private $argumentsCount = 0;
    /**
     * @var object|string
     */
    private $scope;

    /**
     * @param array $map
     */
    public function __construct(array $map)
    {
        $this->map = $map;
    }

    /**
     * @param array $arguments
     *
     * @return $this
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
        $this->argumentsCount = count($arguments);

        return $this;
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

    /**
     * Return custom value.
     *
     * @return mixed
     */
    public function getValue()
    {
        foreach ($this->map as $map) {
            if (!is_array($map) or $this->argumentsCount !== (count($map) - 1)) {
                continue;
            }
            $value = array_pop($map);
            if ($this->arguments === $map) {
                if ($value instanceof ReturnInterface) {
                    return MethodManifest::extractValue($value, $this->arguments, $this->scope);
                } else {
                    return $value;
                }
            }
        }

        return null;
    }
}
