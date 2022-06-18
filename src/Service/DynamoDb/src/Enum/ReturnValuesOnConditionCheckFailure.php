<?php

namespace AsyncAws\DynamoDb\Enum;

/**
 * Use `ReturnValuesOnConditionCheckFailure` to get the item attributes if the `ConditionCheck` condition fails. For
 * `ReturnValuesOnConditionCheckFailure`, the valid values are: NONE and ALL_OLD.
 */
final class ReturnValuesOnConditionCheckFailure
{
    public const ALL_OLD = 'ALL_OLD';
    public const NONE = 'NONE';

    public static function exists(string $value): bool
    {
        return isset([
            self::ALL_OLD => true,
            self::NONE => true,
        ][$value]);
    }
}
