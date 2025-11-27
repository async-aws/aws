<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify whether your input audio has an additional center rear surround channel matrix encoded into your left and
 * right surround channels.
 */
final class Eac3AtmosSurroundExMode
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
