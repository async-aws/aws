<?php

namespace AsyncAws\DynamoDb\Enum;

/**
 * The data type for the attribute, where:.
 *
 * - `S` - the attribute is of type String
 * - `N` - the attribute is of type Number
 * - `B` - the attribute is of type Binary
 */
final class ScalarAttributeType
{
    public const B = 'B';
    public const N = 'N';
    public const S = 'S';

    public static function exists(string $value): bool
    {
        return isset([
            self::B => true,
            self::N => true,
            self::S => true,
        ][$value]);
    }
}
