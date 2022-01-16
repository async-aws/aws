<?php

namespace AsyncAws\Kms\Enum;

/**
 * Specifies the encryption algorithm that will be used to decrypt the ciphertext. Specify the same algorithm that was
 * used to encrypt the data. If you specify a different algorithm, the `Decrypt` operation fails.
 * This parameter is required only when the ciphertext was encrypted under an asymmetric KMS key. The default value,
 * `SYMMETRIC_DEFAULT`, represents the only supported algorithm that is valid for symmetric KMS keys.
 */
final class EncryptionAlgorithmSpec
{
    public const RSAES_OAEP_SHA_1 = 'RSAES_OAEP_SHA_1';
    public const RSAES_OAEP_SHA_256 = 'RSAES_OAEP_SHA_256';
    public const SYMMETRIC_DEFAULT = 'SYMMETRIC_DEFAULT';

    public static function exists(string $value): bool
    {
        return isset([
            self::RSAES_OAEP_SHA_1 => true,
            self::RSAES_OAEP_SHA_256 => true,
            self::SYMMETRIC_DEFAULT => true,
        ][$value]);
    }
}
