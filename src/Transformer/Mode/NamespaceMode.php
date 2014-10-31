<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Transformer\Mode;

use Influence\Transformer\Transformer;

/**
 * Class NamespaceMode
 *
 * @package Influence\Transformer\Mode
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class NamespaceMode extends AbstractMode
{
    /**
     * @var string
     */
    private $class = '';

    /**
     * @return int
     */
    public function getCode()
    {
        return T_NAMESPACE;
    }

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
            $this->getTransformer()->getClassMetaInfo()->setNamespace(ltrim($this->class, '\\'));
            $this->class = '';
            $this->getTransformer()->setMode(Transformer::MODE_FILE);
        }

        return $value;
    }
}
