<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Test\Influence;

use Influence\Injector;

/**
 * Class InjectorTest
 *
 * @package Test\Influence
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class InjectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function dpTransform()
    {
        $dir = realpath(
            join(DIRECTORY_SEPARATOR, [__DIR__, '..', 'vendor', 'phpunit', 'phpunit', 'src', 'TextUI'])
        );
        $paths = array_map(
            function ($path) {
                return (array)$path;
            },
            glob($dir . DIRECTORY_SEPARATOR . '*.php')
        );

        return $paths;
    }

    /**
     * Test correct code transformation.
     *
     * @dataProvider dpTransform
     */
    public function testTransform($file)
    {
        $codeOriginal = file_get_contents($file);
        $filter = new Injector();
        $class = new \ReflectionObject($filter);
        $method = $class->getMethod('transform');
        $method->setAccessible(true);
        $codeTransformed = $method->invoke($filter, $codeOriginal);
        $linesOriginal = explode(PHP_EOL, $codeOriginal);
        $linesTransformed = explode(PHP_EOL, $codeTransformed);

        $this->assertSame(count($linesOriginal), count($linesTransformed));
        $this->assertGreaterThan(strlen($codeOriginal), strlen($codeTransformed));

        for ($i = 0; $i < count($linesOriginal); $i++) {
            if (empty($linesOriginal[$i]) && empty($linesTransformed[$i])) {
                continue;
            }
            $this->assertStringStartsWith($linesOriginal[$i], $linesTransformed[$i]);
        }
    }
}
