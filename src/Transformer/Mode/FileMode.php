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
            case T_USE:
                $this->transformer->setMode(Transformer::MODE_USE);
                break;
            case T_NAMESPACE:
                $this->transformer->setMode(Transformer::MODE_NAMESPACE);
                break;
            case T_ABSTRACT:
            case T_FINAL:
                $this->transformer->getClassMetaInfo()->setAttribute($code);
                break;
            case T_CLASS:
                $this->transformer->setMode(Transformer::MODE_CLASS);
                break;
            case T_INTERFACE:
            case T_TRAIT:
                $this->transformer->setMode(Transformer::MODE_AS_IS);
                break;
        }
        return $value;
    }
}
