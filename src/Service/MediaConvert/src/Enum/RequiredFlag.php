<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Set to ENABLED to force a rendition to be included.
 */
final class RequiredFlag
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
