<?php

namespace AsyncAws\ElastiCache\Enum;

/**
 * The auth token status.
 */
final class AuthTokenUpdateStatus
{
    public const ROTATING = 'ROTATING';
    public const SETTING = 'SETTING';

    public static function exists(string $value): bool
    {
        return isset([
            self::ROTATING => true,
            self::SETTING => true,
        ][$value]);
    }
}
