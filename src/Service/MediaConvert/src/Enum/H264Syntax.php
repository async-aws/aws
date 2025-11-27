<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Produces a bitstream compliant with SMPTE RP-2027.
 */
final class H264Syntax
{
    public const DEFAULT = 'DEFAULT';
    public const RP2027 = 'RP2027';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DEFAULT => true,
            self::RP2027 => true,
        ][$value]);
    }
}
