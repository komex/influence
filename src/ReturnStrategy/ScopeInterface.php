<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\ReturnStrategy;

/**
 * Interface ScopeInterface
 *
 * @package Influence\ReturnStrategy
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
interface ScopeInterface
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
