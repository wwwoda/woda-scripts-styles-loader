<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitdf3f8fe0eeed85367e28e07d385d66fd
{
    public static $files = array (
        '241d2b5b9c1e680c0770b006b0271156' => __DIR__ . '/..' . '/yahnis-elsts/plugin-update-checker/load-v4p9.php',
    );

    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'Woda\\WordPress\\ScriptsStylesLoader\\' => 35,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Woda\\WordPress\\ScriptsStylesLoader\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitdf3f8fe0eeed85367e28e07d385d66fd::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitdf3f8fe0eeed85367e28e07d385d66fd::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
