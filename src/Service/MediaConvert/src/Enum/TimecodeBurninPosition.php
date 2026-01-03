<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use Position under Timecode burn-in to specify the location the burned-in timecode on output video.
 */
final class TimecodeBurninPosition
{
    public const BOTTOM_CENTER = 'BOTTOM_CENTER';
    public const BOTTOM_LEFT = 'BOTTOM_LEFT';
    public const BOTTOM_RIGHT = 'BOTTOM_RIGHT';
    public const MIDDLE_CENTER = 'MIDDLE_CENTER';
    public const MIDDLE_LEFT = 'MIDDLE_LEFT';
    public const MIDDLE_RIGHT = 'MIDDLE_RIGHT';
    public const TOP_CENTER = 'TOP_CENTER';
    public const TOP_LEFT = 'TOP_LEFT';
    public const TOP_RIGHT = 'TOP_RIGHT';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::BOTTOM_CENTER => true,
            self::BOTTOM_LEFT => true,
            self::BOTTOM_RIGHT => true,
            self::MIDDLE_CENTER => true,
            self::MIDDLE_LEFT => true,
            self::MIDDLE_RIGHT => true,
            self::TOP_CENTER => true,
            self::TOP_LEFT => true,
            self::TOP_RIGHT => true,
        ][$value]);
    }
}
