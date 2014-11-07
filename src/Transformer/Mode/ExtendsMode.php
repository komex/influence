<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Transformer\Mode;

/**
 * Class ExtendsMode
 *
 * @package Influence\Transformer\Mode
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ExtendsMode extends AbstractClassHierarchy
{
    /**
     * @return int
     */
    public function getCode()
    {
        return T_EXTENDS;
    }

    /**
     * @param string $className
     *
     * @internal param Transformer $transformer
     * @return void
     */
    protected function setter($className)
    {
        $this->getTransformer()->setMode(T_CLASS)->getClassMetaInfo()->setExtends($className);
    }
}
