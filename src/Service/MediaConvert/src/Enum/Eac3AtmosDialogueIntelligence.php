<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Enable Dolby Dialogue Intelligence to adjust loudness based on dialogue analysis.
 */
final class Eac3AtmosDialogueIntelligence
{
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
        ][$value]);
    }
}
