<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the encryption mode that you used to encrypt your input files.
 */
final class DecryptionMode
{
    public const AES_CBC = 'AES_CBC';
    public const AES_CTR = 'AES_CTR';
    public const AES_GCM = 'AES_GCM';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AES_CBC => true,
            self::AES_CTR => true,
            self::AES_GCM => true,
        ][$value]);
    }
}
