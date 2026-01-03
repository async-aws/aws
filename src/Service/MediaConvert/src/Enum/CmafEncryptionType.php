<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the encryption scheme that you want the service to use when encrypting your CMAF segments. Choose AES-CBC
 * subsample or AES_CTR.
 */
final class CmafEncryptionType
{
    public const AES_CTR = 'AES_CTR';
    public const SAMPLE_AES = 'SAMPLE_AES';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AES_CTR => true,
            self::SAMPLE_AES => true,
        ][$value]);
    }
}
