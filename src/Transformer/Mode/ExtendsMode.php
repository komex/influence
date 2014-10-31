<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Transformer\Mode;

/**
 * Class ExtendsMode
 *
 * @package Influence\Transformer\Mode
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ExtendsMode extends AbstractMode
{
    /**
     * @var string
     */
    private $class = '';
    /**
     * @var bool
     */
    private $starts = false;

    /**
     * @return int
     */
    public function getCode()
    {
        return T_EXTENDS;
    }

    /**
     * @param int|null $code
     * @param string $value
     *
     * @return string
     */
    public function transform($code, $value)
    {
        if ($code === T_NS_SEPARATOR or $code === T_STRING) {
            $this->class .= $value;
            $this->starts = true;
        } elseif ($this->starts) {
            $this->getTransformer()->getClassMetaInfo()->setExtends(ltrim($this->class, '\\'));
            $this->class = '';
            $this->starts = false;
            $this->getTransformer()->setMode(T_CLASS);
        }

        return $value;
    }
}
