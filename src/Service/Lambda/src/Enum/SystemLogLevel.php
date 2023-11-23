<?php

namespace AsyncAws\Lambda\Enum;

final class SystemLogLevel
{
    public const DEBUG = 'DEBUG';
    public const INFO = 'INFO';
    public const WARN = 'WARN';

    public static function exists(string $value): bool
    {
        return isset([
            self::DEBUG => true,
            self::INFO => true,
            self::WARN => true,
        ][$value]);
    }
}
