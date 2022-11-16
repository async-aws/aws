<?php

namespace AsyncAws\Scheduler\Enum;

/**
 * The type of placement strategy. The random placement strategy randomly places tasks on available candidates. The
 * spread placement strategy spreads placement across available candidates evenly based on the field parameter. The
 * binpack strategy places tasks on available candidates that have the least available amount of the resource that is
 * specified with the field parameter. For example, if you binpack on memory, a task is placed on the instance with the
 * least amount of remaining memory (but still enough to run the task).
 */
final class PlacementStrategyType
{
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
