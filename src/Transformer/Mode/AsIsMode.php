<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Transformer\Mode;

use Influence\Transformer\Transformer;

/**
 * Class AsIsMode
 *
 * @package Influence\Transformer\Mode
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class AsIsMode extends AbstractMode
{
    /**
     * @return int
     */
    public function getCode()
    {
        return Transformer::MODE_AS_IS;
    }

    /**
     * @param int|null $code
     * @param string $value
     *
     * @return string
     */
    public function transform($code, $value)
    {
        return $value;
    }
}
