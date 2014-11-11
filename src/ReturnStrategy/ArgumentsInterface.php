<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\ReturnStrategy;

/**
 * Interface ArgumentsInterface
 *
 * @package Influence\ReturnStrategy
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
interface ArgumentsInterface
{
    /**
     * @param array $arguments
     *
     * @return $this
     */
    public function setArguments(array $arguments);
}
