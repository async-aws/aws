<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * If you are setting up the picture as a tile, you must set this to "disabled". In other configurations, you typically
 * enter "enabled".
 */
final class H265MvTemporalPredictor
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
