<?php
/**
 * This file is a part of RemoteControlUtils project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Transformer\Mode;

use Influence\Transformer\TransformInterface;
use Influence\Transformer\Transformer;

/**
 * Class AbstractMode
 *
 * @package Influence\Transformer\Mode
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
abstract class AbstractMode implements TransformInterface
{
    /**
     * @var Transformer
     */
    private $transformer;

    /**
     * @return int
     */
    abstract public function getCode();

    /**
     * @return Transformer
     */
    public function getTransformer()
    {
        return $this->transformer;
    }

    /**
     * @param Transformer $transformer
     */
    public function setTransformer(Transformer $transformer)
    {
        $this->transformer = $transformer;
    }
}
