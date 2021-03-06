<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2ef7289e2c9019953105201d6009b990
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'ParseCsv\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'ParseCsv\\' => 
        array (
            0 => __DIR__ . '/..' . '/parsecsv/php-parsecsv/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2ef7289e2c9019953105201d6009b990::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2ef7289e2c9019953105201d6009b990::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit2ef7289e2c9019953105201d6009b990::$classMap;

        }, null, ClassLoader::class);
    }
}
