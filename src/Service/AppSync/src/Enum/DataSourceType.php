<?php

namespace AsyncAws\AppSync\Enum;

final class DataSourceType
{
    public const AMAZON_BEDROCK_RUNTIME = 'AMAZON_BEDROCK_RUNTIME';
    public const AMAZON_DYNAMODB = 'AMAZON_DYNAMODB';
    public const AMAZON_ELASTICSEARCH = 'AMAZON_ELASTICSEARCH';
    public const AMAZON_EVENTBRIDGE = 'AMAZON_EVENTBRIDGE';
    public const AMAZON_OPENSEARCH_SERVICE = 'AMAZON_OPENSEARCH_SERVICE';
    public const AWS_LAMBDA = 'AWS_LAMBDA';
    public const HTTP = 'HTTP';
    public const NONE = 'NONE';
    public const RELATIONAL_DATABASE = 'RELATIONAL_DATABASE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AMAZON_BEDROCK_RUNTIME => true,
            self::AMAZON_DYNAMODB => true,
            self::AMAZON_ELASTICSEARCH => true,
            self::AMAZON_EVENTBRIDGE => true,
            self::AMAZON_OPENSEARCH_SERVICE => true,
            self::AWS_LAMBDA => true,
            self::HTTP => true,
            self::NONE => true,
            self::RELATIONAL_DATABASE => true,
        ][$value]);
    }
}
