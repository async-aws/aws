<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Optional. Choose the scan line type for this output. If you don't specify a value, MediaConvert will create a
 * progressive output.
 */
final class Vc3InterlaceMode
{
    public const INTERLACED = 'INTERLACED';
    public const PROGRESSIVE = 'PROGRESSIVE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::INTERLACED => true,
            self::PROGRESSIVE => true,
        ][$value]);
    }
}
