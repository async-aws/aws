<?php

namespace AsyncAws\Rekognition\Enum;

final class Attribute
{
    public const ALL = 'ALL';
    public const DEFAULT = 'DEFAULT';

    public static function exists(string $value): bool
    {
        return isset([
            self::ALL => true,
            self::DEFAULT => true,
        ][$value]);
    }
}
