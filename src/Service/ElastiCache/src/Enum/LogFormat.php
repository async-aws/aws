<?php

namespace AsyncAws\ElastiCache\Enum;

final class LogFormat
{
    public const JSON = 'json';
    public const TEXT = 'text';

    public static function exists(string $value): bool
    {
        return isset([
            self::JSON => true,
            self::TEXT => true,
        ][$value]);
    }
}
