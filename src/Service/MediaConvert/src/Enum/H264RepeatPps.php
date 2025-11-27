<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Places a PPS header on each encoded picture, even if repeated.
 */
final class H264RepeatPps
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';

    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
        ][$value]);
    }
}
