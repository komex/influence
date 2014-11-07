<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Transformer\Mode;

use Influence\Transformer\MetaInfo\ClassMetaInfo;

/**
 * Class NamespaceMode
 *
 * @package Influence\Transformer\Mode
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class NamespaceMode extends AbstractNamespaceMode
{
    /**
     * @return int
     */
    public function getCode()
    {
        return T_NAMESPACE;
    }

    /**
     * @param ClassMetaInfo $classMetaInfo
     * @param string $className
     *
     * @return void
     */
    protected function setter(ClassMetaInfo $classMetaInfo, $className)
    {
        $classMetaInfo->setNamespace($className);
    }
}
