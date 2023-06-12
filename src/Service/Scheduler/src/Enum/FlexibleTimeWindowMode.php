<?php

namespace AsyncAws\Scheduler\Enum;

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
