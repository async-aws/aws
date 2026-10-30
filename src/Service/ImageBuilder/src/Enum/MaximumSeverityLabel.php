<?php

namespace AsyncAws\ImageBuilder\Enum;

final class MaximumSeverityLabel
{
    public const HIGH = 'HIGH';
    public const INFORMATIONAL = 'INFORMATIONAL';
    public const LOW = 'LOW';
    public const MEDIUM = 'MEDIUM';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::HIGH => true,
            self::INFORMATIONAL => true,
            self::LOW => true,
            self::MEDIUM => true,
        ][$value]);
    }
}
