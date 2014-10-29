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
     * Method is public
     */
    const VISIBILITY_PUBLIC = 0;
    /**
     * Method is protected
     */
    const VISIBILITY_PROTECTED = 1;
    /**
     * Method is private
     */
    const VISIBILITY_PRIVATE = 2;
    /**
     * @var int
     */
    private $visibility = self::VISIBILITY_PUBLIC;

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
     * @return int
     */
    public function getVisibility()
    {
        return $this->visibility;
    }
}
