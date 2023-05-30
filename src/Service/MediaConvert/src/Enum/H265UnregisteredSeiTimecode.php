<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Inserts timecode for each frame as 4 bytes of an unregistered SEI message.
 */
final class H265UnregisteredSeiTimecode
{
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
