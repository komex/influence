<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Transformer\Mode;

use Influence\Transformer\Transformer;

/**
 * Class ImplementsMode
 *
 * @package Influence\Transformer\Mode
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ImplementsMode extends AbstractMode
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
        return T_IMPLEMENTS;
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
            $this->getTransformer()->getClassMetaInfo()->addImplements(ltrim($this->class, '\\'));
            $this->class = '';
            $this->starts = false;
        } elseif ($value === '{') {
            $this->getTransformer()->setMode(Transformer::MODE_CLASS_BODY);
        }

        return $value;
    }
}
