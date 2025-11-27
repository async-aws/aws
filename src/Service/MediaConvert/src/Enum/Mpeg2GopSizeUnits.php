<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the units for GOP size. If you don't specify a value here, by default the encoder measures GOP size in
 * frames.
 */
final class Mpeg2GopSizeUnits
{
    public const FRAMES = 'FRAMES';
    public const SECONDS = 'SECONDS';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::FRAMES => true,
            self::SECONDS => true,
        ][$value]);
    }
}
