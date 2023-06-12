<?php

namespace AsyncAws\Lambda\Enum;

final class State
{
    public const ACTIVE = 'Active';
    public const FAILED = 'Failed';
    public const INACTIVE = 'Inactive';
    public const PENDING = 'Pending';

    public static function exists(string $value): bool
    {
        return isset([
            self::ACTIVE => true,
            self::FAILED => true,
            self::INACTIVE => true,
            self::PENDING => true,
        ][$value]);
    }
}
