<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\ReturnStrategy;

/**
 * Class ReturnCallbackScope
 *
 * @package Influence\ReturnStrategy
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ReturnCallbackScope extends ReturnCallback implements UseScopeReturnInterface
{
    /**
     * @inheritdoc
     */
    public function setScope($scope)
    {
        $this->handler = $this->handler->bindTo((is_object($scope) ? $scope : null), $scope);

        return $this;
    }
}
