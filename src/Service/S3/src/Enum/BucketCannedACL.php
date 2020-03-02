<?php

namespace AsyncAws\S3\Enum;

class BucketCannedACL
{
    public const AUTHENTICATED_READ = 'authenticated-read';
    public const PRIVATE = 'private';
    public const PUBLIC_READ = 'public-read';
    public const PUBLIC_READ_WRITE = 'public-read-write';

    public static function exists(string $value): bool
    {
        $values = [
            self::AUTHENTICATED_READ => true,
            self::PRIVATE => true,
            self::PUBLIC_READ => true,
            self::PUBLIC_READ_WRITE => true,
        ];

        return isset($values[$value]);
    }
}
