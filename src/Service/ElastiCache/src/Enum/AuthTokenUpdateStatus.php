<?php

namespace AsyncAws\ElastiCache\Enum;

final class AuthTokenUpdateStatus
{
    public const ROTATING = 'ROTATING';
    public const SETTING = 'SETTING';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ROTATING => true,
            self::SETTING => true,
        ][$value]);
    }
}
