<?php
/**
 * This file is a part of RemoteControl project.
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
    public function get($method)
    {
        if (empty($this->methods[$method])) {
            $this->methods[$method] = new MethodManifest();
        }

        return $this->methods[$method];
    }

    /**
     * @param string $method
     *
     * @return bool
     */
    public function has($method)
    {
        return isset($this->methods[$method]);
    }

    /**
     * @param string $method
     *
     * @return $this
     */
    public function remove($method)
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
