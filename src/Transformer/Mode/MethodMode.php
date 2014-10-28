<?php
/**
 * This file is a part of RemoteControl project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence\Transformer\Mode;

use Influence\Transformer\Transformer;

/**
 * Class MethodMode
 *
 * @package Influence\Transformer\Mode
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class MethodMode extends AbstractMode
{
    /**
     * @var bool
     */
    private $static;
    /**
     * @var int
     */
    private $level = 0;
    /**
     * @var bool
     */
    private $expectMethodName = true;
    /**
     * @var string
     */
    private $methodName;

    /**
     * @param int|null $code
     * @param string $value
     *
     * @return string
     */
    public function transform($code, $value)
    {
        if ($this->expectMethodName) {
            if ($code === T_STRING) {
                $this->methodName = $value;
                $this->expectMethodName = false;
            }
        } else {
            if ($value === '{') {
                if ($this->level === 0) {
                    $static = ($this->static or $this->methodName === '__construct');
                    $value .= $this->getCode($static);
                }
                $this->level++;
            } elseif ($value === '}') {
                $this->level--;
                if ($this->level === 0) {
                    $this->transformer->setMode(Transformer::MODE_CLASS_BODY)->reset();
                }
            }
        }

        return $value;
    }

    /**
     * @param bool $defaultValue
     */
    public function reset($defaultValue = null)
    {
        $this->static = !!$defaultValue;
        $this->level = 0;
        $this->expectMethodName = true;
        $this->methodName = null;
    }

    /**
     * @param bool $isStatic
     *
     * @return string
     */
    private function getCode($isStatic)
    {
        $target = $isStatic ? 'get_called_class()' : '$this';
        $scope = $isStatic ? '__CLASS__' : '$this';
        $type = $isStatic ? 'Static' : 'Object';
        $isUnderControl = sprintf('\\Influence\\RemoteControl::isUnderControl%s(%s, __FUNCTION__)', $type, $target);
        $control = sprintf('\\Influence\\RemoteControl::control%s(%s)', $type, $target);
        $manifest = uniqid('$manifest_');

        $code = <<<EOL
if (${isUnderControl}) {
    ${manifest} = ${control};
    ${manifest}->registerCall(__FUNCTION__, func_get_args());
    if (${manifest}->intercept(__FUNCTION__)) {
        return ${manifest}->call(__FUNCTION__, func_get_args(), $scope);
    }
}
EOL;

        return preg_replace('/\s+/', '', $code);
    }
}
