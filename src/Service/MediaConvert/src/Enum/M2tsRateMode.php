<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When set to CBR, inserts null packets into transport stream to fill specified bitrate. When set to VBR, the bitrate
 * setting acts as the maximum bitrate, but the output will not be padded up to that bitrate.
 */
final class M2tsRateMode
{
    public const CBR = 'CBR';
    public const VBR = 'VBR';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CBR => true,
            self::VBR => true,
        ][$value]);
    }
}
