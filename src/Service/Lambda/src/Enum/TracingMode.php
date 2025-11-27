<?php

namespace AsyncAws\Lambda\Enum;

final class TracingMode
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const ACTIVE = 'Active';
    public const PASS_THROUGH = 'PassThrough';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ACTIVE => true,
            self::PASS_THROUGH => true,
        ][$value]);
    }
}
