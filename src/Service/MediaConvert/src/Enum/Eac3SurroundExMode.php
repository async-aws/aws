<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When encoding 3/2 audio, sets whether an extra center back surround channel is matrix encoded into the left and right
 * surround channels.
 */
final class Eac3SurroundExMode
{
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';
    public const NOT_INDICATED = 'NOT_INDICATED';

    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
            self::NOT_INDICATED => true,
        ][$value]);
    }
}
