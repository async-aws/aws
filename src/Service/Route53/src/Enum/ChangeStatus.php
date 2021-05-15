<?php

namespace AsyncAws\Route53\Enum;

/**
 * The current state of the request. `PENDING` indicates that this request has not yet been applied to all Amazon Route
 * 53 DNS servers.
 */
final class ChangeStatus
{
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
