<?php

namespace AsyncAws\S3\Enum;

class ObjectLockMode
{
    public const COMPLIANCE = 'COMPLIANCE';
    public const GOVERNANCE = 'GOVERNANCE';

    public static function exists(string $value): bool
    {
        return isset([
            self::COMPLIANCE => true,
            self::GOVERNANCE => true,
        ][$value]);
    }
}
