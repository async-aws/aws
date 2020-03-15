<?php

namespace AsyncAws\DynamoDb\Enum;

class Select
{
    public const ALL_ATTRIBUTES = 'ALL_ATTRIBUTES';
    public const ALL_PROJECTED_ATTRIBUTES = 'ALL_PROJECTED_ATTRIBUTES';
    public const COUNT = 'COUNT';
    public const SPECIFIC_ATTRIBUTES = 'SPECIFIC_ATTRIBUTES';

    public static function exists(string $value): bool
    {
        return isset([
            self::ALL_ATTRIBUTES => true,
            self::ALL_PROJECTED_ATTRIBUTES => true,
            self::COUNT => true,
            self::SPECIFIC_ATTRIBUTES => true,
        ][$value]);
    }
}
