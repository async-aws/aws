<?php

namespace AsyncAws\Lambda\Enum;

/**
 * The tracing mode.
 */
final class TracingMode
{
    public const ACTIVE = 'Active';
    public const PASS_THROUGH = 'PassThrough';

    public static function exists(string $value): bool
    {
        return isset([
            self::ACTIVE => true,
            self::PASS_THROUGH => true,
        ][$value]);
    }
}
