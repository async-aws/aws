<?php

namespace AsyncAws\Kms\Enum;

final class KeySpec
{
    public const ECC_NIST_P256 = 'ECC_NIST_P256';
    public const ECC_NIST_P384 = 'ECC_NIST_P384';
    public const ECC_NIST_P521 = 'ECC_NIST_P521';
    public const ECC_SECG_P256K1 = 'ECC_SECG_P256K1';
    public const HMAC_224 = 'HMAC_224';
    public const HMAC_256 = 'HMAC_256';
    public const HMAC_384 = 'HMAC_384';
    public const HMAC_512 = 'HMAC_512';
    public const ML_DSA_44 = 'ML_DSA_44';
    public const ML_DSA_65 = 'ML_DSA_65';
    public const ML_DSA_87 = 'ML_DSA_87';
    public const RSA_2048 = 'RSA_2048';
    public const RSA_3072 = 'RSA_3072';
    public const RSA_4096 = 'RSA_4096';
    public const SM2 = 'SM2';
    public const SYMMETRIC_DEFAULT = 'SYMMETRIC_DEFAULT';

    public static function exists(string $value): bool
    {
        return isset([
            self::ECC_NIST_P256 => true,
            self::ECC_NIST_P384 => true,
            self::ECC_NIST_P521 => true,
            self::ECC_SECG_P256K1 => true,
            self::HMAC_224 => true,
            self::HMAC_256 => true,
            self::HMAC_384 => true,
            self::HMAC_512 => true,
            self::ML_DSA_44 => true,
            self::ML_DSA_65 => true,
            self::ML_DSA_87 => true,
            self::RSA_2048 => true,
            self::RSA_3072 => true,
            self::RSA_4096 => true,
            self::SM2 => true,
            self::SYMMETRIC_DEFAULT => true,
        ][$value]);
    }
}
