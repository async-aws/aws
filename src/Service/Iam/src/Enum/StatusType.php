<?php

namespace AsyncAws\Iam\Enum;

final class StatusType
{
    public const ACTIVE = 'Active';
    public const EXPIRED = 'Expired';
    public const INACTIVE = 'Inactive';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ACTIVE => true,
            self::EXPIRED => true,
            self::INACTIVE => true,
        ][$value]);
    }
}
