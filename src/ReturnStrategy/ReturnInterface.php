<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\ReturnStrategy;

/**
 * Interface ReturnInterface
 *
 * @package Influence\ReturnStrategy
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
interface ReturnInterface
{
    /**
     * Return custom value.
     *
     * @return mixed
     */
    public function getValue();
}
