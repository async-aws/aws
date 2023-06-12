<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Inserts a free-space box immediately after the moov box.
 */
final class Mp4FreeSpaceBox
{
    public const EXCLUDE = 'EXCLUDE';
    public const INCLUDE = 'INCLUDE';

    public static function exists(string $value): bool
    {
        return isset([
            self::EXCLUDE => true,
            self::INCLUDE => true,
        ][$value]);
    }
}
