<?php

namespace AsyncAws\ImageBuilder\Enum;

final class ImageScanStatus
{
    public const ABANDONED = 'ABANDONED';
    public const COLLECTING = 'COLLECTING';
    public const COMPLETED = 'COMPLETED';
    public const FAILED = 'FAILED';
    public const PENDING = 'PENDING';
    public const SCANNING = 'SCANNING';
    public const TIMED_OUT = 'TIMED_OUT';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ABANDONED => true,
            self::COLLECTING => true,
            self::COMPLETED => true,
            self::FAILED => true,
            self::PENDING => true,
            self::SCANNING => true,
            self::TIMED_OUT => true,
        ][$value]);
    }
}
