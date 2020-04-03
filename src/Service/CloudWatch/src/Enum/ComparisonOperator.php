<?php

namespace AsyncAws\CloudWatch\Enum;

class ComparisonOperator
{
    public const GREATER_THAN_OR_EQUAL_TO_THRESHOLD = 'GreaterThanOrEqualToThreshold';
    public const GREATER_THAN_THRESHOLD = 'GreaterThanThreshold';
    public const GREATER_THAN_UPPER_THRESHOLD = 'GreaterThanUpperThreshold';
    public const LESS_THAN_LOWER_OR_GREATER_THAN_UPPER_THRESHOLD = 'LessThanLowerOrGreaterThanUpperThreshold';
    public const LESS_THAN_LOWER_THRESHOLD = 'LessThanLowerThreshold';
    public const LESS_THAN_OR_EQUAL_TO_THRESHOLD = 'LessThanOrEqualToThreshold';
    public const LESS_THAN_THRESHOLD = 'LessThanThreshold';

    public static function exists(string $value): bool
    {
        return isset([
            self::GREATER_THAN_OR_EQUAL_TO_THRESHOLD => true,
            self::GREATER_THAN_THRESHOLD => true,
            self::GREATER_THAN_UPPER_THRESHOLD => true,
            self::LESS_THAN_LOWER_OR_GREATER_THAN_UPPER_THRESHOLD => true,
            self::LESS_THAN_LOWER_THRESHOLD => true,
            self::LESS_THAN_OR_EQUAL_TO_THRESHOLD => true,
            self::LESS_THAN_THRESHOLD => true,
        ][$value]);
    }
}
