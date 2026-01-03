<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Required when you enable Dolby Vision. Use Profile 5 to include frame-interleaved Dolby Vision metadata in your
 * output. Your input must include Dolby Vision metadata or an HDR10 YUV color space. Use Profile 8.1 to include
 * frame-interleaved Dolby Vision metadata and HDR10 metadata in your output. Your input must include Dolby Vision
 * metadata.
 */
final class DolbyVisionProfile
{
    public const PROFILE_5 = 'PROFILE_5';
    public const PROFILE_8_1 = 'PROFILE_8_1';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::PROFILE_5 => true,
            self::PROFILE_8_1 => true,
        ][$value]);
    }
}
