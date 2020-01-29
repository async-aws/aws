<?php

declare(strict_types=1);

namespace WorkingTitle\Aws;

use WorkingTitle\Aws\Exception\InvalidArgument;

class Configuration
{
    private const AVAILABLE_OPTIONS = [
        'region', 'accessKeyId', 'accessKeySecret'
    ];

    private $data;

    public static function create(array $options)
    {
        if (0 < \count($invalidOptions = array_diff(array_keys($options), self::AVAILABLE_OPTIONS))) {
            throw new InvalidArgument(sprintf('Invalid option(s) "%s" passed to "%s::%s". ', implode('", "', $invalidOptions), __CLASS__, __METHOD__));
        }

        $configuration = new Configuration();
        $configuration->data = $options;

        return $configuration;
    }

    public function get(string $name): ?string
    {
        if (!in_array($name, self::AVAILABLE_OPTIONS)) {
            throw new InvalidArgument(sprintf('Invalid option "%s" passed to "%s::%s". ', $name, __CLASS__, __METHOD__));
        }

        return $this->data[$name] ?? null;
    }

    public function has(string $name): bool
    {
        if (!in_array($name, self::AVAILABLE_OPTIONS)) {
            throw new InvalidArgument(sprintf('Invalid option "%s" passed to "%s::%s". ', $name, __CLASS__, __METHOD__));
        }

        return isset($this->data[$name]);
    }

}