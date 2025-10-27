<?php

namespace AsyncAws\CloudWatchLogs\Enum;

final class DataProtectionStatus
{
    public const ACTIVATED = 'ACTIVATED';
    public const ARCHIVED = 'ARCHIVED';
    public const DELETED = 'DELETED';
    public const DISABLED = 'DISABLED';

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
