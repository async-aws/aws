<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * If set to LOG, log each output's audio track loudness to a CSV file.
 */
final class AudioNormalizationLoudnessLogging
{
    public const DONT_LOG = 'DONT_LOG';
    public const LOG = 'LOG';

    public static function exists(string $value): bool
    {
        return isset([
            self::DONT_LOG => true,
            self::LOG => true,
        ][$value]);
    }
}
