<?php

namespace AsyncAws\TimestreamWrite\Enum;

final class TimeUnit
{
    public const MICROSECONDS = 'MICROSECONDS';
    public const MILLISECONDS = 'MILLISECONDS';
    public const NANOSECONDS = 'NANOSECONDS';
    public const SECONDS = 'SECONDS';

    public static function exists(string $value): bool
    {
        return isset([
            self::MICROSECONDS => true,
            self::MILLISECONDS => true,
            self::NANOSECONDS => true,
            self::SECONDS => true,
        ][$value]);
    }
}
