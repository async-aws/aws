<?php

namespace AsyncAws\DynamoDb\Enum;

final class KeyType
{
    public const HASH = 'HASH';
    public const RANGE = 'RANGE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::HASH => true,
            self::RANGE => true,
        ][$value]);
    }
}
