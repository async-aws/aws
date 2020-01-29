<?php

declare(strict_types=1);

namespace WorkingTitle\Aws;

use WorkingTitle\Aws\Exception\InvalidArgument;

/**
 * Helper object that holds all configuration to the API.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Configuration
{
    private const AVAILABLE_OPTIONS = [
        'region', 'accessKeyId', 'accessKeySecret', 'endpoint',
    ];

    private const DEFAULT_OPTIONS = [
        'endpoint' => 'https://%service%.%region%.amazonaws.com', // https://docs.aws.amazon.com/general/latest/gr/rande.html
    ];

    private $data;

    public static function create(array $options)
    {
        if (0 < \count($invalidOptions = array_diff(array_keys($options), self::AVAILABLE_OPTIONS))) {
            throw new InvalidArgument(sprintf('Invalid option(s) "%s" passed to "%s::%s". ', implode('", "', $invalidOptions), __CLASS__, __METHOD__));
        }

        $configuration = new Configuration();
        $configuration->data = array_merge(self::DEFAULT_OPTIONS, $options);

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
