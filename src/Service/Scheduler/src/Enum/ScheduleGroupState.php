<?php

namespace AsyncAws\Scheduler\Enum;

final class ScheduleGroupState
{
    public const ACTIVE = 'ACTIVE';
    public const DELETING = 'DELETING';

    public static function exists(string $value): bool
    {
        return isset([
            self::ACTIVE => true,
            self::DELETING => true,
        ][$value]);
    }
}
