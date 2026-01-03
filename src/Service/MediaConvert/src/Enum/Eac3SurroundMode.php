<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When encoding 2/0 audio, sets whether Dolby Surround is matrix encoded into the two channels.
 */
final class Eac3SurroundMode
{
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';
    public const NOT_INDICATED = 'NOT_INDICATED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
            self::NOT_INDICATED => true,
        ][$value]);
    }
}
