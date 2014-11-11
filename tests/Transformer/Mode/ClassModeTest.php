<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Test\Influence\Transformer\Mode;

use Influence\Transformer\MetaInfo\ClassMetaInfo;
use Influence\Transformer\Mode\ClassMode;
use Influence\Transformer\Transformer;

/**
 * Class ClassModeTest
 *
 * @package Test\Influence\Transformer\Mode
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ClassModeTest extends TransformTestCase
{
    /**
     * @return array
     */
    public function dpSwitchingModes()
    {
        return [
            ['ClassName', 'ClassName', T_CLASS],
            [' SomeClass ', 'SomeClass', T_CLASS],
            ['ExtendsClass extends ', 'ExtendsClass', T_EXTENDS],
            [' ImplementsClass implements', 'ImplementsClass', T_IMPLEMENTS],
            [' ImplementsClass {', 'ImplementsClass', Transformer::MODE_CLASS_BODY],
        ];
    }

    /**
     * Test correct extract method name.
     *
     * @param string $definition
     * @param string $correctClassName
     * @param int $correctMode
     *
     * @dataProvider dpSwitchingModes
     */
    public function testSwitchingModes($definition, $correctClassName, $correctMode)
    {
        $classMeta = new ClassMetaInfo();
        $transformer = new Transformer();
        $transformer->setClassMetaInfo($classMeta);
        $transformer->setMode(T_CLASS);
        $mode = new ClassMode();
        $mode->setTransformer($transformer);
        $this->assertSame($definition, $this->transform($mode, $definition));
        $this->assertSame($correctClassName, $classMeta->getName());
        $this->assertSame($correctMode, $transformer->getMode());
    }

    /**
     * @return array
     */
    public function dpExtractClassMeta()
    {
        return [
            ['someClass', 'someClass', null, [], T_CLASS],
            [' anotherClass extends \\ArrayObject ', 'anotherClass', 'ArrayObject', [], T_CLASS],
            ['someclass extends \\DD\\FF{', 'someclass', 'DD\\FF', [], Transformer::MODE_CLASS_BODY],
            ['someClass implements \\ArrayObject ', 'someClass', null, ['ArrayObject'], T_IMPLEMENTS],
            ['someClass implements \\DD\\FF{', 'someClass', null, ['DD\\FF'], Transformer::MODE_CLASS_BODY],
            [
                'someClass implements \\ArrayObject , \\DD\\FF ',
                'someClass',
                null,
                ['ArrayObject', 'DD\\FF'],
                T_IMPLEMENTS
            ],
            [
                'someClass extends \\SplFixedArray implements \\ArrayObject , \\DD\\FF {',
                'someClass',
                'SplFixedArray',
                ['ArrayObject', 'DD\\FF'],
                Transformer::MODE_CLASS_BODY
            ],
        ];
    }

    /**
     * @param string $definition
     * @param string $name
     * @param string $extends
     * @param array $implements
     * @param int $mode
     *
     * @dataProvider dpExtractClassMeta
     */
    public function testExtractClassMeta($definition, $name, $extends, array $implements, $mode)
    {
        $classMeta = new ClassMetaInfo();
        $transformer = new Transformer();
        $transformer->setClassMetaInfo($classMeta);
        $transformer->setMode(T_CLASS);

        $this->assertSame($definition, $this->transform($transformer, $definition));
        $this->assertSame($name, $classMeta->getName());
        $this->assertSame($extends, $classMeta->getExtends());
        $this->assertSame($implements, $classMeta->getImplements());
        $this->assertSame($mode, $transformer->getMode());
    }
}
