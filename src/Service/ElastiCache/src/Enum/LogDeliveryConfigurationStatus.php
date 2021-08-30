<?php

namespace AsyncAws\ElastiCache\Enum;

/**
 * Returns the log delivery configuration status. Values are one of `enabling` | `disabling` | `modifying` | `active` |
 * `error`.
 */
final class LogDeliveryConfigurationStatus
{
    public const ACTIVE = 'active';
    public const DISABLING = 'disabling';
    public const ENABLING = 'enabling';
    public const ERROR = 'error';
    public const MODIFYING = 'modifying';

    public static function exists(string $value): bool
    {
        return isset([
            self::ACTIVE => true,
            self::DISABLING => true,
            self::ENABLING => true,
            self::ERROR => true,
            self::MODIFYING => true,
        ][$value]);
    }
}
