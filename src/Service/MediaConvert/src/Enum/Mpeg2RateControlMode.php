<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use Rate control mode to specify whether the bitrate is variable (vbr) or constant (cbr).
 */
final class Mpeg2RateControlMode
{
    public const CBR = 'CBR';
    public const VBR = 'VBR';

    public static function exists(string $value): bool
    {
        return isset([
            self::CBR => true,
            self::VBR => true,
        ][$value]);
    }
}
