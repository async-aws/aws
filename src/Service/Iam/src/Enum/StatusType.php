<?php

namespace AsyncAws\Iam\Enum;

final class StatusType
{
    public const ACTIVE = 'Active';
    public const EXPIRED = 'Expired';
    public const INACTIVE = 'Inactive';

    public static function exists(string $value): bool
    {
        return isset([
            self::ACTIVE => true,
            self::EXPIRED => true,
            self::INACTIVE => true,
        ][$value]);
    }
}
