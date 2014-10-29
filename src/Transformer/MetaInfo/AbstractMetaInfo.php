<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Transformer\MetaInfo;

/**
 * Class AbstractMetaInfo
 *
 * @package Influence\Transformer\MetaInfo
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
abstract class AbstractMetaInfo
{
    /**
     * Target has no attributes
     */
    const MODE_NORMAL = 0;
    /**
     * Target is abstract
     */
    const MODE_ABSTRACT = 0;
    /**
     * Target is final
     */
    const MODE_FINAL = 1;
    /**
     * @var string
     */
    private $name;
    /**
     * @var int
     */
    private $attribute = self::MODE_NORMAL;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * @param int $attribute
     */
    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
    }
}
