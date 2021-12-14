<?php

namespace AsyncAws\Kinesis\Enum;

/**
 * Specifies the capacity mode to which you want to set your data stream. Currently, in Kinesis Data Streams, you can
 * choose between an **on-demand** capacity mode and a **provisioned** capacity mode for your data streams.
 */
final class StreamMode
{
    public const ON_DEMAND = 'ON_DEMAND';
    public const PROVISIONED = 'PROVISIONED';

    public static function exists(string $value): bool
    {
        return isset([
            self::ON_DEMAND => true,
            self::PROVISIONED => true,
        ][$value]);
    }
}
