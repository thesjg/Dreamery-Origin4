<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit962e56ef69d44e23c192c32c2cba2532
{
    public static $prefixLengthsPsr4 = array (
        'L' => 
        array (
            'LightnCandy\\' => 12,
            'Leafo\\ScssPhp\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'LightnCandy\\' => 
        array (
            0 => __DIR__ . '/..' . '/zordius/lightncandy/src',
        ),
        'Leafo\\ScssPhp\\' => 
        array (
            0 => __DIR__ . '/..' . '/leafo/scssphp/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit962e56ef69d44e23c192c32c2cba2532::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit962e56ef69d44e23c192c32c2cba2532::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
