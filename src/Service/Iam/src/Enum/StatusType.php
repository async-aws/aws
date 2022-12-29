<?php

namespace AsyncAws\Iam\Enum;

/**
 * The status of the access key. `Active` means that the key is valid for API calls, while `Inactive` means it is not.
 */
final class StatusType
{
    public const ACTIVE = 'Active';
    public const INACTIVE = 'Inactive';

    public static function exists(string $value): bool
    {
        return isset([
            self::ACTIVE => true,
            self::INACTIVE => true,
        ][$value]);
    }
}
