<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\ReturnStrategy;

/**
 * Interface UseScopeReturnInterface
 *
 * @package Influence\ReturnStrategy
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
interface UseScopeReturnInterface
{
    /**
     * Set object or class scope for closure handler.
     *
     * @param object|string $scope
     *
     * @return $this
     */
    public function setScope($scope);
}
