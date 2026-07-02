<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Enable or disable explicit weighted prediction for the H.264 encoder. Weighted prediction improves compression
 * efficiency for content with fading or brightness changes between frames.
 */
final class H264ExplicitWeightedPrediction
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
