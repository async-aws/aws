<?php

namespace AsyncAws\Lambda\Enum;

final class LogFormat
{
    public const JSON = 'JSON';
    public const TEXT = 'Text';

    public static function exists(string $value): bool
    {
        return isset([
            self::JSON => true,
            self::TEXT => true,
        ][$value]);
    }
}
