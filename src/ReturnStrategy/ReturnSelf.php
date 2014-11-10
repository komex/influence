<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\ReturnStrategy;

/**
 * Class ReturnSelf
 *
 * @package Influence\ReturnStrategy
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ReturnSelf implements ReturnInterface, UseScopeReturnInterface
{
    /**
     * @var callable
     */
    private $handler;

    /**
     * Return custom value.
     *
     * @return object
     */
    public function getValue()
    {
        return call_user_func($this->handler);
    }

    /**
     * @inheritdoc
     */
    public function setScope($scope)
    {
        if (is_object($scope)) {
            $handler = function () {
                return $this;
            };
            $this->handler = $handler->bindTo($scope, $scope);
        } else {
            throw new \InvalidArgumentException('You can\'t use ' . __CLASS__ . ' strategy with static method.');
        }

        return $this;
    }
}
