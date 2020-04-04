<?php

namespace AsyncAws\DynamoDb\Enum;

final class ProjectionType
{
    public const ALL = 'ALL';
    public const INCLUDE = 'INCLUDE';
    public const KEYS_ONLY = 'KEYS_ONLY';

    public static function exists(string $value): bool
    {
        return isset([
            self::ALL => true,
            self::INCLUDE => true,
            self::KEYS_ONLY => true,
        ][$value]);
    }
}
