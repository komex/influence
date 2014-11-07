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
 * Class ExtendsModeTest
 *
 * @package Test\Influence\Transformer\Mode
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ExtendsModeTest extends TransformTestCase
{
    /**
     * @return array
     */
    public function dpExtendsClassName()
    {
        return [
            ['ClassName', null, T_EXTENDS],
            [' SomeClass ', 'SomeClass', T_CLASS],
            ['\\SomeClass{', 'SomeClass', Transformer::MODE_CLASS_BODY],
            [' NS\\Some\\ClassName' . PHP_EOL, 'NS\\Some\\ClassName', T_CLASS],
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
        $transformer->setMode(T_EXTENDS);
        $this->assertSame($definition, $this->transform($transformer, $definition));
        $this->assertSame($correctClassName, $classMeta->getExtends());
        $this->assertSame($correctMode, $transformer->getMode());
    }
}
