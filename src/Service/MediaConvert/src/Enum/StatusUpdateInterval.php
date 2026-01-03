<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify how often MediaConvert sends STATUS_UPDATE events to Amazon CloudWatch Events. Set the interval, in seconds,
 * between status updates. MediaConvert sends an update at this interval from the time the service begins processing
 * your job to the time it completes the transcode or encounters an error.
 */
final class StatusUpdateInterval
{
    public const SECONDS_10 = 'SECONDS_10';
    public const SECONDS_12 = 'SECONDS_12';
    public const SECONDS_120 = 'SECONDS_120';
    public const SECONDS_15 = 'SECONDS_15';
    public const SECONDS_180 = 'SECONDS_180';
    public const SECONDS_20 = 'SECONDS_20';
    public const SECONDS_240 = 'SECONDS_240';
    public const SECONDS_30 = 'SECONDS_30';
    public const SECONDS_300 = 'SECONDS_300';
    public const SECONDS_360 = 'SECONDS_360';
    public const SECONDS_420 = 'SECONDS_420';
    public const SECONDS_480 = 'SECONDS_480';
    public const SECONDS_540 = 'SECONDS_540';
    public const SECONDS_60 = 'SECONDS_60';
    public const SECONDS_600 = 'SECONDS_600';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::SECONDS_10 => true,
            self::SECONDS_12 => true,
            self::SECONDS_120 => true,
            self::SECONDS_15 => true,
            self::SECONDS_180 => true,
            self::SECONDS_20 => true,
            self::SECONDS_240 => true,
            self::SECONDS_30 => true,
            self::SECONDS_300 => true,
            self::SECONDS_360 => true,
            self::SECONDS_420 => true,
            self::SECONDS_480 => true,
            self::SECONDS_540 => true,
            self::SECONDS_60 => true,
            self::SECONDS_600 => true,
        ][$value]);
    }
}
