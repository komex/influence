<?php
/**
 * This file is a part of influence project.
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
     * @var array
     */
    private static $modes = [T_USE, T_NAMESPACE, T_CLASS];
    /**
     * @var array
     */
    private static $attributes = [T_ABSTRACT, T_FINAL];
    /**
     * @var array
     */
    private static $skip = [T_INTERFACE, T_TRAIT];

    /**
     * @return int
     */
    public function getCode()
    {
        return Transformer::MODE_FILE;
    }

    /**
     * @param int|null $code
     * @param string $value
     *
     * @return string
     */
    public function transform($code, $value)
    {
        if (in_array($code, self::$modes) === true) {
            $this->getTransformer()->setMode($code);
        } elseif (in_array($code, self::$attributes) === true) {
            $this->getTransformer()->getClassMetaInfo()->setAttribute($code);
        } elseif (in_array($code, self::$skip) === true) {
            $this->getTransformer()->setMode(Transformer::MODE_AS_IS);
        }

        return $value;
    }
}
