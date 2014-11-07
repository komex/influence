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
 * Class UseModeTest
 *
 * @package Test\Influence\Transformer\Mode
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class UseModeTest extends TransformTestCase
{
    /**
     * @return array
     */
    public function dpUses()
    {
        return [
            ['ClassName', [], T_USE],
            [' SomeClass ;', ['SomeClass'], Transformer::MODE_FILE],
            ['\\SomeClass;', ['SomeClass'], Transformer::MODE_FILE],
            [' \\NS\\Some\\ClassName; use SomeClass;', ['NS\\Some\\ClassName', 'SomeClass'], Transformer::MODE_FILE],
        ];
    }

    /**
     * Test correct extraction uses.
     *
     * @param string $definition
     * @param string $correctClassName
     * @param int $correctMode
     *
     * @dataProvider dpUses
     */
    public function testUses($definition, $correctClassName, $correctMode)
    {
        $classMeta = new ClassMetaInfo();
        $transformer = new Transformer();
        $transformer->setClassMetaInfo($classMeta);
        $transformer->setMode(T_USE);
        $this->assertSame($definition, $this->transform($transformer, $definition));
        $this->assertSame($correctClassName, $classMeta->getUses());
        $this->assertSame($correctMode, $transformer->getMode());
    }
}
