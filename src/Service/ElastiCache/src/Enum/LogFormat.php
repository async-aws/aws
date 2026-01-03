<?php

namespace AsyncAws\ElastiCache\Enum;

final class LogFormat
{
    public const JSON = 'json';
    public const TEXT = 'text';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::JSON => true,
            self::TEXT => true,
        ][$value]);
    }
}
