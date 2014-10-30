<?php
/**
 * This file is a part of RemoteControl project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Transformer;

/**
 * Interface TransformerInterface
 *
 * @package Influence\Transformer
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
interface TransformerInterface
{
    /**
     * @param int|null $code
     * @param string $value
     *
     * @return string
     */
    public function transform($code, $value);
}
