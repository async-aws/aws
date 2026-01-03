<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When you use the setting Time delta to adjust the sync between your sidecar captions and your video, use this setting
 * to specify the units for the delta that you specify. When you don't specify a value for Time delta units,
 * MediaConvert uses seconds by default.
 */
final class FileSourceTimeDeltaUnits
{
    public const MILLISECONDS = 'MILLISECONDS';
    public const SECONDS = 'SECONDS';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::MILLISECONDS => true,
            self::SECONDS => true,
        ][$value]);
    }
}
