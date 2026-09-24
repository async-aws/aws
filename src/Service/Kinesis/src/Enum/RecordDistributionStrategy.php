<?php

namespace AsyncAws\Kinesis\Enum;

final class RecordDistributionStrategy
{
    public const AUTO = 'AUTO';
    public const USER_PARTITION_KEY = 'USER_PARTITION_KEY';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::USER_PARTITION_KEY => true,
        ][$value]);
    }
}
