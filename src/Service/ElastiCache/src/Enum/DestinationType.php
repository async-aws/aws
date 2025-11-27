<?php

namespace AsyncAws\ElastiCache\Enum;

final class DestinationType
{
    public const CLOUDWATCH_LOGS = 'cloudwatch-logs';
    public const KINESIS_FIREHOSE = 'kinesis-firehose';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CLOUDWATCH_LOGS => true,
            self::KINESIS_FIREHOSE => true,
        ][$value]);
    }
}
