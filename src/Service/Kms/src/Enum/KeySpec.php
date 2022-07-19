<?php

namespace AsyncAws\Kms\Enum;

/**
 * Specifies the type of KMS key to create. The default value, `SYMMETRIC_DEFAULT`, creates a KMS key with a 256-bit
 * AES-GCM key that is used for encryption and decryption, except in China Regions, where it creates a 128-bit symmetric
 * key that uses SM4 encryption. For help choosing a key spec for your KMS key, see Choosing a KMS key type in the **Key
 * Management Service Developer Guide**.
 * The `KeySpec` determines whether the KMS key contains a symmetric key or an asymmetric key pair. It also determines
 * the cryptographic algorithms that the KMS key supports. You can't change the `KeySpec` after the KMS key is created.
 * To further restrict the algorithms that can be used with the KMS key, use a condition key in its key policy or IAM
 * policy. For more information, see kms:EncryptionAlgorithm, kms:MacAlgorithm or kms:Signing Algorithm in the **Key
 * Management Service Developer Guide**.
 *
 * ! Amazon Web Services services that are integrated with KMS use symmetric encryption KMS keys to protect your data.
 * ! These services do not support asymmetric KMS keys or HMAC KMS keys.
 *
 * KMS supports the following key specs for KMS keys:
 *
 * - Symmetric encryption key (default)
 *
 *   - `SYMMETRIC_DEFAULT`
 *
 * - HMAC keys (symmetric)
 *
 *   - `HMAC_224`
 *   - `HMAC_256`
 *   - `HMAC_384`
 *   - `HMAC_512`
 *
 * - Asymmetric RSA key pairs
 *
 *   - `RSA_2048`
 *   - `RSA_3072`
 *   - `RSA_4096`
 *
 * - Asymmetric NIST-recommended elliptic curve key pairs
 *
 *   - `ECC_NIST_P256` (secp256r1)
 *   - `ECC_NIST_P384` (secp384r1)
 *   - `ECC_NIST_P521` (secp521r1)
 *
 * - Other asymmetric elliptic curve key pairs
 *
 *   - `ECC_SECG_P256K1` (secp256k1), commonly used for cryptocurrencies.
 *
 * - SM2 key pairs (China Regions only)
 *
 *   - `SM2`
 *
 * @see https://docs.aws.amazon.com/kms/latest/developerguide/key-types.html#symm-asymm-choose
 * @see https://docs.aws.amazon.com/kms/latest/developerguide/policy-conditions.html#conditions-kms-encryption-algorithm
 * @see https://docs.aws.amazon.com/kms/latest/developerguide/policy-conditions.html#conditions-kms-mac-algorithm
 * @see https://docs.aws.amazon.com/kms/latest/developerguide/policy-conditions.html#conditions-kms-signing-algorithm
 * @see http://aws.amazon.com/kms/features/#AWS_Service_Integration
 */
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
            self::RSA_2048 => true,
            self::RSA_3072 => true,
            self::RSA_4096 => true,
            self::SM2 => true,
            self::SYMMETRIC_DEFAULT => true,
        ][$value]);
    }
}
