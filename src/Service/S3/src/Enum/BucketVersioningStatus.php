<?php

namespace AsyncAws\S3\Enum;

/**
 * The versioning state of the bucket.
 */
final class BucketVersioningStatus
{
    public const ENABLED = 'Enabled';
    public const SUSPENDED = 'Suspended';

    public static function exists(string $value): bool
    {
        return isset([
            self::ENABLED => true,
            self::SUSPENDED => true,
        ][$value]);
    }
}
