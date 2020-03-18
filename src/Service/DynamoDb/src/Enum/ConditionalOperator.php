<?php

namespace AsyncAws\DynamoDb\Enum;

class ConditionalOperator
{
    public const AND = 'AND';
    public const OR = 'OR';

    public static function exists(string $value): bool
    {
        return isset([
            self::AND => true,
            self::OR => true,
        ][$value]);
    }
}
