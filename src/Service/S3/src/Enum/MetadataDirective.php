<?php

namespace AsyncAws\S3\Enum;

class MetadataDirective
{
    public const COPY = 'COPY';
    public const REPLACE = 'REPLACE';

    public static function exists(string $value): bool
    {
        $values = [
            self::COPY => true,
            self::REPLACE => true,
        ];

        return isset($values[$value]);
    }
}
