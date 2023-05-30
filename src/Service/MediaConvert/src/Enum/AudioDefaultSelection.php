<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Enable this setting on one audio selector to set it as the default for the job. The service uses this default for
 * outputs where it can't find the specified input audio. If you don't set a default, those outputs have no audio.
 */
final class AudioDefaultSelection
{
    public const DEFAULT = 'DEFAULT';
    public const NOT_DEFAULT = 'NOT_DEFAULT';

    public static function exists(string $value): bool
    {
        return isset([
            self::DEFAULT => true,
            self::NOT_DEFAULT => true,
        ][$value]);
    }
}
