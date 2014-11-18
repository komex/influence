<?php
/**
 * This file is a part of RemoteControl project.
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
class Influence
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
     * Init
     */
    private function __construct()
    {
        $this->composer = $this->getComposer();
        $this->registerModules();
    }

    /**
     * @return Influence
     */
    public static function affect()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function loadClass($class)
    {
        if (strpos(ltrim($class, '\\'), __NAMESPACE__) === 0) {
            return $this->composer->loadClass($class);
        } elseif ($file = $this->composer->findFile($class)) {
            \Composer\Autoload\includeFile('php://filter/read=influence.reader/resource=' . $file);

            return true;
        }

        return false;
    }

    /**
     * Register Influence loader and filter.
     */
    private function registerModules()
    {
        $loaders = spl_autoload_functions();
        foreach ($loaders as $loader) {
            spl_autoload_unregister($loader);
        }
        spl_autoload_register([$this, 'loadClass']);
        stream_filter_register('influence.reader', 'Influence\\Injector');
    }

    /**
     * @return ClassLoader
     * @throws \RuntimeException
     */
    private function getComposer()
    {
        $loaders = spl_autoload_functions();
        foreach ($loaders as $loader) {
            list($object) = $loader;
            if (get_class($object) === 'Composer\\Autoload\\ClassLoader') {
                return $object;
            }
        }
        throw new \RuntimeException('Composer autoloader not registered');
    }
}
