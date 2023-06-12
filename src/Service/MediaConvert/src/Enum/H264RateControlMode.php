<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use this setting to specify whether this output has a variable bitrate (VBR), constant bitrate (CBR) or
 * quality-defined variable bitrate (QVBR).
 */
final class H264RateControlMode
{
    public const CBR = 'CBR';
    public const QVBR = 'QVBR';
    public const VBR = 'VBR';

    public static function exists(string $value): bool
    {
        return isset([
            self::CBR => true,
            self::QVBR => true,
            self::VBR => true,
        ][$value]);
    }
}
