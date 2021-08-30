<?php

namespace AsyncAws\ElastiCache\Enum;

/**
 * Refers to slow-log.
 *
 * @see https://redis.io/commands/slowlog
 */
final class LogType
{
    public const SLOW_LOG = 'slow-log';

    public static function exists(string $value): bool
    {
        return isset([
            self::SLOW_LOG => true,
        ][$value]);
    }
}
