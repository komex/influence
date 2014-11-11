<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Transformer\Mode;

/**
 * Class ImplementsMode
 *
 * @package Influence\Transformer\Mode
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ImplementsMode extends AbstractClassHierarchy
{
    /**
     * @return int
     */
    public function getCode()
    {
        return T_IMPLEMENTS;
    }

    /**
     * @param string $className
     *
     * @internal param Transformer $transformer
     * @return void
     */
    protected function setter($className)
    {
        $this->getTransformer()->getClassMetaInfo()->addImplements($className);
    }
}
