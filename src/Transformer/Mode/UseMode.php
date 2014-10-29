<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Transformer\Mode;

use Influence\Transformer\Transformer;

/**
 * Class UseMode
 *
 * @package Influence\Transformer\Mode
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class UseMode extends AbstractMode
{
    /**
     * @var string
     */
    private $class = '';

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
        } elseif ($value === ';') {
            $this->transformer->getClassMetaInfo()->addUses(ltrim($this->class, '\\'));
            $this->class = '';
            $this->transformer->setMode(Transformer::MODE_FILE);
        }

        return $value;
    }
}
