<?php

namespace AsyncAws\S3\Enum;

/**
 * If present, indicates that the requester was successfully charged for the request. For more information, see Using
 * Requester Pays buckets for storage transfers and usage [^1] in the *Amazon Simple Storage Service user guide*.
 *
 * > This functionality is not supported for directory buckets.
 *
 * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/RequesterPaysBuckets.html
 */
final class RequestCharged
{
    public const REQUESTER = 'requester';

    public static function exists(string $value): bool
    {
        return isset([
            self::REQUESTER => true,
        ][$value]);
    }
}
