<?php

namespace AsyncAws\ElastiCache\Enum;

final class LogDeliveryConfigurationStatus
{
    public const ACTIVE = 'active';
    public const DISABLING = 'disabling';
    public const ENABLING = 'enabling';
    public const ERROR = 'error';
    public const MODIFYING = 'modifying';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
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
