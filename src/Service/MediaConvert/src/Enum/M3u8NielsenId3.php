<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * If INSERT, Nielsen inaudible tones for media tracking will be detected in the input audio and an equivalent ID3 tag
 * will be inserted in the output.
 */
final class M3u8NielsenId3
{
    public const INSERT = 'INSERT';
    public const NONE = 'NONE';

    public static function exists(string $value): bool
    {
        return isset([
            self::INSERT => true,
            self::NONE => true,
        ][$value]);
    }
}
