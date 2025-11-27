<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * The cadence MediaConvert follows for generating thumbnails. If set to FOLLOW_IFRAME, MediaConvert generates
 * thumbnails for each IDR frame in the output (matching the GOP cadence). If set to FOLLOW_CUSTOM, MediaConvert
 * generates thumbnails according to the interval you specify in thumbnailInterval.
 */
final class DashIsoIntervalCadence
{
    public const FOLLOW_CUSTOM = 'FOLLOW_CUSTOM';
    public const FOLLOW_IFRAME = 'FOLLOW_IFRAME';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::FOLLOW_CUSTOM => true,
            self::FOLLOW_IFRAME => true,
        ][$value]);
    }
}
