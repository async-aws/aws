<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Rate Control Mode.
 */
final class AacRateControlMode
{
    public const CBR = 'CBR';
    public const VBR = 'VBR';

    public static function exists(string $value): bool
    {
        return isset([
            self::CBR => true,
            self::VBR => true,
        ][$value]);
    }
}
