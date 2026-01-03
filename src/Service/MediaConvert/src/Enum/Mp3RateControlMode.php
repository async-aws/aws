<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify whether the service encodes this MP3 audio output with a constant bitrate (CBR) or a variable bitrate (VBR).
 */
final class Mp3RateControlMode
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
