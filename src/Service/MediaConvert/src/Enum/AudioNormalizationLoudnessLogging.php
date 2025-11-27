<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * If set to LOG, log each output's audio track loudness to a CSV file.
 */
final class AudioNormalizationLoudnessLogging
{
    public const DONT_LOG = 'DONT_LOG';
    public const LOG = 'LOG';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DONT_LOG => true,
            self::LOG => true,
        ][$value]);
    }
}
