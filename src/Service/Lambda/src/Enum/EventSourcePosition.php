<?php

namespace AsyncAws\Lambda\Enum;

final class EventSourcePosition
{
    public const AT_TIMESTAMP = 'AT_TIMESTAMP';
    public const LATEST = 'LATEST';
    public const TRIM_HORIZON = 'TRIM_HORIZON';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AT_TIMESTAMP => true,
            self::LATEST => true,
            self::TRIM_HORIZON => true,
        ][$value]);
    }
}
