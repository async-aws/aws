<?php

namespace AsyncAws\CloudWatch\Enum;

final class ScanBy
{
    public const TIMESTAMP_ASCENDING = 'TimestampAscending';
    public const TIMESTAMP_DESCENDING = 'TimestampDescending';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::TIMESTAMP_ASCENDING => true,
            self::TIMESTAMP_DESCENDING => true,
        ][$value]);
    }
}
