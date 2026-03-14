<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Choose the preferred stereo downmix method. This setting tells the decoder how to downmix multi-channel audio to
 * stereo during playback.
 */
final class Ac4StereoDownmix
{
    public const DPL2 = 'DPL2';
    public const LO_RO = 'LO_RO';
    public const LT_RT = 'LT_RT';
    public const NOT_INDICATED = 'NOT_INDICATED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DPL2 => true,
            self::LO_RO => true,
            self::LT_RT => true,
            self::NOT_INDICATED => true,
        ][$value]);
    }
}
