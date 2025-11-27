<?php

namespace AsyncAws\DynamoDb\Enum;

final class ReturnItemCollectionMetrics
{
    public const NONE = 'NONE';
    public const SIZE = 'SIZE';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::NONE => true,
            self::SIZE => true,
        ][$value]);
    }
}
