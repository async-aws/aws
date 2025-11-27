<?php

namespace AsyncAws\DynamoDb\Enum;

final class ScalarAttributeType
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
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
