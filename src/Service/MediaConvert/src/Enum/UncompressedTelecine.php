<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When you do frame rate conversion from 23.976 frames per second (fps) to 29.97 fps, and your output scan type is
 * interlaced, you can optionally enable hard telecine to create a smoother picture. When you keep the default value,
 * None, MediaConvert does a standard frame rate conversion to 29.97 without doing anything with the field polarity to
 * create a smoother picture.
 */
final class UncompressedTelecine
{
    public const HARD = 'HARD';
    public const NONE = 'NONE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::HARD => true,
            self::NONE => true,
        ][$value]);
    }
}
