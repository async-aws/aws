<?php

namespace AsyncAws\Route53\Enum;

final class ChangeStatus
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const INSYNC = 'INSYNC';
    public const PENDING = 'PENDING';

    public static function exists(string $value): bool
    {
        return isset([
            self::INSYNC => true,
            self::PENDING => true,
        ][$value]);
    }
}
