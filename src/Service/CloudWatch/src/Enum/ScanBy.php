<?php

namespace AsyncAws\CloudWatch\Enum;

/**
 * The order in which data points should be returned. `TimestampDescending` returns the newest data first and paginates
 * when the `MaxDatapoints` limit is reached. `TimestampAscending` returns the oldest data first and paginates when the
 * `MaxDatapoints` limit is reached.
 */
final class ScanBy
{
    public const TIMESTAMP_ASCENDING = 'TimestampAscending';
    public const TIMESTAMP_DESCENDING = 'TimestampDescending';

    public static function exists(string $value): bool
    {
        return isset([
            self::TIMESTAMP_ASCENDING => true,
            self::TIMESTAMP_DESCENDING => true,
        ][$value]);
    }
}
