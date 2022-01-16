<?php

namespace AsyncAws\Kms\Enum;

/**
 * Specifies the length of the data key. Use `AES_128` to generate a 128-bit symmetric key, or `AES_256` to generate a
 * 256-bit symmetric key.
 * You must specify either the `KeySpec` or the `NumberOfBytes` parameter (but not both) in every `GenerateDataKey`
 * request.
 */
final class DataKeySpec
{
    public const AES_128 = 'AES_128';
    public const AES_256 = 'AES_256';

    public static function exists(string $value): bool
    {
        return isset([
            self::AES_128 => true,
            self::AES_256 => true,
        ][$value]);
    }
}
