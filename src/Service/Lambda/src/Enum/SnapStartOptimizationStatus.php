<?php

namespace AsyncAws\Lambda\Enum;

final class SnapStartOptimizationStatus
{
    public const OFF = 'Off';
    public const ON = 'On';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::OFF => true,
            self::ON => true,
        ][$value]);
    }
}
