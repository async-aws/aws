<?php

namespace AsyncAws\CloudWatchLogs\Enum;

final class LogGroupClass
{
    public const DELIVERY = 'DELIVERY';
    public const INFREQUENT_ACCESS = 'INFREQUENT_ACCESS';
    public const STANDARD = 'STANDARD';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DELIVERY => true,
            self::INFREQUENT_ACCESS => true,
            self::STANDARD => true,
        ][$value]);
    }
}
