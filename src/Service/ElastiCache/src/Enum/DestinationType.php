<?php

namespace AsyncAws\ElastiCache\Enum;

final class DestinationType
{
    public const CLOUDWATCH_LOGS = 'cloudwatch-logs';
    public const KINESIS_FIREHOSE = 'kinesis-firehose';

    public static function exists(string $value): bool
    {
        return isset([
            self::CLOUDWATCH_LOGS => true,
            self::KINESIS_FIREHOSE => true,
        ][$value]);
    }
}
