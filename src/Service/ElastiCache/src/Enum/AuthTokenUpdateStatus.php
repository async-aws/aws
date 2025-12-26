<?php

namespace AsyncAws\ElastiCache\Enum;

final class AuthTokenUpdateStatus
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const ROTATING = 'ROTATING';
    public const SETTING = 'SETTING';

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
