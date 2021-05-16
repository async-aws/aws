<?php

namespace AsyncAws\Kinesis\Enum;

/**
 * Determines how the shard iterator is used to start reading data records from the shard.
 * The following are the valid Amazon Kinesis shard iterator types:.
 *
 * - AT_SEQUENCE_NUMBER - Start reading from the position denoted by a specific sequence number, provided in the value
 *   `StartingSequenceNumber`.
 * - AFTER_SEQUENCE_NUMBER - Start reading right after the position denoted by a specific sequence number, provided in
 *   the value `StartingSequenceNumber`.
 * - AT_TIMESTAMP - Start reading from the position denoted by a specific time stamp, provided in the value `Timestamp`.
 * - TRIM_HORIZON - Start reading at the last untrimmed record in the shard in the system, which is the oldest data
 *   record in the shard.
 * - LATEST - Start reading just after the most recent record in the shard, so that you always read the most recent data
 *   in the shard.
 */
final class ShardIteratorType
{
    public const AFTER_SEQUENCE_NUMBER = 'AFTER_SEQUENCE_NUMBER';
    public const AT_SEQUENCE_NUMBER = 'AT_SEQUENCE_NUMBER';
    public const AT_TIMESTAMP = 'AT_TIMESTAMP';
    public const LATEST = 'LATEST';
    public const TRIM_HORIZON = 'TRIM_HORIZON';

    public static function exists(string $value): bool
    {
        return isset([
            self::AFTER_SEQUENCE_NUMBER => true,
            self::AT_SEQUENCE_NUMBER => true,
            self::AT_TIMESTAMP => true,
            self::LATEST => true,
            self::TRIM_HORIZON => true,
        ][$value]);
    }
}
