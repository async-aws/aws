<?php

namespace AsyncAws\Rekognition\Enum;

final class Attribute
{
    public const DEFAULT = 'DEFAULT';
    public const ALL = 'ALL';

    public static function exists(string $value): bool
    {
        return isset([
            self::DEFAULT => true,
            self::ALL => true,
        ][$value]);
    }
}
