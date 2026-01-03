<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use Deinterlacer to choose how the service will do deinterlacing. Default is Deinterlace.
 * - Deinterlace converts interlaced to progressive.
 * - Inverse telecine converts Hard Telecine 29.97i to progressive 23.976p.
 * - Adaptive auto-detects and converts to progressive.
 */
final class DeinterlacerMode
{
    public const ADAPTIVE = 'ADAPTIVE';
    public const DEINTERLACE = 'DEINTERLACE';
    public const INVERSE_TELECINE = 'INVERSE_TELECINE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ADAPTIVE => true,
            self::DEINTERLACE => true,
            self::INVERSE_TELECINE => true,
        ][$value]);
    }
}
