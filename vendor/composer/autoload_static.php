<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitfacc39541a261a3284b1fc069da3e2ae
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WPClassifieds\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WPClassifieds\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitfacc39541a261a3284b1fc069da3e2ae::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitfacc39541a261a3284b1fc069da3e2ae::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}