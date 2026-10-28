<?php

namespace AsyncAws\Ec2\Enum;

final class SeverityLabel
{
    public const CRITICAL = 'critical';
    public const HIGH = 'high';
    public const INFORMATIONAL = 'informational';
    public const LOW = 'low';
    public const MEDIUM = 'medium';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CRITICAL => true,
            self::HIGH => true,
            self::INFORMATIONAL => true,
            self::LOW => true,
            self::MEDIUM => true,
        ][$value]);
    }
}
