<?php
/**
 * This file is a part of RemoteControl project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Transformer\Mode;

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
     * @var bool
     */
    private $static = false;

    /**
     * @param int|null $code
     * @param string $value
     *
     * @return string
     */
    public function transform($code, $value)
    {
        switch ($code) {
            case T_STATIC:
                $this->static = true;
                break;
            case T_VARIABLE:
                $this->static = false;
                break;
            case T_FUNCTION:
                $this->transformer->setMode(Transformer::MODE_METHOD)->reset($this->static);
                break;
            case null:
                if ($value === '}') {
                    $this->transformer->setMode(Transformer::MODE_FILE)->reset();
                }
                break;
        }
        return $value;
    }

    /**
     * @param null $defaultValue
     */
    public function reset($defaultValue = null)
    {
        $this->static = false;
    }
}
