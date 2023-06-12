<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify whether your motion graphic overlay repeats on a loop or plays only once.
 */
final class MotionImagePlayback
{
    public const ONCE = 'ONCE';
    public const REPEAT = 'REPEAT';

    public static function exists(string $value): bool
    {
        return isset([
            self::ONCE => true,
            self::REPEAT => true,
        ][$value]);
    }
}
