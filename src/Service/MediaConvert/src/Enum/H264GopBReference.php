<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify whether to allow B-frames to be referenced by other frame types. To use reference B-frames when your GOP
 * structure has 1 or more B-frames: Leave blank or keep the default value Enabled. We recommend that you choose Enabled
 * to help improve the video quality of your output relative to its bitrate. To not use reference B-frames: Choose
 * Disabled.
 */
final class H264GopBReference
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
