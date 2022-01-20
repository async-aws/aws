<?php

namespace AsyncAws\Kms\Enum;

/**
 * The source of the key material for the KMS key. You cannot change the origin after you create the KMS key. The
 * default is `AWS_KMS`, which means that KMS creates the key material.
 * To create a KMS key with no key material (for imported key material), set the value to `EXTERNAL`. For more
 * information about importing key material into KMS, see Importing Key Material in the *Key Management Service
 * Developer Guide*. This value is valid only for symmetric KMS keys.
 * To create a KMS key in an KMS custom key store and create its key material in the associated CloudHSM cluster, set
 * this value to `AWS_CLOUDHSM`. You must also use the `CustomKeyStoreId` parameter to identify the custom key store.
 * This value is valid only for symmetric KMS keys.
 *
 * @see https://docs.aws.amazon.com/kms/latest/developerguide/importing-keys.html
 * @see https://docs.aws.amazon.com/kms/latest/developerguide/custom-key-store-overview.html
 */
final class OriginType
{
    public const AWS_CLOUDHSM = 'AWS_CLOUDHSM';
    public const AWS_KMS = 'AWS_KMS';
    public const EXTERNAL = 'EXTERNAL';

    public static function exists(string $value): bool
    {
        return isset([
            self::AWS_CLOUDHSM => true,
            self::AWS_KMS => true,
            self::EXTERNAL => true,
        ][$value]);
    }
}
