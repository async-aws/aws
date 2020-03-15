<?php

namespace AsyncAws\DynamoDb\Enum;

class ReturnItemCollectionMetrics
{
    public const NONE = 'NONE';
    public const SIZE = 'SIZE';

    public static function exists(string $value): bool
    {
        return isset([
            self::NONE => true,
            self::SIZE => true,
        ][$value]);
    }
}
