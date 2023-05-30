<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * With the VP9 codec, you can use only the variable bitrate (VBR) rate control mode.
 */
final class Vp9RateControlMode
{
    public const VBR = 'VBR';

    public static function exists(string $value): bool
    {
        return isset([
            self::VBR => true,
        ][$value]);
    }
}
