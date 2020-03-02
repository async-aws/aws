<?php

namespace AsyncAws\S3\Enum;

class ObjectLockMode
{
    public const COMPLIANCE = 'COMPLIANCE';
    public const GOVERNANCE = 'GOVERNANCE';

    public static function exists(string $value): bool
    {
        $values = [
            self::COMPLIANCE => true,
            self::GOVERNANCE => true,
        ];

        return isset($values[$value]);
    }
}
