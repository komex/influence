<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Test\Influence\Transformer\Mode;

use Influence\Transformer\TransformInterface;

/**
 * Class TransformTestCase
 *
 * @package Test\Influence\Transformer\Mode
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
abstract class TransformTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @param TransformInterface $mode
     * @param string $definition
     *
     * @return string
     */
    protected function transform(TransformInterface $mode, $definition)
    {
        $content = '';
        foreach (array_slice(token_get_all('<?php ' . $definition), 1) as $token) {
            if (is_array($token)) {
                list($code, $value) = $token;
            } else {
                $code = null;
                $value = $token;
            }
            $content .= $mode->transform($code, $value);
        }

        return $content;
    }
}
