<?php

namespace AsyncAws\Lambda\Enum;

/**
 * The storage mode for a function's deployment package.
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
