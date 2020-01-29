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
            throw new InvalidArgument(sprintf('Invalid option(s) "%s" passed to %s. ', implode('", "', $invalidOptions), __CLASS__));
        }

        $configuration = new Configuration();
        $configuration->data = $options;

        return $configuration;
    }

    public function getAccessKeyId(): ?string
    {
        return $this->data['accessKeyId'] ?? null;
    }
    public function getAccessKeySecret(): ?string
    {
        return $this->data['accessKeySecret'] ?? null;
    }
    public function getRegion(): ?string
    {
        return $this->data['region'] ?? null;
    }
}