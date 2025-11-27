<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the quality of your variable bitrate (VBR) AAC audio. For a list of approximate VBR bitrates, see:
 * https://docs.aws.amazon.com/mediaconvert/latest/ug/aac-support.html#aac_vbr
 */
final class AacVbrQuality
{
    public const HIGH = 'HIGH';
    public const LOW = 'LOW';
    public const MEDIUM_HIGH = 'MEDIUM_HIGH';
    public const MEDIUM_LOW = 'MEDIUM_LOW';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::HIGH => true,
            self::LOW => true,
            self::MEDIUM_HIGH => true,
            self::MEDIUM_LOW => true,
        ][$value]);
    }
}
