<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * The Quality tuning level you choose represents a trade-off between the encoding speed of your job and the output
 * video quality. For the fastest encoding speed at the cost of video quality: Choose Single pass. For a good balance
 * between encoding speed and video quality: Leave blank or keep the default value Single pass HQ. For the best video
 * quality, at the cost of encoding speed: Choose Multi pass HQ. MediaConvert performs an analysis pass on your input
 * followed by an encoding pass. Outputs that use this feature incur pro-tier pricing.
 */
final class H264QualityTuningLevel
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
