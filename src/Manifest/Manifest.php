<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Manifest;

/**
 * Class Manifest
 *
 * @package Influence
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class Manifest implements \Countable
{
    /**
     * @var MethodManifest[]
     */
    private $methods = [];

    /**
     * @param string $method
     *
     * @return MethodManifest
     */
    public function getMethod($method)
    {
        if (empty($this->methods[$method]) === true) {
            $this->methods[$method] = new MethodManifest();
        }

        return $this->methods[$method];
    }

    /**
     * @param string $method
     *
     * @return bool
     */
    public function hasMethod($method)
    {
        return isset($this->methods[$method]);
    }

    /**
     * @param string $method
     *
     * @return $this
     */
    public function removeMethod($method)
    {
        unset($this->methods[$method]);

        return $this;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->methods);
    }
}
