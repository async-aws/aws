<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When encoding 3/2 audio, controls whether the LFE channel is enabled.
 */
final class Eac3LfeControl
{
    public const LFE = 'LFE';
    public const NO_LFE = 'NO_LFE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::LFE => true,
            self::NO_LFE => true,
        ][$value]);
    }
}
