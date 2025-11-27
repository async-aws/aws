<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the file format for your wave audio output. To use a RIFF wave format: Keep the default value, RIFF. If your
 * output audio is likely to exceed 4GB in file size, or if you otherwise need the extended support of the RF64 format:
 * Choose RF64. If your player only supports the extensible wave format: Choose Extensible.
 */
final class WavFormat
{
    public const EXTENSIBLE = 'EXTENSIBLE';
    public const RF64 = 'RF64';
    public const RIFF = 'RIFF';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::EXTENSIBLE => true,
            self::RF64 => true,
            self::RIFF => true,
        ][$value]);
    }
}
