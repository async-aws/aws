<?php

namespace AsyncAws\Lambda\Enum;

/**
 * The status of the last update that was performed on the function. This is first set to `Successful` after function
 * creation completes.
 */
final class LastUpdateStatus
{
    public const FAILED = 'Failed';
    public const IN_PROGRESS = 'InProgress';
    public const SUCCESSFUL = 'Successful';

    public static function exists(string $value): bool
    {
        return isset([
            self::FAILED => true,
            self::IN_PROGRESS => true,
            self::SUCCESSFUL => true,
        ][$value]);
    }
}
