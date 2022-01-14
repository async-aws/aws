<?php

namespace AsyncAws\ElastiCache\Enum;

/**
 * Refers to slow-log or engine-log..
 *
 * @see https://redis.io/commands/slowlog
 */
final class LogType
{
    public const ENGINE_LOG = 'engine-log';
    public const SLOW_LOG = 'slow-log';

    public static function exists(string $value): bool
    {
        return isset([
            self::ENGINE_LOG => true,
            self::SLOW_LOG => true,
        ][$value]);
    }
}
