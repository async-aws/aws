<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Enable this setting to have the encoder reduce I-frame pop. I-frame pop appears as a visual flicker that can arise
 * when the encoder saves bits by copying some macroblocks many times from frame to frame, and then refreshes them at
 * the I-frame. When you enable this setting, the encoder updates these macroblocks slightly more often to smooth out
 * the flicker. This setting is disabled by default. Related setting: In addition to enabling this setting, you must
 * also set adaptiveQuantization to a value other than Off.
 */
final class H265FlickerAdaptiveQuantization
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
