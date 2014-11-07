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
 * Class NamespaceModeTest
 *
 * @package Test\Influence\Transformer\Mode
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class NamespaceModeTest extends TransformTestCase
{
    /**
     * @return array
     */
    public function dpNamespace()
    {
        return [
            ['ClassName', null, T_NAMESPACE],
            [' SomeClass ;', 'SomeClass', Transformer::MODE_FILE],
            ['\\SomeClass;', 'SomeClass', Transformer::MODE_FILE],
            [' NS\\Some\\ClassName;', 'NS\\Some\\ClassName', Transformer::MODE_FILE],
        ];
    }

    /**
     * Test correct extraction namespace.
     *
     * @param string $definition
     * @param string $correctClassName
     * @param int $correctMode
     *
     * @dataProvider dpNamespace
     */
    public function testNamespace($definition, $correctClassName, $correctMode)
    {
        $classMeta = new ClassMetaInfo();
        $transformer = new Transformer();
        $transformer->setClassMetaInfo($classMeta);
        $transformer->setMode(T_NAMESPACE);
        $this->assertSame($definition, $this->transform($transformer, $definition));
        $this->assertSame($correctClassName, $classMeta->getNamespace());
        $this->assertSame($correctMode, $transformer->getMode());
    }
}
