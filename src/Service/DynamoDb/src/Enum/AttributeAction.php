<?php

namespace AsyncAws\DynamoDb\Enum;

final class AttributeAction
{
    public const ADD = 'ADD';
    public const DELETE = 'DELETE';
    public const PUT = 'PUT';

    public static function exists(string $value): bool
    {
        return isset([
            self::ADD => true,
            self::DELETE => true,
            self::PUT => true,
        ][$value]);
    }
}
