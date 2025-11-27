<?php

namespace AsyncAws\Athena\Enum;

final class S3AclOption
{
    public const BUCKET_OWNER_FULL_CONTROL = 'BUCKET_OWNER_FULL_CONTROL';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::BUCKET_OWNER_FULL_CONTROL => true,
        ][$value]);
    }
}
