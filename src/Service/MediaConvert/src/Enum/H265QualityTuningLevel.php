<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Optional. Use Quality tuning level to choose how you want to trade off encoding speed for output video quality. The
 * default behavior is faster, lower quality, single-pass encoding.
 */
final class H265QualityTuningLevel
{
    public const MULTI_PASS_HQ = 'MULTI_PASS_HQ';
    public const SINGLE_PASS = 'SINGLE_PASS';
    public const SINGLE_PASS_HQ = 'SINGLE_PASS_HQ';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::MULTI_PASS_HQ => true,
            self::SINGLE_PASS => true,
            self::SINGLE_PASS_HQ => true,
        ][$value]);
    }
}
