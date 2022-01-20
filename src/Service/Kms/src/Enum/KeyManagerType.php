<?php

namespace AsyncAws\Kms\Enum;

/**
 * The manager of the KMS key. KMS keys in your Amazon Web Services account are either customer managed or Amazon Web
 * Services managed. For more information about the difference, see KMS keys in the *Key Management Service Developer
 * Guide*.
 *
 * @see https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#kms_keys
 */
final class KeyManagerType
{
    public const AWS = 'AWS';
    public const CUSTOMER = 'CUSTOMER';

    public static function exists(string $value): bool
    {
        return isset([
            self::AWS => true,
            self::CUSTOMER => true,
        ][$value]);
    }
}
