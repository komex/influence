<?php
/**
 * This file is a part of RemoteControlUtils project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Transformer\Mode;

use Influence\Transformer\MetaInfo\MethodMetaInfo;
use Influence\Transformer\Transformer;

/**
 * Class ClassBodyMode
 *
 * @package Influence\Transformer\Mode
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ClassBodyMode extends AbstractMode
{
    /**
     * @var array
     */
    private static $visibilityFlags = [T_PUBLIC, T_PROTECTED, T_PRIVATE];
    /**
     * @var array
     */
    private static $attributeFlags = [T_ABSTRACT, T_FINAL];
    /**
     * @var int
     */
    private $visibility = T_PUBLIC;
    /**
     * @var int
     */
    private $attribute;
    /**
     * @var bool
     */
    private $static = false;

    /**
     * @return int
     */
    public function getCode()
    {
        return Transformer::MODE_CLASS_BODY;
    }

    /**
     * @param int|null $code
     * @param string $value
     *
     * @return string
     */
    public function transform($code, $value)
    {
        if (in_array($code, self::$visibilityFlags)) {
            $this->visibility = $code;
        } elseif ($code === T_STATIC) {
            $this->static = true;
        } elseif ($code === T_VARIABLE) {
            $this->reset();
        } elseif ($code === T_FUNCTION) {
            $method = new MethodMetaInfo();
            $method->setStatic($this->static);
            $method->setAttribute($this->attribute);
            $method->setVisibility($this->visibility);
            $this->getTransformer()->getClassMetaInfo()->addMethod($method);
            $this->getTransformer()->setMode($code);
            $this->reset();
        } elseif (in_array($code, self::$attributeFlags)) {
            $this->attribute = $code;
        } elseif ($value === '}') {
            $this->getTransformer()->setMode(Transformer::MODE_FILE);
        }

        return $value;
    }

    /**
     * Reset modifications.
     */
    private function reset()
    {
        $this->visibility = T_PUBLIC;
        $this->attribute = null;
        $this->static = false;
    }
}
