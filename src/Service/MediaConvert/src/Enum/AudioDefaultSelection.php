<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify a fallback audio selector for this input. Use to ensure outputs have audio even when the audio selector you
 * specify in your output is missing from the source. DEFAULT (Checked in the MediaConvert console): If your output
 * settings specify an audio selector that does not exist in this input, MediaConvert uses this audio selector instead.
 * This is useful when you have multiple inputs with a different number of audio tracks. NOT_DEFAULT (Unchecked in the
 * MediaConvert console): MediaConvert will not fallback from any missing audio selector. Any output specifying a
 * missing audio selector will be silent.
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
