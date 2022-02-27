<?php

namespace AsyncAws\S3\Enum;

/**
 * Specifies whether you want to apply a legal hold to the copied object.
 */
final class ObjectLockLegalHoldStatus
{
    public const OFF = 'OFF';
    public const ON = 'ON';

    public static function exists(string $value): bool
    {
        return isset([
            self::OFF => true,
            self::ON => true,
        ][$value]);
    }
}
