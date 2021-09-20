<?php

namespace AsyncAws\AppSync\Enum;

/**
 * The new data source type.
 */
final class DataSourceType
{
    public const AMAZON_DYNAMODB = 'AMAZON_DYNAMODB';
    public const AMAZON_ELASTICSEARCH = 'AMAZON_ELASTICSEARCH';
    public const AWS_LAMBDA = 'AWS_LAMBDA';
    public const HTTP = 'HTTP';
    public const NONE = 'NONE';
    public const RELATIONAL_DATABASE = 'RELATIONAL_DATABASE';

    public static function exists(string $value): bool
    {
        return isset([
            self::AMAZON_DYNAMODB => true,
            self::AMAZON_ELASTICSEARCH => true,
            self::AWS_LAMBDA => true,
            self::HTTP => true,
            self::NONE => true,
            self::RELATIONAL_DATABASE => true,
        ][$value]);
    }
}
