<?php

namespace AsyncAws\DynamoDb\Enum;

final class ReturnValuesOnConditionCheckFailure
{
    public const ALL_OLD = 'ALL_OLD';
    public const NONE = 'NONE';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ALL_OLD => true,
            self::NONE => true,
        ][$value]);
    }
}
