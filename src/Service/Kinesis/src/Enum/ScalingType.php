<?php

namespace AsyncAws\Kinesis\Enum;

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
