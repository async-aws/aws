<?php

namespace AsyncAws\S3\Enum;

/**
 * Indicates the algorithm you want Amazon S3 to use to create the checksum for the object. For more information, see
 * Checking object integrity in the *Amazon S3 User Guide*.
 *
 * @see https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
 */
final class ChecksumAlgorithm
{
    public const CRC32 = 'CRC32';
    public const CRC32C = 'CRC32C';
    public const SHA1 = 'SHA1';
    public const SHA256 = 'SHA256';

    public static function exists(string $value): bool
    {
        return isset([
            self::CRC32 => true,
            self::CRC32C => true,
            self::SHA1 => true,
            self::SHA256 => true,
        ][$value]);
    }
}
