<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Test\Influence\Transformer\Mode;

use Influence\Transformer\Transformer;

/**
 * Class MethodModeTest
 *
 * @package Test\Influence\Transformer\Mode
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class MethodModeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Length of static injected code
     */
    const STATIC_CODE_LENGTH = 337;
    /**
     * Length of injected code
     */
    const CODE_LENGTH = 312;
    /**
     * @var Transformer
     */
    private static $transformer;

    /**
     * @return array
     */
    public function dpMethodContent()
    {
        return [
            ['__construct', false, self::STATIC_CODE_LENGTH],
            ['staticMethod', true, self::STATIC_CODE_LENGTH],
            ['method', false, self::CODE_LENGTH],
        ];
    }

    /**
     * Test code injection successful for different methods.
     *
     * @param string $name
     * @param bool $reset
     * @param int $length
     *
     * @dataProvider dpMethodContent
     */
    public function testTransformMethodContent($name, $reset, $length)
    {
        $mode = $this->getMode();
        $mode->reset($reset);
        $content = '';
        foreach (array_slice(token_get_all('<?php function ' . $name . '() {}'), 3) as $token) {
            if (is_array($token)) {
                list($code, $value) = $token;
            } else {
                $code = null;
                $value = $token;
            }
            $content .= $mode->transform($code, $value);
        }
        $this->assertStringStartsWith($name . '() {', $content);
        $this->assertStringEndsWith('}', $content);
        $this->assertSame((strlen($name) + 5 + $length), strlen($content));
    }

    /**
     * Test arguments parses successful.
     */
    public function testMethodArguments()
    {
        $mode = $this->getMode();
        $content = '';
        $definition = 'method($a, \stdClass $obj = null, $array = []) {';
        foreach (array_slice(token_get_all('<?php function ' . $definition . '}'), 3) as $token) {
            if (is_array($token)) {
                list($code, $value) = $token;
            } else {
                $code = null;
                $value = $token;
            }
            $content .= $mode->transform($code, $value);
        }
        $this->assertStringStartsWith($definition, $content);
    }

    /**
     * @return \Influence\Transformer\Mode\AbstractMode
     */
    private function getMode()
    {
        if (self::$transformer === null) {
            self::$transformer = new Transformer();
        }

        return self::$transformer->setMode(Transformer::MODE_METHOD);
    }
}
