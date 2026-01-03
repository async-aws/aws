<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use Level to set the MPEG-2 level for the video output.
 */
final class Mpeg2CodecLevel
{
    public const AUTO = 'AUTO';
    public const HIGH = 'HIGH';
    public const HIGH1440 = 'HIGH1440';
    public const LOW = 'LOW';
    public const MAIN = 'MAIN';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::HIGH => true,
            self::HIGH1440 => true,
            self::LOW => true,
            self::MAIN => true,
        ][$value]);
    }
}
