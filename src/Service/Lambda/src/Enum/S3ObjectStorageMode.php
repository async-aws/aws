<?php

namespace AsyncAws\Lambda\Enum;

/**
 * The method Lambda uses to store a function's deployment package — either by copying the package into Lambda-managed
 * storage (`COPY`) or by referencing it directly from the source Amazon S3 bucket (`REFERENCE`).
 */
final class S3ObjectStorageMode
{
    public const COPY = 'COPY';
    public const REFERENCE = 'REFERENCE';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::COPY => true,
            self::REFERENCE => true,
        ][$value]);
    }
}
