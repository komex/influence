<?php
/**
 * This file is a part of RemoteControl project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Transformer\Mode;

use Influence\Transformer\Transformer;

/**
 * Class MethodMode
 *
 * @package Influence\Transformer\Mode
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class MethodMode extends AbstractMode
{
    /**
     * @var bool
     */
    private $static;
    /**
     * @var int
     */
    private $level = 0;

    /**
     * @param int|null $code
     * @param string $value
     *
     * @return string
     */
    public function transform($code, $value)
    {
        if ($value === '{') {
            if ($this->level === 0) {
                $value .= '$a = ' . ($this->static ? 1 : 2) . ';';
            }
            $this->level++;
        } elseif ($value === '}') {
            $this->level--;
            if ($this->level === 0) {
                $this->transformer->setMode(Transformer::MODE_CLASS_BODY)->reset();
            }
        }

        return $value;
    }

    /**
     * @param bool $defaultValue
     */
    public function reset($defaultValue = null)
    {
        $this->static = !!$defaultValue;
        $this->level = 0;
    }
}
