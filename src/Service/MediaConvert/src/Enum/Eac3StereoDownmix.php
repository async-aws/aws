<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Choose how the service does stereo downmixing. This setting only applies if you keep the default value of 3/2 - L, R,
 * C, Ls, Rs for the setting Coding mode. If you choose a different value for Coding mode, the service ignores Stereo
 * downmix.
 */
final class Eac3StereoDownmix
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
