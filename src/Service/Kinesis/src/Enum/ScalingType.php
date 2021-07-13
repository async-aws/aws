<?php

namespace AsyncAws\Kinesis\Enum;

/**
 * The scaling type. Uniform scaling creates shards of equal size.
 */
final class ScalingType
{
    public const UNIFORM_SCALING = 'UNIFORM_SCALING';

    public static function exists(string $value): bool
    {
        return isset([
            self::UNIFORM_SCALING => true,
        ][$value]);
    }
}
