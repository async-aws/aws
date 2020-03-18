<?php

namespace AsyncAws\S3\Enum;

class EncodingType
{
    public const URL = 'url';

    public static function exists(string $value): bool
    {
        return isset([
            self::URL => true,
        ][$value]);
    }
}
