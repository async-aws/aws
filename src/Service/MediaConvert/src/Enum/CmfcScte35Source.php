<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Ignore this setting unless you have SCTE-35 markers in your input video file. Choose Passthrough if you want SCTE-35
 * markers that appear in your input to also appear in this output. Choose None if you don't want those SCTE-35 markers
 * in this output.
 */
final class CmfcScte35Source
{
    public const NONE = 'NONE';
    public const PASSTHROUGH = 'PASSTHROUGH';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::NONE => true,
            self::PASSTHROUGH => true,
        ][$value]);
    }
}
