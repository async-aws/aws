<?php

namespace AsyncAws\S3\Enum;

final class ObjectLockLegalHoldStatus
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
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
