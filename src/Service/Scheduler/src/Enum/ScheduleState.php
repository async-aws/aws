<?php

namespace AsyncAws\Scheduler\Enum;

/**
 * Specifies whether the schedule is enabled or disabled.
 */
final class ScheduleState
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
