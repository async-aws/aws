<?php

namespace AsyncAws\Scheduler\Enum;

final class PlacementStrategyType
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const BINPACK = 'binpack';
    public const RANDOM = 'random';
    public const SPREAD = 'spread';

    public static function exists(string $value): bool
    {
        return isset([
            self::BINPACK => true,
            self::RANDOM => true,
            self::SPREAD => true,
        ][$value]);
    }
}
