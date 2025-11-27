<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Choose an Amazon S3 canned ACL for MediaConvert to apply to this output.
 */
final class S3ObjectCannedAcl
{
    public const AUTHENTICATED_READ = 'AUTHENTICATED_READ';
    public const BUCKET_OWNER_FULL_CONTROL = 'BUCKET_OWNER_FULL_CONTROL';
    public const BUCKET_OWNER_READ = 'BUCKET_OWNER_READ';
    public const PUBLIC_READ = 'PUBLIC_READ';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AUTHENTICATED_READ => true,
            self::BUCKET_OWNER_FULL_CONTROL => true,
            self::BUCKET_OWNER_READ => true,
            self::PUBLIC_READ => true,
        ][$value]);
    }
}
