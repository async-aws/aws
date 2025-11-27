<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use Slow PAL pitch correction to compensate for audio pitch changes during slow PAL frame rate conversion. This
 * setting only applies when Slow PAL is enabled in your output video codec settings. To automatically apply audio pitch
 * correction: Choose Enabled. MediaConvert automatically applies a pitch correction to your output to match the
 * original content's audio pitch. To not apply audio pitch correction: Keep the default value, Disabled.
 */
final class SlowPalPitchCorrection
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
