<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Enable Deblock (InputDeblockFilter) to produce smoother motion in the output. Default is disabled. Only manually
 * controllable for MPEG2 and uncompressed video inputs.
 */
final class InputDeblockFilter
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
