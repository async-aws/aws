<?php

namespace AsyncAws\Kinesis\Enum;

final class ShardFilterType
{
    public const AFTER_SHARD_ID = 'AFTER_SHARD_ID';
    public const AT_LATEST = 'AT_LATEST';
    public const AT_TIMESTAMP = 'AT_TIMESTAMP';
    public const AT_TRIM_HORIZON = 'AT_TRIM_HORIZON';
    public const FROM_TIMESTAMP = 'FROM_TIMESTAMP';
    public const FROM_TRIM_HORIZON = 'FROM_TRIM_HORIZON';

    /**
     * @psalm-assert-if-true self::* $value
     */
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
