<?php

namespace AsyncAws\S3\Enum;

class EncodingType
{
    public const URL = 'url';

    public static function exists(string $value): bool
    {
        $values = [
            self::URL => true,
        ];

        return isset($values[$value]);
    }
}
