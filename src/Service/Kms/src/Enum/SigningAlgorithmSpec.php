<?php

namespace AsyncAws\Kms\Enum;

final class SigningAlgorithmSpec
{
    public const ECDSA_SHA_256 = 'ECDSA_SHA_256';
    public const ECDSA_SHA_384 = 'ECDSA_SHA_384';
    public const ECDSA_SHA_512 = 'ECDSA_SHA_512';
    public const ML_DSA_SHAKE_256 = 'ML_DSA_SHAKE_256';
    public const RSASSA_PKCS1_V1_5_SHA_256 = 'RSASSA_PKCS1_V1_5_SHA_256';
    public const RSASSA_PKCS1_V1_5_SHA_384 = 'RSASSA_PKCS1_V1_5_SHA_384';
    public const RSASSA_PKCS1_V1_5_SHA_512 = 'RSASSA_PKCS1_V1_5_SHA_512';
    public const RSASSA_PSS_SHA_256 = 'RSASSA_PSS_SHA_256';
    public const RSASSA_PSS_SHA_384 = 'RSASSA_PSS_SHA_384';
    public const RSASSA_PSS_SHA_512 = 'RSASSA_PSS_SHA_512';
    public const SM2DSA = 'SM2DSA';

    public static function exists(string $value): bool
    {
        return isset([
            self::ECDSA_SHA_256 => true,
            self::ECDSA_SHA_384 => true,
            self::ECDSA_SHA_512 => true,
            self::ML_DSA_SHAKE_256 => true,
            self::RSASSA_PKCS1_V1_5_SHA_256 => true,
            self::RSASSA_PKCS1_V1_5_SHA_384 => true,
            self::RSASSA_PKCS1_V1_5_SHA_512 => true,
            self::RSASSA_PSS_SHA_256 => true,
            self::RSASSA_PSS_SHA_384 => true,
            self::RSASSA_PSS_SHA_512 => true,
            self::SM2DSA => true,
        ][$value]);
    }
}
