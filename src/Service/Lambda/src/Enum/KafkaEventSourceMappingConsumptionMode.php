<?php

namespace AsyncAws\Lambda\Enum;

final class KafkaEventSourceMappingConsumptionMode
{
    public const QUEUE = 'Queue';
    public const STREAM = 'Stream';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::QUEUE => true,
            self::STREAM => true,
        ][$value]);
    }
}
