<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When you do frame rate conversion from 23.976 frames per second (fps) to 29.97 fps, and your output scan type is
 * interlaced, you can optionally enable hard or soft telecine to create a smoother picture. Hard telecine produces a
 * 29.97i output. Soft telecine produces an output with a 23.976 output that signals to the video player device to do
 * the conversion during play back. When you keep the default value, None, MediaConvert does a standard frame rate
 * conversion to 29.97 without doing anything with the field polarity to create a smoother picture.
 */
final class Mpeg2Telecine
{
    public const HARD = 'HARD';
    public const NONE = 'NONE';
    public const SOFT = 'SOFT';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::HARD => true,
            self::NONE => true,
            self::SOFT => true,
        ][$value]);
    }
}
