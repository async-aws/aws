<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify whether this output's video uses the D10 syntax. Keep the default value to not use the syntax. Related
 * settings: When you choose D10 (D_10) for your MXF profile (profile), you must also set this value to D10 (D_10).
 */
final class Mpeg2Syntax
{
    public const DEFAULT = 'DEFAULT';
    public const D_10 = 'D_10';

    public static function exists(string $value): bool
    {
        return isset([
            self::DEFAULT => true,
            self::D_10 => true,
        ][$value]);
    }
}
