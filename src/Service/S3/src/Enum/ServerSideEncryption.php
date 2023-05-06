<?php

namespace AsyncAws\S3\Enum;

/**
 * The server-side encryption algorithm used when storing this object in Amazon S3 (for example, AES256, `aws:kms`).
 */
final class ServerSideEncryption
{
    public const AES256 = 'AES256';
    public const AWS_KMS = 'aws:kms';

    public static function exists(string $value): bool
    {
        return isset([
            self::AES256 => true,
            self::AWS_KMS => true,
        ][$value]);
    }
}
