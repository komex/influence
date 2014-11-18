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
        $newThis = (is_object($scope) === true) ? $scope : null;
        $this->handler = call_user_func([$this->handler, 'bindTo'], $newThis, $scope);

        return $this;
    }
}
