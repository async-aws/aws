<?php

namespace AsyncAws\Scheduler\Enum;

/**
 * Determines whether the schedule is invoked within a flexible time window.
 */
final class FlexibleTimeWindowMode
{
    public const FLEXIBLE = 'FLEXIBLE';
    public const OFF = 'OFF';

    public static function exists(string $value): bool
    {
        return isset([
            self::FLEXIBLE => true,
            self::OFF => true,
        ][$value]);
    }
}
