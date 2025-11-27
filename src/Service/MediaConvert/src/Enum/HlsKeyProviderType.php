<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify whether your DRM encryption key is static or from a key provider that follows the SPEKE standard. For more
 * information about SPEKE, see https://docs.aws.amazon.com/speke/latest/documentation/what-is-speke.html.
 */
final class HlsKeyProviderType
{
    public const SPEKE = 'SPEKE';
    public const STATIC_KEY = 'STATIC_KEY';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::SPEKE => true,
            self::STATIC_KEY => true,
        ][$value]);
    }
}
