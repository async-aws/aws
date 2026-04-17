<?php

namespace AsyncAws\Lambda\Enum;

final class EventSourceMappingMetric
{
    public const ERROR_COUNT = 'ErrorCount';
    public const EVENT_COUNT = 'EventCount';
    public const KAFKA_METRICS = 'KafkaMetrics';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ERROR_COUNT => true,
            self::EVENT_COUNT => true,
            self::KAFKA_METRICS => true,
        ][$value]);
    }
}
