<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * The Initialization Vector is a 128-bit number used in conjunction with the key for encrypting blocks. If set to
 * INCLUDE, Initialization Vector is listed in the manifest. Otherwise Initialization Vector is not in the manifest.
 */
final class HlsInitializationVectorInManifest
{
    public const EXCLUDE = 'EXCLUDE';
    public const INCLUDE = 'INCLUDE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::EXCLUDE => true,
            self::INCLUDE => true,
        ][$value]);
    }
}
