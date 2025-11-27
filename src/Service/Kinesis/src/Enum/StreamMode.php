<?php

namespace AsyncAws\Kinesis\Enum;

final class StreamMode
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const ON_DEMAND = 'ON_DEMAND';
    public const PROVISIONED = 'PROVISIONED';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ON_DEMAND => true,
            self::PROVISIONED => true,
        ][$value]);
    }
}
