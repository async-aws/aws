<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify whether your DRM encryption key is static or from a key provider that follows the SPEKE standard. For more
 * information about SPEKE, see https://docs.aws.amazon.com/speke/latest/documentation/what-is-speke.html.
 */
final class CmafKeyProviderType
{
    public const SPEKE = 'SPEKE';
    public const STATIC_KEY = 'STATIC_KEY';

    public static function exists(string $value): bool
    {
        return isset([
            self::SPEKE => true,
            self::STATIC_KEY => true,
        ][$value]);
    }
}
