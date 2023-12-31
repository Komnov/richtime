<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit066b59f321b557874ec56cda855153fd
{
    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'DgoraWcas\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'DgoraWcas\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit066b59f321b557874ec56cda855153fd::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit066b59f321b557874ec56cda855153fd::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit066b59f321b557874ec56cda855153fd::$classMap;

        }, null, ClassLoader::class);
    }
}
