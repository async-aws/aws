<?php

namespace AsyncAws\DynamoDb\Enum;

class KeyType
{
    public const HASH = 'HASH';
    public const RANGE = 'RANGE';

    public static function exists(string $value): bool
    {
        return isset([
            self::HASH => true,
            self::RANGE => true,
        ][$value]);
    }
}
