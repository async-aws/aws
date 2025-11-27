<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * If set to ATTENUATE_3_DB, applies a 3 dB attenuation to the surround channels. Only used for 3/2 coding mode.
 */
final class Eac3AttenuationControl
{
    public const ATTENUATE_3_DB = 'ATTENUATE_3_DB';
    public const NONE = 'NONE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ATTENUATE_3_DB => true,
            self::NONE => true,
        ][$value]);
    }
}
