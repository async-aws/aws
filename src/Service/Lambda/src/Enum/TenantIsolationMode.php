<?php

namespace AsyncAws\Lambda\Enum;

final class TenantIsolationMode
{
    public const PER_TENANT = 'PER_TENANT';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::PER_TENANT => true,
        ][$value]);
    }
}
