<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify which SPEKE version 2.0 audio preset MediaConvert uses to request content keys from your SPEKE server. For
 * more information, see: https://docs.aws.amazon.com/mediaconvert/latest/ug/drm-content-speke-v2-presets.html To
 * encrypt to your audio outputs, choose from the following: Audio preset 1, Audio preset 2, or Audio preset 3. To
 * encrypt your audio outputs, using the same content key for both your audio and video outputs: Choose Shared. When you
 * do, you must also set SPEKE v2.0 video preset to Shared. To not encrypt your audio outputs: Choose Unencrypted. When
 * you do, to encrypt your video outputs, you must also specify a SPEKE v2.0 video preset (other than Shared or
 * Unencrypted).
 */
final class PresetSpeke20Audio
{
    public const PRESET_AUDIO_1 = 'PRESET_AUDIO_1';
    public const PRESET_AUDIO_2 = 'PRESET_AUDIO_2';
    public const PRESET_AUDIO_3 = 'PRESET_AUDIO_3';
    public const SHARED = 'SHARED';
    public const UNENCRYPTED = 'UNENCRYPTED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::PRESET_AUDIO_1 => true,
            self::PRESET_AUDIO_2 => true,
            self::PRESET_AUDIO_3 => true,
            self::SHARED => true,
            self::UNENCRYPTED => true,
        ][$value]);
    }
}
