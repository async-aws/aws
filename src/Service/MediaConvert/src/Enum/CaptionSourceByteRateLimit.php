<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Choose whether to limit the byte rate at which your SCC input captions are inserted into your output. To not limit
 * the caption rate: We recommend that you keep the default value, Disabled. MediaConvert inserts captions in your
 * output according to the byte rates listed in the EIA-608 specification, typically 2 or 3 caption bytes per frame
 * depending on your output frame rate. To limit your output caption rate: Choose Enabled. Choose this option if your
 * downstream systems require a maximum of 2 caption bytes per frame. Note that this setting has no effect when your
 * output frame rate is 30 or 60.
 */
final class CaptionSourceByteRateLimit
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
