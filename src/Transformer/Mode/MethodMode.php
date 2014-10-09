<?php
/**
 * This file is a part of RemoteControl project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Transformer\Mode;

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
     * @param bool $static
     */
    public function __construct($static)
    {
        $this->static = $static;
    }

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
                $this->transformer->setMode(new ClassBodyMode());
            }
        }

        return $value;
    }
}
