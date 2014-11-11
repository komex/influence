<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Transformer\Mode;

use Influence\Transformer\MetaInfo\ClassMetaInfo;

/**
 * Class AbstractNamespaceMode
 *
 * @package Influence\Transformer\Mode
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
abstract class AbstractNamespaceMode extends AbstractMode
{
    /**
     * @var string
     */
    private $class = '';

    /**
     * @param int|null $code
     * @param string $value
     *
     * @return string
     */
    public function transform($code, $value)
    {
        if ($code === T_NS_SEPARATOR or $code === T_STRING) {
            $this->class .= $value;
        } elseif ($value === ';') {
            $transformer = $this->getTransformer();
            $this->setter($transformer->getClassMetaInfo(), ltrim($this->class, '\\'));
            $this->class = '';
            $transformer->setMode($transformer::MODE_FILE);
        }

        return $value;
    }

    /**
     * @param ClassMetaInfo $classMetaInfo
     * @param string $className
     *
     * @return void
     */
    abstract protected function setter(ClassMetaInfo $classMetaInfo, $className);
}
