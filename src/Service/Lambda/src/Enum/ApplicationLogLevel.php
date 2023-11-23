<?php

namespace AsyncAws\Lambda\Enum;

final class ApplicationLogLevel
{
    public const DEBUG = 'DEBUG';
    public const ERROR = 'ERROR';
    public const FATAL = 'FATAL';
    public const INFO = 'INFO';
    public const TRACE = 'TRACE';
    public const WARN = 'WARN';

    public static function exists(string $value): bool
    {
        return isset([
            self::DEBUG => true,
            self::ERROR => true,
            self::FATAL => true,
            self::INFO => true,
            self::TRACE => true,
            self::WARN => true,
        ][$value]);
    }
}
