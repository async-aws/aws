<?php

namespace AsyncAws\S3\Enum;

/**
 * Indicates the algorithm used to create the checksum for the object when using the SDK. This header will not provide
 * any additional functionality if not using the SDK. When sending this header, there must be a corresponding
 * `x-amz-checksum` or `x-amz-trailer` header sent. Otherwise, Amazon S3 fails the request with the HTTP status code
 * `400 Bad Request`. For more information, see Checking object integrity in the *Amazon S3 User Guide*.
 * If you provide an individual checksum, Amazon S3 ignores any provided `ChecksumAlgorithm` parameter.
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
