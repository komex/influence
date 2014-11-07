<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Test\Influence\Transformer\Mode;

use Influence\Transformer\MetaInfo\ClassMetaInfo;
use Influence\Transformer\Transformer;

/**
 * Class ImplementsModeTest
 *
 * @package Test\Influence\Transformer\Mode
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ImplementsModeTest extends TransformTestCase
{
    /**
     * @return array
     */
    public function dpExtendsClassName()
    {
        return [
            ['ClassName', [], T_IMPLEMENTS],
            [' SomeClass ', ['SomeClass'], T_IMPLEMENTS],
            ['\\SomeClass{', ['SomeClass'], Transformer::MODE_CLASS_BODY],
            [' NS\\Some\\ClassName' . PHP_EOL, ['NS\\Some\\ClassName'], T_IMPLEMENTS],
            [' SomeClass , \\NS\\SomeClass ', ['SomeClass', 'NS\\SomeClass'], T_IMPLEMENTS],
        ];
    }

    /**
     * @param string $definition
     * @param string $correctClassName
     * @param int $correctMode
     *
     * @dataProvider dpExtendsClassName
     */
    public function testExtendsClassName($definition, $correctClassName, $correctMode)
    {
        $classMeta = new ClassMetaInfo();
        $transformer = new Transformer();
        $transformer->setClassMetaInfo($classMeta);
        $transformer->setMode(T_IMPLEMENTS);
        $this->assertSame($definition, $this->transform($transformer, $definition));
        $this->assertSame($correctClassName, $classMeta->getImplements());
        $this->assertSame($correctMode, $transformer->getMode());
    }
}
