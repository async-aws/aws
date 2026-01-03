<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify which SPEKE version 2.0 video preset MediaConvert uses to request content keys from your SPEKE server. For
 * more information, see: https://docs.aws.amazon.com/mediaconvert/latest/ug/drm-content-speke-v2-presets.html To
 * encrypt to your video outputs, choose from the following: Video preset 1, Video preset 2, Video preset 3, Video
 * preset 4, Video preset 5, Video preset 6, Video preset 7, or Video preset 8. To encrypt your video outputs, using the
 * same content key for both your video and audio outputs: Choose Shared. When you do, you must also set SPEKE v2.0
 * audio preset to Shared. To not encrypt your video outputs: Choose Unencrypted. When you do, to encrypt your audio
 * outputs, you must also specify a SPEKE v2.0 audio preset (other than Shared or Unencrypted).
 */
final class PresetSpeke20Video
{
    public const PRESET_VIDEO_1 = 'PRESET_VIDEO_1';
    public const PRESET_VIDEO_2 = 'PRESET_VIDEO_2';
    public const PRESET_VIDEO_3 = 'PRESET_VIDEO_3';
    public const PRESET_VIDEO_4 = 'PRESET_VIDEO_4';
    public const PRESET_VIDEO_5 = 'PRESET_VIDEO_5';
    public const PRESET_VIDEO_6 = 'PRESET_VIDEO_6';
    public const PRESET_VIDEO_7 = 'PRESET_VIDEO_7';
    public const PRESET_VIDEO_8 = 'PRESET_VIDEO_8';
    public const SHARED = 'SHARED';
    public const UNENCRYPTED = 'UNENCRYPTED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::PRESET_VIDEO_1 => true,
            self::PRESET_VIDEO_2 => true,
            self::PRESET_VIDEO_3 => true,
            self::PRESET_VIDEO_4 => true,
            self::PRESET_VIDEO_5 => true,
            self::PRESET_VIDEO_6 => true,
            self::PRESET_VIDEO_7 => true,
            self::PRESET_VIDEO_8 => true,
            self::SHARED => true,
            self::UNENCRYPTED => true,
        ][$value]);
    }
}
