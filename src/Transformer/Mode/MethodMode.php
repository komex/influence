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
        static $namespace = '\\Influence\\RemoteControl::';
        if ($isStatic) {
            $hasMethod = $namespace . 'hasStatic(get_called_class(), __FUNCTION__)';
            $getMethod = $namespace . 'getStatic(get_called_class())';
            $scope = '__CLASS__';
        } else {
            $hasMethod = $namespace . 'hasObject($this, __FUNCTION__)';
            $getMethod = $namespace . 'getObject($this)';
            $scope = '$this';
        }
        $manifest = uniqid('$manifest_');

        $code = <<<EOL
if (${hasMethod}) {
    ${manifest} = ${getMethod}->get(__FUNCTION__);
    if (${manifest}->log(func_get_args())->hasValue()) {
        return ${manifest}->getValue(func_get_args(), $scope);
    }
}
EOL;

        return preg_replace('/\s+/', '', $code);
    }
}
