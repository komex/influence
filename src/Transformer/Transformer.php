<?php
/**
 * This file is a part of RemoteControl project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Transformer;

use Influence\Transformer\Mode\AbstractMode;
use Influence\Transformer\Mode\FileMode;

/**
 * Class Transformer
 *
 * @package Influence\Transformer
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class Transformer implements TransformerInterface
{
    /**
     * @var TransformerInterface
     */
    private $mode;

    /**
     * Init modes
     */
    public function __construct()
    {
        $this->setMode(new FileMode());
    }

    /**
     * @param AbstractMode $mode
     */
    public function setMode(AbstractMode $mode)
    {
        $mode->setTransformer($this);
        $this->mode = $mode;
    }

    /**
     * @param int|null $code
     * @param string $value
     *
     * @return string
     */
    public function transform($code, $value)
    {
        return $this->mode->transform($code, $value);
    }
}
