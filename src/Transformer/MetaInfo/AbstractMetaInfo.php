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
     * @var string
     */
    private $name;
    /**
     * @var int|null
     */
    private $attribute;

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
        $this->name = trim($name);

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * @param int|null $attribute
     */
    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
    }
}
