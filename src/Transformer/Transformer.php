<?php
/**
 * This file is a part of RemoteControl project.
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
use Influence\Transformer\Mode\MethodMode;
use Influence\Transformer\Mode\NamespaceMode;
use Influence\Transformer\Mode\UseMode;

/**
 * Class Transformer
 *
 * @package Influence\Transformer
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class Transformer
{
    /**
     * Parse file.
     */
    const MODE_FILE = 1;
    /**
     * Parse class namespace.
     */
    const MODE_NAMESPACE = 2;
    /**
     * Parse "use".
     */
    const MODE_USE = 3;
    /**
     * Parse class
     */
    const MODE_CLASS = 4;
    /**
     * Parse extends
     */
    const MODE_EXTENDS = 5;
    /**
     * Parse implements
     */
    const MODE_IMPLEMENTS = 6;
    /**
     * Parse class body
     */
    const MODE_CLASS_BODY = 7;
    /**
     * Parse method
     */
    const MODE_METHOD = 8;
    /**
     * Do nothing
     */
    const MODE_AS_IS = 9;
    /**
     * @var int
     */
    private $mode;
    /**
     * @var TransformerInterface[]
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
        $this->registerMode(self::MODE_FILE, new FileMode());
        $this->registerMode(self::MODE_USE, new UseMode());
        $this->registerMode(self::MODE_NAMESPACE, new NamespaceMode());
        $this->registerMode(self::MODE_CLASS, new ClassMode());
        $this->registerMode(self::MODE_EXTENDS, new ExtendsMode());
        $this->registerMode(self::MODE_IMPLEMENTS, new ImplementsMode());
        $this->registerMode(self::MODE_CLASS_BODY, new ClassBodyMode());
        $this->registerMode(self::MODE_METHOD, new MethodMode());
        $this->registerMode(self::MODE_AS_IS, new AsIsMode());
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
     * @param ClassMetaInfo $classMetaInfo
     */
    public function setClassMetaInfo(ClassMetaInfo $classMetaInfo)
    {
        $this->classMetaInfo = $classMetaInfo;
        foreach ($this->modes as $mode) {
            $mode->reset();
        }
        $this->setMode(self::MODE_FILE);
    }

    /**
     * @return ClassMetaInfo
     */
    public function getClassMetaInfo()
    {
        return $this->classMetaInfo;
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
