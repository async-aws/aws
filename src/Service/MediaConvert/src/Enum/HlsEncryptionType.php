<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Encrypts the segments with the given encryption scheme. Leave blank to disable. Selecting 'Disabled' in the web
 * interface also disables encryption.
 */
final class HlsEncryptionType
{
    public const AES128 = 'AES128';
    public const SAMPLE_AES = 'SAMPLE_AES';

    public static function exists(string $value): bool
    {
        return isset([
            self::AES128 => true,
            self::SAMPLE_AES => true,
        ][$value]);
    }
}
