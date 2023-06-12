<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specification to use (RFC-6381 or the default RFC-4281) during m3u8 playlist generation.
 */
final class CmafCodecSpecification
{
    public const RFC_4281 = 'RFC_4281';
    public const RFC_6381 = 'RFC_6381';

    public static function exists(string $value): bool
    {
        return isset([
            self::RFC_4281 => true,
            self::RFC_6381 => true,
        ][$value]);
    }
}
