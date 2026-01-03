<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Only use this setting when you change the default value, Auto, for the setting H264AdaptiveQuantization. When you
 * keep all defaults, excluding H264AdaptiveQuantization and all other adaptive quantization from your JSON job
 * specification, MediaConvert automatically applies the best types of quantization for your video content. When you set
 * H264AdaptiveQuantization to a value other than AUTO, the default value for H264SpatialAdaptiveQuantization is
 * Enabled. Keep this default value to adjust quantization within each frame based on spatial variation of content
 * complexity. When you enable this feature, the encoder uses fewer bits on areas that can sustain more distortion with
 * no noticeable visual degradation and uses more bits on areas where any small distortion will be noticeable. For
 * example, complex textured blocks are encoded with fewer bits and smooth textured blocks are encoded with more bits.
 * Enabling this feature will almost always improve your video quality. Note, though, that this feature doesn't take
 * into account where the viewer's attention is likely to be. If viewers are likely to be focusing their attention on a
 * part of the screen with a lot of complex texture, you might choose to set H264SpatialAdaptiveQuantization to
 * Disabled. Related setting: When you enable spatial adaptive quantization, set the value for Adaptive quantization
 * depending on your content. For homogeneous content, such as cartoons and video games, set it to Low. For content with
 * a wider variety of textures, set it to High or Higher. To manually enable or disable H264SpatialAdaptiveQuantization,
 * you must set Adaptive quantization to a value other than AUTO.
 */
final class H264SpatialAdaptiveQuantization
{
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
        ][$value]);
    }
}
