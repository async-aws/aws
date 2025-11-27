<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Choose the type of motion graphic asset that you are providing for your overlay. You can choose either a .mov file or
 * a series of .png files.
 */
final class MotionImageInsertionMode
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const MOV = 'MOV';
    public const PNG = 'PNG';

    public static function exists(string $value): bool
    {
        return isset([
            self::MOV => true,
            self::PNG => true,
        ][$value]);
    }
}
