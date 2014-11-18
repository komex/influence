<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\ReturnStrategy;

/**
 * Class CallbackScope
 *
 * @package Influence\ReturnStrategy
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class CallbackScope extends Callback implements ScopeInterface
{
    /**
     * @inheritdoc
     */
    public function setScope($scope)
    {
        if (method_exists($this->handler, 'bindTo')) {
            $this->handler = $this->handler->bindTo((is_object($scope) ? $scope : null), $scope);
        }

        return $this;
    }
}
