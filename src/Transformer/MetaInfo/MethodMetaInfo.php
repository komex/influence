<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Transformer\MetaInfo;

/**
 * Class MethodMetaInfo
 *
 * @package Influence\Transformer\MetaInfo
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class MethodMetaInfo extends AbstractMetaInfo
{
    /**
     * @var int
     */
    private $visibility = T_PUBLIC;
    /**
     * @var bool
     */
    private $isStatic = false;
    /**
     * @var bool
     */
    private $isConstructor = false;

    /**
     * @return int
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * @param int $visibility
     *
     * @return $this
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isStatic()
    {
        return $this->isStatic;
    }

    /**
     * @param boolean $isStatic
     */
    public function setStatic($isStatic)
    {
        $this->isStatic = (bool)$isStatic;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->isConstructor = strtolower($name) === '__construct';

        return parent::setName($name);
    }

    /**
     * @return bool
     */
    public function isConstructor()
    {
        return $this->isConstructor;
    }
}
