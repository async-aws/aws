<?php

namespace AsyncAws\Kms\Enum;

/**
 * The source of the key material for the KMS key. You cannot change the origin after you create the KMS key. The
 * default is `AWS_KMS`, which means that KMS creates the key material.
 * To create a KMS key with no key material (for imported key material), set this value to `EXTERNAL`. For more
 * information about importing key material into KMS, see Importing Key Material in the *Key Management Service
 * Developer Guide*. The `EXTERNAL` origin value is valid only for symmetric KMS keys.
 * To create a KMS key in an CloudHSM key store and create its key material in the associated CloudHSM cluster, set this
 * value to `AWS_CLOUDHSM`. You must also use the `CustomKeyStoreId` parameter to identify the CloudHSM key store. The
 * `KeySpec` value must be `SYMMETRIC_DEFAULT`.
 * To create a KMS key in an external key store, set this value to `EXTERNAL_KEY_STORE`. You must also use the
 * `CustomKeyStoreId` parameter to identify the external key store and the `XksKeyId` parameter to identify the
 * associated external key. The `KeySpec` value must be `SYMMETRIC_DEFAULT`.
 *
 * @see https://docs.aws.amazon.com/kms/latest/developerguide/importing-keys-create-cmk.html
 * @see https://docs.aws.amazon.com/kms/latest/developerguide/importing-keys.html
 * @see https://docs.aws.amazon.com/kms/latest/developerguide/create-cmk-keystore.html
 * @see https://docs.aws.amazon.com/kms/latest/developerguide/create-xks-keys.html
 */
final class OriginType
{
    public const AWS_CLOUDHSM = 'AWS_CLOUDHSM';
    public const AWS_KMS = 'AWS_KMS';
    public const EXTERNAL = 'EXTERNAL';
    public const EXTERNAL_KEY_STORE = 'EXTERNAL_KEY_STORE';

    public static function exists(string $value): bool
    {
        return isset([
            self::AWS_CLOUDHSM => true,
            self::AWS_KMS => true,
            self::EXTERNAL => true,
            self::EXTERNAL_KEY_STORE => true,
        ][$value]);
    }
}
