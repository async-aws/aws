<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * If INSERT, Nielsen inaudible tones for media tracking will be detected in the input audio and an equivalent ID3 tag
 * will be inserted in the output.
 */
final class M2tsNielsenId3
{
    public const INSERT = 'INSERT';
    public const NONE = 'NONE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::INSERT => true,
            self::NONE => true,
        ][$value]);
    }
}
