<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use this setting to specify whether this output has a variable bitrate (VBR), constant bitrate (CBR) or
 * quality-defined variable bitrate (QVBR).
 */
final class H265RateControlMode
{
    public const CBR = 'CBR';
    public const QVBR = 'QVBR';
    public const VBR = 'VBR';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CBR => true,
            self::QVBR => true,
            self::VBR => true,
        ][$value]);
    }
}
