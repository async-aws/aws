<?php

namespace AsyncAws\S3\Enum;

final class AnnotationDirective
{
    public const COPY = 'COPY';
    public const EXCLUDE = 'EXCLUDE';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::COPY => true,
            self::EXCLUDE => true,
        ][$value]);
    }
}
