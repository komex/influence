<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Transformer\Mode;

use Influence\Transformer\Transformer;

/**
 * Class AbstractClassHierarchy
 *
 * @package Transformer\Mode
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
abstract class AbstractClassHierarchy extends AbstractMode
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
            $this->setter(ltrim($this->class, '\\'));
            $this->class = '';
            $this->starts = false;
        }
        if ($value === '{') {
            $this->getTransformer()->setMode(Transformer::MODE_CLASS_BODY);
        }

        return $value;
    }

    /**
     * @param string $className
     *
     * @return void
     */
    abstract protected function setter($className);
}
