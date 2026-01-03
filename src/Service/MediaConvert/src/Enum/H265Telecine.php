<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * This field applies only if the Streams > Advanced > Framerate field is set to 29.970. This field works with the
 * Streams > Advanced > Preprocessors > Deinterlacer field and the Streams > Advanced > Interlaced Mode field to
 * identify the scan type for the output: Progressive, Interlaced, Hard Telecine or Soft Telecine. - Hard: produces
 * 29.97i output from 23.976 input. - Soft: produces 23.976; the player converts this output to 29.97i.
 */
final class H265Telecine
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
