<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify whether your motion graphic overlay repeats on a loop or plays only once.
 */
final class MotionImagePlayback
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const ONCE = 'ONCE';
    public const REPEAT = 'REPEAT';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ONCE => true,
            self::REPEAT => true,
        ][$value]);
    }
}
