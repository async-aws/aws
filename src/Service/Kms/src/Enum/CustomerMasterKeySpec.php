<?php

namespace AsyncAws\Kms\Enum;

/**
 * Instead, use the `KeySpec` parameter.
 * The `KeySpec` and `CustomerMasterKeySpec` parameters work the same way. Only the names differ. We recommend that you
 * use `KeySpec` parameter in your code. However, to avoid breaking changes, KMS will support both parameters.
 */
final class CustomerMasterKeySpec
{
    public const ECC_NIST_P256 = 'ECC_NIST_P256';
    public const ECC_NIST_P384 = 'ECC_NIST_P384';
    public const ECC_NIST_P521 = 'ECC_NIST_P521';
    public const ECC_SECG_P256K1 = 'ECC_SECG_P256K1';
    public const RSA_2048 = 'RSA_2048';
    public const RSA_3072 = 'RSA_3072';
    public const RSA_4096 = 'RSA_4096';
    public const SYMMETRIC_DEFAULT = 'SYMMETRIC_DEFAULT';

    public static function exists(string $value): bool
    {
        return isset([
            self::ECC_NIST_P256 => true,
            self::ECC_NIST_P384 => true,
            self::ECC_NIST_P521 => true,
            self::ECC_SECG_P256K1 => true,
            self::RSA_2048 => true,
            self::RSA_3072 => true,
            self::RSA_4096 => true,
            self::SYMMETRIC_DEFAULT => true,
        ][$value]);
    }
}
