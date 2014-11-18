<?php
/**
 * This file is a part of RemoteControlUtils project.
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
     * Full class name of RemoteControlUtils.
     */
    const REMOTE_CONTROL_CLASS = '\\Influence\\RemoteControlUtils';

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
        if ($code === null) {
            switch ($value) {
                case '{':
                    $value = $this->increaseLevel($value);
                    break;
                case '}':
                    $this->decreaseLevel();
                    break;
                case ';':
                    $this->skipAbstractMethod();
                    break;
            }
        }

        return $value;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private function increaseLevel($value)
    {
        if ($this->level === 0) {
            $value .= $this->getInjectedCode($this->getTransformer()->getClassMetaInfo()->currentMethod());
        }
        $this->level++;

        return $value;
    }

    /**
     * Decrease method nesting level.
     */
    private function decreaseLevel()
    {
        $this->level--;
        if ($this->level === 0) {
            $this->getTransformer()->setMode(Transformer::MODE_CLASS_BODY);
        }
    }

    /**
     * Skip abstract method.
     */
    private function skipAbstractMethod()
    {
        if ($this->getTransformer()->getClassMetaInfo()->currentMethod()->getAttribute() === T_ABSTRACT) {
            $this->getTransformer()->setMode(Transformer::MODE_CLASS_BODY);
        }
    }

    /**
     * @param MethodMetaInfo $metaInfo
     *
     * @return string
     */
    private function getInjectedCode(MethodMetaInfo $metaInfo)
    {
        if ($metaInfo->isStatic() === true || $metaInfo->isConstructor() === true) {
            $type = 'Static';
            $class = 'get_called_class()';
        } else {
            $type = 'Object';
            $class = '$this';
        }
        $hasMethod = self::REMOTE_CONTROL_CLASS . sprintf('::has%s(%s, __FUNCTION__)', $type, $class);
        $getMethod = self::REMOTE_CONTROL_CLASS . sprintf('::get%s(%s)', $type, $class);
        $scope = (($metaInfo->isStatic() === true) ? '__CLASS__' : '$this');
        $manifest = uniqid('$manifest_');

        $code = <<<EOL
if ({$hasMethod}) {
    {$manifest} = {$getMethod}->getMethod(__FUNCTION__);
    if ({$manifest}->writeLog(func_get_args())->hasValue()) {
        return {$manifest}->getValue(func_get_args(), {$scope});
    }
}
EOL;

        return preg_replace('/\s+/', '', $code);
    }
}
