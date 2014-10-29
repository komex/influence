<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Transformer\Mode;

use Influence\Transformer\Transformer;

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
            $this->transformer->getClassMetaInfo()->setExtends(ltrim($this->class, '\\'));
            $this->class = '';
            $this->starts = false;
            $this->transformer->setMode(Transformer::MODE_CLASS);
        }

        return $value;
    }
}
