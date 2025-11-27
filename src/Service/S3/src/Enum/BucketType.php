<?php

namespace AsyncAws\S3\Enum;

final class BucketType
{
    public const DIRECTORY = 'Directory';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DIRECTORY => true,
        ][$value]);
    }
}
