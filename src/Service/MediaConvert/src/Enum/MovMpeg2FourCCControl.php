<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When set to XDCAM, writes MPEG2 video streams into the QuickTime file using XDCAM fourcc codes. This increases
 * compatibility with Apple editors and players, but may decrease compatibility with other players. Only applicable when
 * the video codec is MPEG2.
 */
final class MovMpeg2FourCCControl
{
    public const MPEG = 'MPEG';
    public const XDCAM = 'XDCAM';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::MPEG => true,
            self::XDCAM => true,
        ][$value]);
    }
}
