<?php

namespace AsyncAws\S3\Enum;

final class MetadataDirective
{
    public const COPY = 'COPY';
    public const REPLACE = 'REPLACE';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::COPY => true,
            self::REPLACE => true,
        ][$value]);
    }
}
