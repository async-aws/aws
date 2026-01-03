<?php

namespace AsyncAws\Route53\Enum;

final class ChangeStatus
{
    public const INSYNC = 'INSYNC';
    public const PENDING = 'PENDING';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::INSYNC => true,
            self::PENDING => true,
        ][$value]);
    }
}
