<?php

declare(strict_types=1);

namespace AsyncAws\Test;

class ServiceProvider
{
    private static $cache;

    public static function getAwsServices()
    {
        $manifest = self::getManifest();
        $data = [];
        foreach ($manifest['services'] as $name => $service) {
            $service['snake_case'] = self::snakeCase($name);
            $service['package_name'] = 'async-aws/' . str_replace('_', '-', $service['snake_case']);
            $data[$name] = $service;
        }

        return $data;
    }

    public static function getAwsServiceNames()
    {
        $manifest = self::getManifest();

        return array_keys($manifest['services']);
    }

    private static function getManifest()
    {
        if (null === self::$cache) {
            $manifestFile = \dirname(__DIR__) . '/manifest.json';
            self::$cache = json_decode(file_get_contents($manifestFile), true);
        }

        return self::$cache;
    }

    /**
     * @see https://stackoverflow.com/a/1993772/1526789
     */
    private static function snakeCase(string $input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }

        return implode('_', $ret);
    }
}
