<?php

namespace Fucoso\ORM;

/**
 * Class autoloader.
 *
 * To be used when not using Composer.
 * Conforms to PSR-0 specification.
 */
class Autoloader
{
    /**
     * Registers an SPL autoloader for Fucoso\ORM.
     */
    public static function register()
    {
        spl_autoload_register('Fucoso\ORM\Autoloader::autoload');
    }

    /**
     * Autoloader method.
     * @param string $class The class to load.
     * @see https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
     */
    public static function autoload($class)
    {
        $namespace = __NAMESPACE__ . '\\';

        if (strpos($class, $namespace) == 0) {
            $replacements = array(
                '\\' => DIRECTORY_SEPARATOR,
                '_' => DIRECTORY_SEPARATOR
            );

            $subpath = substr($class, strlen($namespace));
            $subpath = strtr($subpath, $replacements);
            $path = __DIR__ . DIRECTORY_SEPARATOR . $subpath . ".php";

            if (file_exists($path)) {
                include $path;
            }
        }
    }
}
