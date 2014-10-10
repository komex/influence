<?php
/**
 * This file is a part of RemoteControl project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Transformer\Mode;

use Influence\Transformer\Transformer;

/**
 * Class FileMode
 *
 * @package Influence\Transformer\Mode
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class FileMode extends AbstractMode
{
    /**
     * @param int|null $code
     * @param string $value
     *
     * @return string
     */
    public function transform($code, $value)
    {
        switch ($code) {
            case T_CLASS:
                $this->transformer->setMode(Transformer::MODE_CLASS)->reset();
                break;
            case T_TRAIT:
                $this->transformer->setMode(Transformer::MODE_AS_IS)->reset();
                break;
        }
        return $value;
    }
}
