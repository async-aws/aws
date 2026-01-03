<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Optional. Use Quality tuning level to choose how you want to trade off encoding speed for output video quality. The
 * default behavior is faster, lower quality, single-pass encoding.
 */
final class Mpeg2QualityTuningLevel
{
    public const MULTI_PASS = 'MULTI_PASS';
    public const SINGLE_PASS = 'SINGLE_PASS';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::MULTI_PASS => true,
            self::SINGLE_PASS => true,
        ][$value]);
    }
}
