<?php

namespace AsyncAws\Route53\Enum;

final class AcceleratedRecoveryStatus
{
    public const DISABLED = 'DISABLED';
    public const DISABLE_FAILED = 'DISABLE_FAILED';
    public const DISABLING = 'DISABLING';
    public const DISABLING_HOSTED_ZONE_LOCKED = 'DISABLING_HOSTED_ZONE_LOCKED';
    public const ENABLED = 'ENABLED';
    public const ENABLE_FAILED = 'ENABLE_FAILED';
    public const ENABLING = 'ENABLING';
    public const ENABLING_HOSTED_ZONE_LOCKED = 'ENABLING_HOSTED_ZONE_LOCKED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::DISABLE_FAILED => true,
            self::DISABLING => true,
            self::DISABLING_HOSTED_ZONE_LOCKED => true,
            self::ENABLED => true,
            self::ENABLE_FAILED => true,
            self::ENABLING => true,
            self::ENABLING_HOSTED_ZONE_LOCKED => true,
        ][$value]);
    }
}
