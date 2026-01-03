<?php

namespace AsyncAws\Scheduler\Enum;

final class PlacementStrategyType
{
    public const BINPACK = 'binpack';
    public const RANDOM = 'random';
    public const SPREAD = 'spread';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::BINPACK => true,
            self::RANDOM => true,
            self::SPREAD => true,
        ][$value]);
    }
}
