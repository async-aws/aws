<?php

namespace AsyncAws\S3\Enum;

class ObjectLockLegalHoldStatus
{
    public const OFF = 'OFF';
    public const ON = 'ON';

    public static function exists(string $value): bool
    {
        $values = [
            self::OFF => true,
            self::ON => true,
        ];

        return isset($values[$value]);
    }
}
