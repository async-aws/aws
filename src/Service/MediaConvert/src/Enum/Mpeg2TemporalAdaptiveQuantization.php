<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Keep the default value, Enabled, to adjust quantization within each frame based on temporal variation of content
 * complexity. When you enable this feature, the encoder uses fewer bits on areas of the frame that aren't moving and
 * uses more bits on complex objects with sharp edges that move a lot. For example, this feature improves the
 * readability of text tickers on newscasts and scoreboards on sports matches. Enabling this feature will almost always
 * improve your video quality. Note, though, that this feature doesn't take into account where the viewer's attention is
 * likely to be. If viewers are likely to be focusing their attention on a part of the screen that doesn't have moving
 * objects with sharp edges, such as sports athletes' faces, you might choose to disable this feature. Related setting:
 * When you enable temporal quantization, adjust the strength of the filter with the setting Adaptive quantization.
 */
final class Mpeg2TemporalAdaptiveQuantization
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
