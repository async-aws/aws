<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Ignore this setting unless you have SCTE-35 markers in your input video file. Choose Passthrough (PASSTHROUGH) if you
 * want SCTE-35 markers that appear in your input to also appear in this output. Choose None (NONE) if you don't want
 * those SCTE-35 markers in this output.
 */
final class CmfcScte35Source
{
    public const NONE = 'NONE';
    public const PASSTHROUGH = 'PASSTHROUGH';

    public static function exists(string $value): bool
    {
        return isset([
            self::NONE => true,
            self::PASSTHROUGH => true,
        ][$value]);
    }
}
