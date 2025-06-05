<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When enabled, a C2PA compliant manifest will be generated, signed and embeded in the output. For more information on
 * C2PA, see https://c2pa.org/specifications/specifications/2.1/index.html.
 */
final class Mp4C2paManifest
{
    public const EXCLUDE = 'EXCLUDE';
    public const INCLUDE = 'INCLUDE';

    public static function exists(string $value): bool
    {
        return isset([
            self::EXCLUDE => true,
            self::INCLUDE => true,
        ][$value]);
    }
}
