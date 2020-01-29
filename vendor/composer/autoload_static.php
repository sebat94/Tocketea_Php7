<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit89b85e00567a8c1ed8e9f4bc9e30a6e0
{
    public static $prefixLengthsPsr4 = array (
        't' => 
        array (
            'tocketea\\' => 9,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'M' => 
        array (
            'Monolog\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'tocketea\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'Monolog\\' => 
        array (
            0 => __DIR__ . '/..' . '/monolog/monolog/src/Monolog',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit89b85e00567a8c1ed8e9f4bc9e30a6e0::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit89b85e00567a8c1ed8e9f4bc9e30a6e0::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
