<?php

namespace AsyncAws\DynamoDb\Enum;

final class ReturnValue
{
    public const ALL_NEW = 'ALL_NEW';
    public const ALL_OLD = 'ALL_OLD';
    public const NONE = 'NONE';
    public const UPDATED_NEW = 'UPDATED_NEW';
    public const UPDATED_OLD = 'UPDATED_OLD';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ALL_NEW => true,
            self::ALL_OLD => true,
            self::NONE => true,
            self::UPDATED_NEW => true,
            self::UPDATED_OLD => true,
        ][$value]);
    }
}
