<?php

namespace AsyncAws\CodeBuild\Enum;

final class CacheType
{
    public const LOCAL = 'LOCAL';
    public const NO_CACHE = 'NO_CACHE';
    public const S3 = 'S3';

    public static function exists(string $value): bool
    {
        return isset([
            self::LOCAL => true,
            self::NO_CACHE => true,
            self::S3 => true,
        ][$value]);
    }
}
