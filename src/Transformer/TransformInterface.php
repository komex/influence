<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Transformer;


/**
 * Interface TransformInterface
 *
 * @package Influence\Transformer
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
interface TransformInterface
{
    /**
     * @param int|null $code
     * @param string $value
     *
     * @return string
     */
    public function transform($code, $value);
}
