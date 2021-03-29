<?php

namespace AsyncAws\Lambda\Enum;

/**
 * The current state of the function. When the state is `Inactive`, you can reactivate the function by invoking it.
 */
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
