<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use this setting for interlaced outputs, when your output frame rate is half of your input frame rate. In this
 * situation, choose Optimized interlacing to create a better quality interlaced output. In this case, each progressive
 * frame from the input corresponds to an interlaced field in the output. Keep the default value, Basic interlacing, for
 * all other output frame rates. With basic interlacing, MediaConvert performs any frame rate conversion first and then
 * interlaces the frames. When you choose Optimized interlacing and you set your output frame rate to a value that isn't
 * suitable for optimized interlacing, MediaConvert automatically falls back to basic interlacing. Required settings: To
 * use optimized interlacing, you must set Telecine to None or Soft. You can't use optimized interlacing for hard
 * telecine outputs. You must also set Interlace mode to a value other than Progressive.
 */
final class AvcIntraScanTypeConversionMode
{
    public const INTERLACED = 'INTERLACED';
    public const INTERLACED_OPTIMIZE = 'INTERLACED_OPTIMIZE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::INTERLACED => true,
            self::INTERLACED_OPTIMIZE => true,
        ][$value]);
    }
}
