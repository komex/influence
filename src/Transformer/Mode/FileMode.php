<?php
/**
 * This file is a part of RemoteControl project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Transformer\Mode;

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
                $this->transformer->setMode(new ClassMode());
                break;
            case T_TRAIT:
                $this->transformer->setMode(new AsIsMode());
                break;
        }
        return $value;
    }
}
