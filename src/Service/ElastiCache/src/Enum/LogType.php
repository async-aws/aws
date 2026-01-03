<?php

namespace AsyncAws\ElastiCache\Enum;

final class LogType
{
    public const ENGINE_LOG = 'engine-log';
    public const SLOW_LOG = 'slow-log';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ENGINE_LOG => true,
            self::SLOW_LOG => true,
        ][$value]);
    }
}
