<?php

namespace AsyncAws\Kms\Enum;

/**
 * Determines the cryptographic operations for which you can use the KMS key. The default value is `ENCRYPT_DECRYPT`.
 * This parameter is required only for asymmetric KMS keys. You can't change the `KeyUsage` value after the KMS key is
 * created.
 * Select only one valid value.
 *
 * - For symmetric KMS keys, omit the parameter or specify `ENCRYPT_DECRYPT`.
 * - For asymmetric KMS keys with RSA key material, specify `ENCRYPT_DECRYPT` or `SIGN_VERIFY`.
 * - For asymmetric KMS keys with ECC key material, specify `SIGN_VERIFY`.
 *
 * @see https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#cryptographic-operations
 */
final class KeyUsageType
{
    public const ENCRYPT_DECRYPT = 'ENCRYPT_DECRYPT';
    public const SIGN_VERIFY = 'SIGN_VERIFY';

    public static function exists(string $value): bool
    {
        return isset([
            self::ENCRYPT_DECRYPT => true,
            self::SIGN_VERIFY => true,
        ][$value]);
    }
}
