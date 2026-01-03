<?php

namespace AsyncAws\CloudWatchLogs\Enum;

final class DataProtectionStatus
{
    public const ACTIVATED = 'ACTIVATED';
    public const ARCHIVED = 'ARCHIVED';
    public const DELETED = 'DELETED';
    public const DISABLED = 'DISABLED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ACTIVATED => true,
            self::ARCHIVED => true,
            self::DELETED => true,
            self::DISABLED => true,
        ][$value]);
    }
}
