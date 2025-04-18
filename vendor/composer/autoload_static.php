<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0c0fca74f56bc8e12aff33a0edf8e480
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Src\\' => 4,
        ),
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Src\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0c0fca74f56bc8e12aff33a0edf8e480::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0c0fca74f56bc8e12aff33a0edf8e480::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit0c0fca74f56bc8e12aff33a0edf8e480::$classMap;

        }, null, ClassLoader::class);
    }
}
