<?php

namespace AsyncAws\CodeBuild\Enum;

/**
 * The type of cache used by the build project. Valid values include:.
 *
 * - `NO_CACHE`: The build project does not use any cache.
 * - `S3`: The build project reads and writes from and to S3.
 * - `LOCAL`: The build project stores a cache locally on a build host that is only available to that build host.
 */
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
