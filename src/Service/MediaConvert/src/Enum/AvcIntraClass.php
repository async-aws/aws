<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the AVC-Intra class of your output. The AVC-Intra class selection determines the output video bit rate
 * depending on the frame rate of the output. Outputs with higher class values have higher bitrates and improved image
 * quality. Note that for Class 4K/2K, MediaConvert supports only 4:2:2 chroma subsampling.
 */
final class AvcIntraClass
{
    public const CLASS_100 = 'CLASS_100';
    public const CLASS_200 = 'CLASS_200';
    public const CLASS_4K_2K = 'CLASS_4K_2K';
    public const CLASS_50 = 'CLASS_50';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CLASS_100 => true,
            self::CLASS_200 => true,
            self::CLASS_4K_2K => true,
            self::CLASS_50 => true,
        ][$value]);
    }
}
