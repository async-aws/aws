<?php

namespace AsyncAws\Athena\Enum;

final class S3AclOption
{
    public const BUCKET_OWNER_FULL_CONTROL = 'BUCKET_OWNER_FULL_CONTROL';

    public static function exists(string $value): bool
    {
        return isset([
            self::BUCKET_OWNER_FULL_CONTROL => true,
        ][$value]);
    }
}
