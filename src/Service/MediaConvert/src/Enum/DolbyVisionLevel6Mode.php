<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use Dolby Vision Mode to choose how the service will handle Dolby Vision MaxCLL and MaxFALL properies.
 */
final class DolbyVisionLevel6Mode
{
    public const PASSTHROUGH = 'PASSTHROUGH';
    public const RECALCULATE = 'RECALCULATE';
    public const SPECIFY = 'SPECIFY';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::PASSTHROUGH => true,
            self::RECALCULATE => true,
            self::SPECIFY => true,
        ][$value]);
    }
}
