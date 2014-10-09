<?php
/**
 * This file is a part of RemoteControl project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Transformer;

use Influence\Transformer\Mode\AbstractMode;
use Influence\Transformer\Mode\AsIsMode;
use Influence\Transformer\Mode\ClassBodyMode;
use Influence\Transformer\Mode\ClassMode;
use Influence\Transformer\Mode\FileMode;
use Influence\Transformer\Mode\MethodMode;

/**
 * Class Transformer
 *
 * @package Influence\Transformer
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class Transformer implements TransformerInterface
{
    const MODE_FILE = 1;
    const MODE_CLASS = 2;
    const MODE_CLASS_BODY = 3;
    const MODE_METHOD = 4;
    const MODE_AS_IS = 5;
    /**
     * @var TransformerInterface
     */
    private $mode;
    /**
     * @var array
     */
    private $modes = [];

    /**
     * Init modes
     */
    public function __construct()
    {
        $this->registerMode(self::MODE_FILE, new FileMode());
        $this->registerMode(self::MODE_CLASS, new ClassMode());
        $this->registerMode(self::MODE_CLASS_BODY, new ClassBodyMode());
        $this->registerMode(self::MODE_METHOD, new MethodMode());
        $this->registerMode(self::MODE_AS_IS, new AsIsMode());
        $this->setMode(self::MODE_FILE)->reset();
    }

    /**
     * @param int $mode
     *
     * @return AbstractMode
     */
    public function setMode($mode)
    {
        $mode = $this->modes[$mode];
        $this->mode = $mode;

        return $mode;
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

    /**
     * @param mixed $defaultValue
     *
     * @return void
     */
    public function reset($defaultValue = null)
    {
        // TODO: Implement reset() method.
    }

    /**
     * @param int $code
     * @param AbstractMode $mode
     */
    private function registerMode($code, AbstractMode $mode)
    {
        $mode->setTransformer($this);
        $this->modes[$code] = $mode;
    }
}
