<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Activates a DC highpass filter for all input channels.
 */
final class Eac3DcFilter
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
