<?php

namespace AsyncAws\Scheduler\Enum;

final class FlexibleTimeWindowMode
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
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
