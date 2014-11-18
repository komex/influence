<?php
/**
 * This file is a part of RemoteControlUtils project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Transformer;

use Influence\Transformer\MetaInfo\ClassMetaInfo;
use Influence\Transformer\Mode\AbstractMode;
use Influence\Transformer\Mode\AsIsMode;
use Influence\Transformer\Mode\ClassBodyMode;
use Influence\Transformer\Mode\ClassMode;
use Influence\Transformer\Mode\ExtendsMode;
use Influence\Transformer\Mode\FileMode;
use Influence\Transformer\Mode\ImplementsMode;
use Influence\Transformer\Mode\MethodBodyMode;
use Influence\Transformer\Mode\MethodHeadMode;
use Influence\Transformer\Mode\NamespaceMode;
use Influence\Transformer\Mode\UseMode;

/**
 * Class Transformer
 *
 * @package Influence\Transformer
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class Transformer implements TransformInterface
{
    /**
     * Do nothing
     */
    const MODE_AS_IS = 1;
    /**
     * Parse file.
     */
    const MODE_FILE = 2;
    /**
     * Parse class body
     */
    const MODE_CLASS_BODY = 3;
    /**
     * Parse method body
     */
    const MODE_METHOD_BODY = 4;
    /**
     * @var int
     */
    private $mode;
    /**
     * @var AbstractMode[]
     */
    private $modes = [];
    /**
     * @var ClassMetaInfo
     */
    private $classMetaInfo;

    /**
     * Init modes
     */
    public function __construct()
    {
        /** @var AbstractMode[] $modes */
        $modes = [
            new FileMode(),
            new UseMode(),
            new NamespaceMode(),
            new ClassMode(),
            new ExtendsMode(),
            new ImplementsMode(),
            new ClassBodyMode(),
            new MethodHeadMode(),
            new MethodBodyMode(),
            new AsIsMode(),
        ];
        foreach ($modes as $mode) {
            $this->registerMode($mode->getCode(), $mode);
        }
    }

    /**
     * @return int
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param int $mode
     *
     * @return $this
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * @param int|null $code
     * @param string $value
     *
     * @return string
     */
    public function transform($code, $value)
    {
        return $this->modes[$this->mode]->transform($code, $value);
    }

    /**
     * @return ClassMetaInfo
     */
    public function getClassMetaInfo()
    {
        return $this->classMetaInfo;
    }

    /**
     * @param ClassMetaInfo $classMetaInfo
     *
     * @return $this
     */
    public function setClassMetaInfo(ClassMetaInfo $classMetaInfo)
    {
        $this->classMetaInfo = $classMetaInfo;
        $this->setMode(self::MODE_FILE);

        return $this;
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
