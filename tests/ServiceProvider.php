<?php

declare(strict_types=1);

namespace AsyncAws\Test;

class ServiceProvider
{
    private static $cache;

    public static function getAwsServiceNames()
    {
        $manifest = self::getManifest();

        return array_keys($manifest['services']);
    }

    private static function getManifest()
    {
        if (self::$cache === null) {
            $manifestFile = dirname(__DIR__).'/manifest.json';
            self::$cache = json_decode(file_get_contents($manifestFile), true);
        }

        return self::$cache;
    }
}
