<?php
/**
 * This file is a part of RemoteControlUtils project.
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
     * @var array
     */
    private static $modes = [T_EXTENDS, T_IMPLEMENTS];

    /**
     * @return int
     */
    public function getCode()
    {
        return T_CLASS;
    }

    /**
     * @param int|null $code
     * @param string $value
     *
     * @return string
     */
    public function transform($code, $value)
    {
        if ($code === T_STRING) {
            $this->getTransformer()->getClassMetaInfo()->setName($value);
        } elseif (in_array($code, self::$modes) === true) {
            $this->getTransformer()->setMode($code);
        } elseif ($value === '{') {
            $this->getTransformer()->setMode(Transformer::MODE_CLASS_BODY);
        }

        return $value;
    }
}
