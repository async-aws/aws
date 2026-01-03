<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Controls the amount of phase-shift applied to the surround channels. Only used for 3/2 coding mode.
 */
final class Eac3PhaseControl
{
    public const NO_SHIFT = 'NO_SHIFT';
    public const SHIFT_90_DEGREES = 'SHIFT_90_DEGREES';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::NO_SHIFT => true,
            self::SHIFT_90_DEGREES => true,
        ][$value]);
    }
}
