<?php

namespace AsyncAws\Kms\Enum;

/**
 * Determines the cryptographic operations for which you can use the KMS key. The default value is `ENCRYPT_DECRYPT`.
 * This parameter is optional when you are creating a symmetric encryption KMS key; otherwise, it is required. You can't
 * change the `KeyUsage` value after the KMS key is created.
 * Select only one valid value.
 *
 * - For symmetric encryption KMS keys, omit the parameter or specify `ENCRYPT_DECRYPT`.
 * - For HMAC KMS keys (symmetric), specify `GENERATE_VERIFY_MAC`.
 * - For asymmetric KMS keys with RSA key material, specify `ENCRYPT_DECRYPT` or `SIGN_VERIFY`.
 * - For asymmetric KMS keys with ECC key material, specify `SIGN_VERIFY`.
 *
 * @see https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#cryptographic-operations
 */
final class KeyUsageType
{
    public const ENCRYPT_DECRYPT = 'ENCRYPT_DECRYPT';
    public const GENERATE_VERIFY_MAC = 'GENERATE_VERIFY_MAC';
    public const SIGN_VERIFY = 'SIGN_VERIFY';

    public static function exists(string $value): bool
    {
        return isset([
            self::ENCRYPT_DECRYPT => true,
            self::GENERATE_VERIFY_MAC => true,
            self::SIGN_VERIFY => true,
        ][$value]);
    }
}
