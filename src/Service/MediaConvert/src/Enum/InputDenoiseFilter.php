<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Enable Denoise (InputDenoiseFilter) to filter noise from the input. Default is disabled. Only applicable to MPEG2,
 * H.264, H.265, and uncompressed video inputs.
 */
final class InputDenoiseFilter
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
