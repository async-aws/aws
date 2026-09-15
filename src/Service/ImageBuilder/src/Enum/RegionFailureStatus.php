<?php

namespace AsyncAws\ImageBuilder\Enum;

final class RegionFailureStatus
{
    public const CANCELLED = 'CANCELLED';
    public const FAILED = 'FAILED';
    public const TIMED_OUT = 'TIMED_OUT';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CANCELLED => true,
            self::FAILED => true,
            self::TIMED_OUT => true,
        ][$value]);
    }
}
