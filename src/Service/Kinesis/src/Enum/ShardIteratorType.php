<?php

namespace AsyncAws\Kinesis\Enum;

final class ShardIteratorType
{
    public const AFTER_SEQUENCE_NUMBER = 'AFTER_SEQUENCE_NUMBER';
    public const AT_SEQUENCE_NUMBER = 'AT_SEQUENCE_NUMBER';
    public const AT_TIMESTAMP = 'AT_TIMESTAMP';
    public const LATEST = 'LATEST';
    public const TRIM_HORIZON = 'TRIM_HORIZON';

    /**
     * @psalm-assert-if-true self::* $value
     */
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
