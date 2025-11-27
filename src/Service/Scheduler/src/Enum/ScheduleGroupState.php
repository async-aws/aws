<?php

namespace AsyncAws\Scheduler\Enum;

final class ScheduleGroupState
{
    public const ACTIVE = 'ACTIVE';
    public const DELETING = 'DELETING';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ACTIVE => true,
            self::DELETING => true,
        ][$value]);
    }
}
