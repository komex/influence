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
 * Class MethodBodyMode
 *
 * @package Influence\Transformer\Mode
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class MethodBodyMode extends AbstractMode
{
    /**
     * @var int
     */
    private $level = 0;

    /**
     * @return int
     */
    public function getCode()
    {
        return Transformer::MODE_METHOD_BODY;
    }

    /**
     * @param int|null $code
     * @param string $value
     *
     * @return string
     */
    public function transform($code, $value)
    {
        switch ($value) {
            case '{':
                if ($this->level === 0) {
                    $value .= $this->getInjectedCode($this->getTransformer()->getClassMetaInfo()->currentMethod());
                }
                $this->level++;
                break;
            case '}':
                $this->level--;
                if ($this->level === 0) {
                    $this->getTransformer()->setMode(Transformer::MODE_CLASS_BODY);
                }
                break;
            case ';':
                if ($this->getTransformer()->getClassMetaInfo()->currentMethod()->getAttribute() === T_ABSTRACT) {
                    $this->getTransformer()->setMode(Transformer::MODE_CLASS_BODY);
                }
                break;
        }

        return $value;
    }

    /**
     * @param MethodMetaInfo $metaInfo
     *
     * @return string
     */
    private function getInjectedCode(MethodMetaInfo $metaInfo)
    {
        static $namespace = '\\Influence\\RemoteControl::';
        if ($metaInfo->isStatic() || $metaInfo->isConstructor()) {
            $hasMethod = $namespace . 'hasStatic(get_called_class(), __FUNCTION__)';
            $getMethod = $namespace . 'getStatic(get_called_class())';
        } else {
            $hasMethod = $namespace . 'hasObject($this, __FUNCTION__)';
            $getMethod = $namespace . 'getObject($this)';
        }
        $scope = (($metaInfo->isStatic()) ? '__CLASS__' : '$this');
        $manifest = uniqid('$manifest_');

        $code = <<<EOL
if (${hasMethod}) {
    ${manifest} = ${getMethod}->getMethod(__FUNCTION__);
    if (${manifest}->writeLog(func_get_args())->hasValue()) {
        return ${manifest}->getValue(func_get_args(), $scope);
    }
}
EOL;

        return preg_replace('/\s+/', '', $code);
    }
}
