<?php
/**
 * This file is a part of RemoteControl project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Transformer\Mode;

use Influence\Transformer\MetaInfo\MethodMetaInfo;
use Influence\Transformer\Transformer;

/**
 * Class ClassBodyMode
 *
 * @package Influence\Transformer\Mode
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ClassBodyMode extends AbstractMode
{
    /**
     * @var int
     */
    private $visibility = T_PUBLIC;
    /**
     * @var int
     */
    private $attribute = MethodMetaInfo::MODE_NORMAL;
    /**
     * @var bool
     */
    private $static = false;

    /**
     * @param int|null $code
     * @param string $value
     *
     * @return string
     */
    public function transform($code, $value)
    {
        if ($value === '}') {
            $this->transformer->setMode(Transformer::MODE_FILE);
        } else {
            switch ($code) {
                case T_STATIC:
                    $this->static = true;
                    break;
                case T_PUBLIC:
                case T_PROTECTED:
                case T_PRIVATE:
                    $this->visibility = $code;
                    break;
                case T_ABSTRACT:
                case T_FINAL:
                    $this->attribute = $code;
                    break;
                case T_VARIABLE:
                    $this->reset();
                    break;
                case T_FUNCTION:
                    $method = new MethodMetaInfo();
                    $method->setIsStatic($this->static);
                    $method->setAttribute($this->attribute);
                    $method->setVisibility($this->visibility);
                    $this->transformer->getClassMetaInfo()->addMethod($method);
                    $this->transformer->setMode(Transformer::MODE_METHOD_HEAD);
                    $this->reset();
                    break;
            }
        }

        return $value;
    }

    /**
     * Reset modifications.
     */
    private function reset()
    {
        $this->visibility = T_PUBLIC;
        $this->attribute = MethodMetaInfo::MODE_NORMAL;
        $this->static = false;
    }
}
