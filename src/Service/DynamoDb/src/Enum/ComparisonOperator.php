<?php

namespace AsyncAws\DynamoDb\Enum;

final class ComparisonOperator
{
    public const BEGINS_WITH = 'BEGINS_WITH';
    public const BETWEEN = 'BETWEEN';
    public const CONTAINS = 'CONTAINS';
    public const EQ = 'EQ';
    public const GE = 'GE';
    public const GT = 'GT';
    public const IN = 'IN';
    public const LE = 'LE';
    public const LT = 'LT';
    public const NE = 'NE';
    public const NOT_CONTAINS = 'NOT_CONTAINS';
    public const NOT_NULL = 'NOT_NULL';
    public const NULL = 'NULL';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::BEGINS_WITH => true,
            self::BETWEEN => true,
            self::CONTAINS => true,
            self::EQ => true,
            self::GE => true,
            self::GT => true,
            self::IN => true,
            self::LE => true,
            self::LT => true,
            self::NE => true,
            self::NOT_CONTAINS => true,
            self::NOT_NULL => true,
            self::NULL => true,
        ][$value]);
    }
}
