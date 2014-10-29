<?php
/**
 * This file is a part of RemoteControl project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Transformer\Mode;

use Influence\Transformer\Transformer;

/**
 * Class ClassMode
 *
 * @package Influence\Transformer\Mode
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ClassMode extends AbstractMode
{
    /**
     * @param int|null $code
     * @param string $value
     *
     * @return string
     */
    public function transform($code, $value)
    {
        if ($code === T_STRING) {
            $this->transformer->getClassMetaInfo()->setName($value);
        } elseif ($code === T_EXTENDS) {
            $this->transformer->setMode(Transformer::MODE_EXTENDS);
        } elseif ($code === T_IMPLEMENTS) {

        } elseif ($value === '{') {
            $this->transformer->setMode(Transformer::MODE_CLASS_BODY);
        }

        return $value;
    }
}
