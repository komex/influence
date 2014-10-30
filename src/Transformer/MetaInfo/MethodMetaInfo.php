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
    public function getIsStatic()
    {
        return $this->isStatic;
    }

    /**
     * @param boolean $isStatic
     */
    public function setIsStatic($isStatic)
    {
        $this->isStatic = (bool)$isStatic;
    }
}
