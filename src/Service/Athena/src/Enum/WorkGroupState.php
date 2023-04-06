<?php

namespace AsyncAws\Athena\Enum;

/**
 * The state of the workgroup: ENABLED or DISABLED.
 */
final class WorkGroupState
{
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';

    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
        ][$value]);
    }
}
