<?php

namespace AsyncAws\DynamoDb\Enum;

final class ReturnConsumedCapacity
{
    public const INDEXES = 'INDEXES';
    public const NONE = 'NONE';
    public const TOTAL = 'TOTAL';

    public static function exists(string $value): bool
    {
        return isset([
            self::INDEXES => true,
            self::NONE => true,
            self::TOTAL => true,
        ][$value]);
    }
}
