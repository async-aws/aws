<?php

namespace AsyncAws\DynamoDb\Enum;

/**
 * This is a legacy parameter. Use `ConditionExpression` instead. For more information, see ConditionalOperator in the
 * *Amazon DynamoDB Developer Guide*.
 *
 * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.ConditionalOperator.html
 */
final class ConditionalOperator
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
