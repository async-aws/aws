<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify how you want your data keys managed. AWS uses data keys to encrypt your content. AWS also encrypts the data
 * keys themselves, using a customer master key (CMK), and then stores the encrypted data keys alongside your encrypted
 * content. Use this setting to specify which AWS service manages the CMK. For simplest set up, choose Amazon S3. If you
 * want your master key to be managed by AWS Key Management Service (KMS), choose AWS KMS. By default, when you choose
 * AWS KMS, KMS uses the AWS managed customer master key (CMK) associated with Amazon S3 to encrypt your data keys. You
 * can optionally choose to specify a different, customer managed CMK. Do so by specifying the Amazon Resource Name
 * (ARN) of the key for the setting KMS ARN.
 */
final class S3ServerSideEncryptionType
{
    public const SERVER_SIDE_ENCRYPTION_KMS = 'SERVER_SIDE_ENCRYPTION_KMS';
    public const SERVER_SIDE_ENCRYPTION_S3 = 'SERVER_SIDE_ENCRYPTION_S3';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::SERVER_SIDE_ENCRYPTION_KMS => true,
            self::SERVER_SIDE_ENCRYPTION_S3 => true,
        ][$value]);
    }
}
