<?php

namespace AsyncAws\CloudWatch\Enum;

final class StandardUnit
{
    public const BITS = 'Bits';
    public const BITS_SECOND = 'Bits/Second';
    public const BYTES = 'Bytes';
    public const BYTES_SECOND = 'Bytes/Second';
    public const COUNT = 'Count';
    public const COUNT_SECOND = 'Count/Second';
    public const GIGABITS = 'Gigabits';
    public const GIGABITS_SECOND = 'Gigabits/Second';
    public const GIGABYTES = 'Gigabytes';
    public const GIGABYTES_SECOND = 'Gigabytes/Second';
    public const KILOBITS = 'Kilobits';
    public const KILOBITS_SECOND = 'Kilobits/Second';
    public const KILOBYTES = 'Kilobytes';
    public const KILOBYTES_SECOND = 'Kilobytes/Second';
    public const MEGABITS = 'Megabits';
    public const MEGABITS_SECOND = 'Megabits/Second';
    public const MEGABYTES = 'Megabytes';
    public const MEGABYTES_SECOND = 'Megabytes/Second';
    public const MICROSECONDS = 'Microseconds';
    public const MILLISECONDS = 'Milliseconds';
    public const NONE = 'None';
    public const PERCENT = 'Percent';
    public const SECONDS = 'Seconds';
    public const TERABITS = 'Terabits';
    public const TERABITS_SECOND = 'Terabits/Second';
    public const TERABYTES = 'Terabytes';
    public const TERABYTES_SECOND = 'Terabytes/Second';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::BITS => true,
            self::BITS_SECOND => true,
            self::BYTES => true,
            self::BYTES_SECOND => true,
            self::COUNT => true,
            self::COUNT_SECOND => true,
            self::GIGABITS => true,
            self::GIGABITS_SECOND => true,
            self::GIGABYTES => true,
            self::GIGABYTES_SECOND => true,
            self::KILOBITS => true,
            self::KILOBITS_SECOND => true,
            self::KILOBYTES => true,
            self::KILOBYTES_SECOND => true,
            self::MEGABITS => true,
            self::MEGABITS_SECOND => true,
            self::MEGABYTES => true,
            self::MEGABYTES_SECOND => true,
            self::MICROSECONDS => true,
            self::MILLISECONDS => true,
            self::NONE => true,
            self::PERCENT => true,
            self::SECONDS => true,
            self::TERABITS => true,
            self::TERABITS_SECOND => true,
            self::TERABYTES => true,
            self::TERABYTES_SECOND => true,
        ][$value]);
    }
}
