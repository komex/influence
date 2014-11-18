<?php
/**
 * This file is a part of RemoteControlUtils project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence;

use Influence\Transformer\MetaInfo\ClassMetaInfo;
use Influence\Transformer\Transformer;

/**
 * Class Injector
 *
 * @package Influence
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 *
 * @property resource $stream
 */
class Injector extends \php_user_filter
{
    /**
     * @var Transformer
     */
    private static $transformer;
    /**
     * @var string
     */
    private $data = '';

    /**
     * @param resource $brigade
     * @param resource $out
     * @param int $consumed
     * @param bool $closing
     *
     * @return int
     */
    public function filter($brigade, $out, &$consumed, $closing)
    {
        if ($closing === true) {
            $bucket = stream_bucket_new($this->stream, $this->transform($this->data));
            $this->data = '';
            $consumed += $bucket->datalen;
            /** @var resource $bucket */
            stream_bucket_append($out, $bucket);

            return PSFS_PASS_ON;
        } else {
            do {
                $bucket = stream_bucket_make_writeable($brigade);
                if ($bucket === null) {
                    break;
                }
                $this->data .= $bucket->data;
            } while (true);

            return PSFS_FEED_ME;
        }
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function transform($content)
    {
        if (self::$transformer === null) {
            self::$transformer = new Transformer();
        }
        self::$transformer->setClassMetaInfo(new ClassMetaInfo());
        $tokens = token_get_all($content);
        $content = '';
        foreach ($tokens as $token) {
            if (is_array($token)) {
                list($code, $value) = $token;
            } else {
                $code = null;
                $value = $token;
            }
            $content .= self::$transformer->transform($code, $value);
        }

        return $content;
    }
}
