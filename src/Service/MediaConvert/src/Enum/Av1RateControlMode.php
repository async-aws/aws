<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * 'With AV1 outputs, for rate control mode, MediaConvert supports only quality-defined variable bitrate (QVBR). You
 * can''t use CBR or VBR.'.
 */
final class Av1RateControlMode
{
    public const QVBR = 'QVBR';

    public static function exists(string $value): bool
    {
        return isset([
            self::QVBR => true,
        ][$value]);
    }
}
