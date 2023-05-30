<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * The service defaults to using RIFF for WAV outputs. If your output audio is likely to exceed 4 GB in file size, or if
 * you otherwise need the extended support of the RF64 format, set your output WAV file format to RF64.
 */
final class WavFormat
{
    public const RF64 = 'RF64';
    public const RIFF = 'RIFF';

    public static function exists(string $value): bool
    {
        return isset([
            self::RF64 => true,
            self::RIFF => true,
        ][$value]);
    }
}
