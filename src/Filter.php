<?php
/**
 * This file is a part of RemoteControl project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence;

use Influence\Transformer\Transformer;

/**
 * Class Filter
 *
 * @package Influence
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class Filter extends \php_user_filter
{
    /**
     * @param resource $in
     * @param resource $out
     * @param int $consumed
     * @param bool $closing
     *
     * @return int
     */
    public function filter($in, $out, &$consumed, $closing)
    {
        /** @var \stdClass|resource $bucket */
        while ($bucket = stream_bucket_make_writeable($in)) {
            $bucket->data = $this->transform($bucket->data);
            $bucket->datalen = strlen($bucket->data);
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);
        }

        return PSFS_PASS_ON;
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function transform($content)
    {
        $transformer = new Transformer();
        $tokens = token_get_all($content);
        $content = '';
        foreach ($tokens as $token) {
            if (is_array($token)) {
                list($code, $value) = $token;
            } else {
                $code = null;
                $value = $token;
            }
            $content .= $transformer->transform($code, $value);
        }

        return $content;
    }
}
