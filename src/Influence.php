<?php
/**
 * This file is a part of influence project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Influence;

use Composer\Autoload\ClassLoader;

/**
 * Class Influence
 *
 * @package Influence
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
final class Influence
{
    /**
     * @var Influence
     */
    private static $instance;
    /**
     * @var ClassLoader
     */
    private $composer;
    /**
     * @var array
     */
    private $blackList = [];

    /**
     * Init Influence.
     *
     * @param array $blackList
     */
    private function __construct(array $blackList)
    {
        $this->replaceAutoLoaders();
        array_push($blackList, __NAMESPACE__);
        foreach ($blackList as $class) {
            $this->addToBlackList($class);
        }
    }

    /**
     * Integrate Influence in code.
     *
     * @param array $blackList
     */
    public static function affect(array $blackList = [])
    {
        if (self::$instance === null) {
            self::$instance = new self($blackList);
        }
    }

    /**
     * @param string $class
     *
     * @return bool|null True if loaded, null otherwise
     */
    public function loadClass($class)
    {
        $class = ltrim($class, '\\');
        foreach ($this->blackList as $namespace) {
            if (strpos($class, $namespace) === 0) {
                return $this->composer->loadClass($class);
            }
        }
        $file = $this->composer->findFile($class);
        if ($file !== false) {
            \Composer\Autoload\includeFile('php://filter/read=influence.reader/resource=' . $file);

            return true;
        } else {
            return null;
        }
    }

    /**
     * @param string $class
     */
    private function addToBlackList($class)
    {
        $class = ltrim($class, '\\');
        if (in_array($class, $this->blackList) === false) {
            array_push($this->blackList, $class);
        }
    }

    /**
     * Register Influence loader and filter.
     */
    private function replaceAutoLoaders()
    {
        $loaders = spl_autoload_functions();
        foreach ($loaders as $loader) {
            list($object) = $loader;
            if ($object instanceof ClassLoader) {
                $this->composer = $object;
            }
            spl_autoload_unregister($loader);
        }
        if ($this->composer === null) {
            throw new \RuntimeException('Composer autoloader not registered');
        }
        spl_autoload_register([$this, 'loadClass']);
        stream_filter_register('influence.reader', 'Influence\\Injector');
    }
}
