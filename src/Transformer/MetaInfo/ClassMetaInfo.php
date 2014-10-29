<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Transformer\MetaInfo;

/**
 * Class ClassMetaInfo
 *
 * @package Influence\Transformer
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ClassMetaInfo extends AbstractMetaInfo
{
    /**
     * @var string
     */
    private $namespace;
    /**
     * @var array
     */
    private $uses = [];
    /**
     * @var array
     */
    private $implements = [];
    /**
     * @var string
     */
    private $extends;
    /**
     * @var MethodMetaInfo[]
     */
    private $methods = [];

    /**
     * @param MethodMetaInfo $method
     *
     * @return $this
     */
    public function addMethod(MethodMetaInfo $method)
    {
        $this->methods[$method->getName()] = $method;

        return $this;
    }

    /**
     * @return MethodMetaInfo[]
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @return string
     */
    public function getExtends()
    {
        return $this->extends;
    }

    /**
     * @param string $className
     *
     * @return $this
     */
    public function setExtends($className)
    {
        $this->extends = $className;

        return $this;
    }

    /**
     * @return array
     */
    public function getImplements()
    {
        return $this->implements;
    }

    /**
     * @param string $className
     *
     * @return $this
     */
    public function addImplements($className)
    {
        if (!in_array($className, $this->implements)) {
            $this->implements = $className;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @return array
     */
    public function getUses()
    {
        return $this->uses;
    }

    /**
     * @param string $use
     *
     * @return $this
     */
    public function addUses($use)
    {
        if (!in_array($use, $this->uses)) {
            array_push($this->uses, $use);
        }

        return $this;
    }
}
