<?php

namespace AsyncAws\CloudWatchLogs\Enum;

final class LogGroupClass
{
    public const DELIVERY = 'DELIVERY';
    public const INFREQUENT_ACCESS = 'INFREQUENT_ACCESS';
    public const STANDARD = 'STANDARD';

    public static function exists(string $value): bool
    {
        return isset([
            self::DELIVERY => true,
            self::INFREQUENT_ACCESS => true,
            self::STANDARD => true,
        ][$value]);
    }
}
