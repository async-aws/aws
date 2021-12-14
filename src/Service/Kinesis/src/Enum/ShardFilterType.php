<?php

namespace AsyncAws\Kinesis\Enum;

/**
 * The shard type specified in the `ShardFilter` parameter. This is a required property of the `ShardFilter` parameter.
 * You can specify the following valid values:.
 *
 * - `AFTER_SHARD_ID` - the response includes all the shards, starting with the shard whose ID immediately follows the
 *   `ShardId` that you provided.
 * - `AT_TRIM_HORIZON` - the response includes all the shards that were open at `TRIM_HORIZON`.
 * - `FROM_TRIM_HORIZON` - (default), the response includes all the shards within the retention period of the data
 *   stream (trim to tip).
 * - `AT_LATEST` - the response includes only the currently open shards of the data stream.
 * - `AT_TIMESTAMP` - the response includes all shards whose start timestamp is less than or equal to the given
 *   timestamp and end timestamp is greater than or equal to the given timestamp or still open.
 * - `FROM_TIMESTAMP` - the response incldues all closed shards whose end timestamp is greater than or equal to the
 *   given timestamp and also all open shards. Corrected to `TRIM_HORIZON` of the data stream if `FROM_TIMESTAMP` is
 *   less than the `TRIM_HORIZON` value.
 */
final class ShardFilterType
{
    public const AFTER_SHARD_ID = 'AFTER_SHARD_ID';
    public const AT_LATEST = 'AT_LATEST';
    public const AT_TIMESTAMP = 'AT_TIMESTAMP';
    public const AT_TRIM_HORIZON = 'AT_TRIM_HORIZON';
    public const FROM_TIMESTAMP = 'FROM_TIMESTAMP';
    public const FROM_TRIM_HORIZON = 'FROM_TRIM_HORIZON';

    public static function exists(string $value): bool
    {
        return isset([
            self::AFTER_SHARD_ID => true,
            self::AT_LATEST => true,
            self::AT_TIMESTAMP => true,
            self::AT_TRIM_HORIZON => true,
            self::FROM_TIMESTAMP => true,
            self::FROM_TRIM_HORIZON => true,
        ][$value]);
    }
}
