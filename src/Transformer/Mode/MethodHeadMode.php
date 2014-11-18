<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <a.j.kolchenko@baltsoftservice.ru>
 */

namespace Influence\Transformer\Mode;

use Influence\Transformer\Transformer;

/**
 * Class MethodHeadMode
 *
 * @package Influence\Transformer\Mode
 * @author Andrey Kolchenko <a.j.kolchenko@baltsoftservice.ru>
 */
class MethodHeadMode extends AbstractMode
{
    /**
     * @return int
     */
    public function getCode()
    {
        return T_FUNCTION;
    }

    /**
     * @param int|null $code
     * @param string $value
     *
     * @return string
     */
    public function transform($code, $value)
    {
        $method = $this->getTransformer()->getClassMetaInfo()->currentMethod();
        if ($code === T_STRING && $method->getName() === null) {
            $method->setName($value);
        } elseif ($value === ')') {
            $this->getTransformer()->setMode(Transformer::MODE_METHOD_BODY);
        }

        return $value;
    }
}
