<?php

namespace AsyncAws\TimestreamWrite\Enum;

/**
 * The granularity of the timestamp unit. It indicates if the time value is in seconds, milliseconds, nanoseconds or
 * other supported values. Default is `MILLISECONDS`.
 */
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
