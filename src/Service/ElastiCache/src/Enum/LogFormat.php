<?php

namespace AsyncAws\ElastiCache\Enum;

/**
 * Returns the log format, either JSON or TEXT.
 */
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
