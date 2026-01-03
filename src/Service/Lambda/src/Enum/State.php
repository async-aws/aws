<?php

namespace AsyncAws\Lambda\Enum;

final class State
{
    public const ACTIVE = 'Active';
    public const ACTIVE_NON_INVOCABLE = 'ActiveNonInvocable';
    public const DEACTIVATED = 'Deactivated';
    public const DEACTIVATING = 'Deactivating';
    public const DELETING = 'Deleting';
    public const FAILED = 'Failed';
    public const INACTIVE = 'Inactive';
    public const PENDING = 'Pending';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ACTIVE => true,
            self::ACTIVE_NON_INVOCABLE => true,
            self::DEACTIVATED => true,
            self::DEACTIVATING => true,
            self::DELETING => true,
            self::FAILED => true,
            self::INACTIVE => true,
            self::PENDING => true,
        ][$value]);
    }
}
