<?php

namespace AsyncAws\Rekognition\Enum;

final class QualityFilter
{
    public const AUTO = 'AUTO';
    public const HIGH = 'HIGH';
    public const LOW = 'LOW';
    public const MEDIUM = 'MEDIUM';
    public const NONE = 'NONE';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::HIGH => true,
            self::LOW => true,
            self::MEDIUM => true,
            self::NONE => true,
        ][$value]);
    }
}
