<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * 'With AV1 outputs, for rate control mode, MediaConvert supports quality-defined variable bitrate (QVBR) and constant
 * bitrate (CBR). You can''t use VBR.'.
 */
final class Av1RateControlMode
{
    public const CBR = 'CBR';
    public const QVBR = 'QVBR';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CBR => true,
            self::QVBR => true,
        ][$value]);
    }
}
