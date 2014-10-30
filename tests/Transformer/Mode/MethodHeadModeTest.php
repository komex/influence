<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Test\Influence\Transformer\Mode;

use Influence\Transformer\MetaInfo\ClassMetaInfo;
use Influence\Transformer\MetaInfo\MethodMetaInfo;
use Influence\Transformer\Mode\MethodHeadMode;
use Influence\Transformer\Transformer;

/**
 * Class MethodHeadModeTest
 *
 * @package Test\Influence\Transformer\Mode
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class MethodHeadModeTest extends TransformTestCase
{
    /**
     * @return array
     */
    public function dpExtractMethodName()
    {
        return [
            ['method($a, \stdClass $obj = null, $array = []) {', 'method'],
            ['__construct() {}', '__construct'],
            ['SomeMethodName ( )', 'SomeMethodName'],
            ['__call() { $a = 1; }', '__call'],
        ];
    }

    /**
     * Test correct extract method name.
     *
     * @dataProvider dpExtractMethodName
     */
    public function testExtractMethodName($definition, $correctMethodName)
    {
        $classMeta = new ClassMetaInfo();
        $methodMeta = new MethodMetaInfo();
        $classMeta->addMethod($methodMeta);
        $transformer = new Transformer();
        $transformer->setClassMetaInfo($classMeta);
        $mode = new MethodHeadMode();
        $mode->setTransformer($transformer);
        $this->assertSame($definition, $this->transform($mode, $definition));
        $this->assertSame($correctMethodName, $methodMeta->getName());
        $this->assertSame(Transformer::MODE_METHOD_BODY, $transformer->getMode());
    }
}
