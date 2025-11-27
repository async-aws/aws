<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the AAC rate control mode. For a constant bitrate: Choose CBR. Your AAC output bitrate will be equal to the
 * value that you choose for Bitrate. For a variable bitrate: Choose VBR. Your AAC output bitrate will vary according to
 * your audio content and the value that you choose for Bitrate quality.
 */
final class AacRateControlMode
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
